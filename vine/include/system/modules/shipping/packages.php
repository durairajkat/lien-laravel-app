<?php

// Dependencies
require_once 'etc/boxing.class.php';

/**
 * @author  Tell Konkle
 * @date    2019-03-27
 */
class Shipping_Packages
{
    /**
     * Path to box size CSV.
     */
    const DEFAULT_BOX_SIZES = 'etc/box-sizes.csv';

    /**
     * Default package weight and sizes.
     */
    const DEFAULT_WEIGHT = 6;
    const DEFAULT_LENGTH = 9;
    const DEFAULT_WIDTH  = 6;
    const DEFAULT_HEIGHT = 4;

    /**
     * Packaging errors.
     * ---
     * @var  array
     */
    protected $errors = NULL;

    /**
     * All of the items in this package. The array format is as follows:
     * ---
     * [
     *     0 => [
     *         'weight' => 0.00,
     *         'length' => 0.00,
     *         'width'  => 0.00,
     *         'height' => 0.00,
     *         'volume' => 0.00,
     *     ],
     * ];
     * ---
     * @var  array
     */
    protected $items = [];

    /**
     * Valid box sizes. The array format is as follows:
     * ---
     * [
     *     0 => [
     *         'length' => 0.00,
     *         'width'  => 0.00,
     *         'height' => 0.00,
     *         'volume' => 0.00,
     *     ],
     * ];
     * ---
     * @var  array
     */
    protected $boxes = [];

    /**
     * Packages in this shipment. The array format is as follows:
     * ---
     * [
     *     0 => [
     *         'weight' => 0.00,
     *         'length' => 0.00,
     *         'width'  => 0.00,
     *         'height' => 0.00,
     *         'volume' => 0.00,
     *         'items'  => [
     *             0 => [
     *                 'weight' => 0,
     *                 'length' => 0,
     *                 'width'  => 0,
     *                 'height' => 0,
     *                 'volume' => 0,
     *             ],
     *         ],
     *     ],
     * ];
     * ---
     * @var  array
     */
    protected $packages = [];

    /**
     * The total number of items put into a box.
     * ---
     * @var  int
     */
    protected $packedItemCount = 0;

    /**
     * Which is the biggest box in overall volume and what key is it in the array?
     * ---
     * @var  int|float
     */
    protected $biggestVolume = 0;
    protected $biggestKey    = 0;

    /**
     * Class contructor. Load and parse box sizes.
     * ---
     * @param   string|array  [optional] Array of box sizes, or file path to box sizes.
     * @return  void
     */
    public function __construct($boxes = NULL)
    {
        // Load a custom array of box sizes
        if (is_array($boxes) && ! empty($boxes)) {
            $this->_loadBoxArray($boxes);
        // Load a specified array of box sizes
        } elseif (strlen($boxes)) {
            $this->_loadBoxCsv($boxes);
        // Load the default array of box sizes
        } else {
            $this->_loadBoxCsv(dirname(__FILE__) . '/' . self::DEFAULT_BOX_SIZES);
        }
    }

    /**
     * Set a packaging error.
     * ---
     * @param   string  Error message.
     * @return  void
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Get a packaging error.
     * ---
     * @return  array|bool  FALSE if no errors found, array otherwise.
     */
    public function getErrors()
    {
        return $this->errors ? $this->errors : FALSE;
    }

    /**
     * Are the packages valid?
     * ---
     * @return  bool
     */
    public function isValid()
    {
        return $this->errors ? FALSE : TRUE;
    }

    /**
     * Add an item to package.
     * ---
     * @param   int|float  The quantity of this item.
     * @param   int|float  Weight of item in ounces or kilograms.
     * @param   int|float  Length of item in inches or centimeters.
     * @param   int|float  Width of item in inches or centimeters.
     * @param   int|float  Height of item in inches or centimeters.
     * @param   string     [optional] 'US' or 'Metric' measurements (case insensitive).
     * @return  void
     */
    public function setItem($qty, $weight, $length, $width, $height, $unit = 'US')
    {
        try {
            // Standardize quantity and unit of measurement
            $qty  = (int) $qty > 0 ? (int) $qty : 1;
            $unit = strtoupper($unit);

            // Invalid weight
            if ( ! is_numeric($weight)) {
                throw new LogicException('Invalid weight. Must be numeric.');
            // Invalid length
            } elseif ( ! is_numeric($length)) {
                throw new LogicException('Invalid length. Must be numeric.');
            // Invalid width
            } elseif ( ! is_numeric($width)) {
                throw new LogicException('Invalid width. Must be numeric.');
            // Invalid height
            } elseif ( ! is_numeric($height)) {
                throw new LogicException('Invalid height. Must be numeric.');
            // Invalid unit of measurement
            } elseif ('US' !== $unit && 'METRIC' !== $unit) {
                throw new LogicException('Invalid unit. "US" or "metric" strings.');
            }

            // No conversion needed
            if ($unit === 'US') {
                $item = [
                    'weight' => (float) $weight,
                    'length' => (float) $length,
                    'width'  => (float) $width,
                    'height' => (float) $height,
                ];
            // Convert kilograms to ounces and centimeters to inches
            } elseif ($weight > 0) {
                $item = [
                    'weight' => (float) round(($weight / 0.45359237) * 16, 2),
                    'length' => (float) $length * 2.54,
                    'width'  => (float) $width  * 2.54,
                    'height' => (float) $height * 2.54,
                ];
            // No weight (eeek!)
            } else {
                $item = [
                    'weight' => self::DEFAULT_WEIGHT,
                    'length' => $length ? $length * 2.54 : self::DEFAULT_LENGTH,
                    'width'  => $width  ? $width  * 2.54 : self::DEFAULT_WIDTH,
                    'height' => $height ? $height * 2.54 : self::DEFAULT_HEIGHT,
                ];
            }

            // Calculate volume
            $item['volume'] = $item['length'] * $item['width'] * $item['height'];

            // Save item
            for ($i = 0; $i < $qty; $i++) {
                $this->items[] = $item;
            }
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Compile a list of packages based on the items that were set.
     * ---
     * @return  bool  TRUE if packages compiled successfully, FALSE otherwise.
     */
    public function compile()
    {
        try {
            // Verify there are items to work with
            if (empty($this->items) || ! $this->items) {
                throw new LogicException('No items were set with setItem().');
            }

            // Sort the items in shipment from the largest to the smallest
            uasort($this->items, ['self', 'sortItems']);

            // Items in current package, weight of current package
            $items  = [];
            $weight = 0;

            // Loop through each item in shipment
            foreach ($this->items as $key => $item) {
                // Add this item to package, add weight to package
                $items[$key] = $item;
                $weight     += $item['weight'];
                $fits        = FALSE;

                // Loop through each box and see if items fit in it
                foreach ($this->boxes as $box) {
                    // Define the box
                    $bin = new boxing();
                    $bin->add_outer_box($box['length'], $box['width'], $box['height']);

                    // Add items to box
                    foreach ($items as $i) {
                        $bin->add_inner_box($i['length'], $i['width'], $i['height']);
                    }

                    // All the items so far fit in box, move on
                    if ($bin->fits()) {
                        $fits = TRUE;
                        break;
                    }
                }

                // No boxes were big enough to hold the item(s)
                if ( ! $fits) {
                    // Remove this item and it's weight from the package being assembled
                    unset($items[$key]);
                    $weight -= $item['weight'];

                    // Put the other items into a box
                    if ( ! empty($items)) {
                        // Put items into a box so script can start filling another box
                        $this->buildPackage($items);

                        // Reset everything
                        $items  = array($item);
                        $weight = $item['weight'];
                    // Put this item in its' own box
                    } else {
                        $this->buildPackage(array($item));
                    }
                }
            }

            // Place all left over items into a box
            if ( ! empty($items)) {
                $this->buildPackage($items);
            }
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Get an array of all of the compiled packages.
     * ---
     * @return  bool|array  FALSE if no packages were compiled, array otherwise.
     */
    public function getPackages()
    {
        return empty($this->packages) ? FALSE : $this->packages;
    }

    /**
     * Get an array of all of the items in the packages.
     * ---
     * @return  bool|array  FALSE if no items found, array otherwise.
     */
    public function getItems()
    {
        return empty($this->items) ? FALSE : $this->items;
    }

    /**
     * Build a package and find the best box size for it.
     * ---
     * @param   array  The items to put in box.
     * @return  void
     */
    protected function buildPackage(array $items)
    {
        // Only one item in shipment, item is it's own box
        if (1 === count($items)) {
            // The details for the one item
            $i = array_values($items);

            // Make package using item dimensions
            $this->setPackage(
                $i[0]['weight'],
                $i[0]['length'],
                $i[0]['width'],
                $i[0]['height'],
                $items
            );

            // Stop here
            return;
        }

        // Loop through each box until one is found that fits
        foreach ($this->boxes as $box) {
            // Setup bin calculator
            $bin = new boxing();
            $bin->add_outer_box($box['length'], $box['width'], $box['height']);

            // Calculate the total weight for this shipment
            $weight = 0;

            // Loop through each item in this package and add it to this box
            foreach ($items as $i) {
                $bin->add_inner_box($i['length'], $i['width'], $i['height']);
                $weight += $i['weight'];
            }

            // This stuff fits in box, stop here
            if ($bin->fits()) {
                // Spiffy; all this stuff apparently fits in this box
                $this->setPackage(
                    $weight,
                    $box['length'],
                    $box['width'],
                    $box['height'],
                    $items
                );

                // Stop here
                return;
            }
        }

        // The largest box available
        $box = $this->boxes[$this->biggestKey];

        // :-( None of the boxes were big enough, so just choose largest box
        $this->setPackage(
            $weight,
            $box['length'],
            $box['width'],
            $box['height'],
            $items
        );
    }

    /**
     * Add a package to this shipment.
     * ---
     * @param   int|float  The package weight.
     * @param   int|float  The package length.
     * @param   int|float  The package width.
     * @param   int|float  The package height.
     * @param   array      All of the items in this package.
     * @return  void
     */
    public function setPackage($weight, $length, $width, $height, $items = NULL)
    {
        // Number of items to pack
        $this->packedItemCount += count($items);

        // Pack this box
        $this->packages[] = [
            'weight' => $weight,
            'length' => $length,
            'width'  => $width,
            'height' => $height,
            'volume' => $width * $length * $height,
            'items'  => $items,
        ];
    }

    /**
     * Sort the items in this shipment from the biggest to the smallest.
     * ---
     * @param   mixed
     * @param   mixed
     * @return  bool
     */
    protected static function sortItems($a, $b)
    {
        if ($a['volume'] == $b['volume']) {
            return 0;
        }

        return $a['volume'] > $b['volume'] ? -1 : 1;
    }

    /**
     * Sort the available box sizes from smallest to biggest.
     * ---
     * @param   mixed
     * @param   mixed
     * @return  bool
     */
    protected static function sortBoxes($a, $b)
    {
        if ($a['volume'] == $b['volume']) {
            return 0;
        }

        return $a['volume'] < $b['volume'] ? -1 : 1;
    }

    /**
     * Load a CSV of all of the valid box sizes. The expected CSV format is as follows:
     * ---
     * length, width, height, volume
     * length, width, height, volume
     * ---
     * [!!!] Headers are allowed in the first row of the CSV. They are optional. The
     *       script will automatically know whether they are headers or not.
     * ---
     * @param   string  The path to the CSV. Can be absolute or relative.
     * @return  void
     */
    private function _loadBoxCsv($path) {
        try {
            // Not a full file path, try to find it automatically
            if ( ! is_file($path)) {
                $path = dirname(__FILE__) . '/' . $path;
            }

            // Still can't find it
            if ( ! is_file($path)) {
                throw new VineMissingFileException('Box CSV not found.');
            }

            // Open box size CSV
            $handle = fopen($path, 'r');

            // Loop through and save all box sizes
            $i = 0; while (($b = fgetcsv($handle, 0, ',')) !== FALSE) {
                // All of the data needed is present
                if (isset($b[0]) && isset($b[1]) && isset($b[2]) && isset($b[3])) {
                    // Skip this box (likely the header)
                    if ( ! is_numeric($b[0])) {
                        continue;
                    }

                    // Clean and standardize
                    $b[0] = (int) $b[0];
                    $b[1] = (int) $b[1];
                    $b[2] = (int) $b[2];
                    $b[3] = (int) $b[3];

                    // Save this box
                    $this->boxes[$i] = [
                        'length' => $b[0],
                        'width'  => $b[1],
                        'height' => $b[2],
                        'volume' => $b[3],
                    ];

                    // Is this the biggest box?
                    if ($this->biggestVolume < $b[3]) {
                        $this->biggestVolume = $b[3];
                        $this->biggestKey    = $i;
                    }

                    // Increment
                    $i++;
                }
            }

            // Sort the box sizes from the smallest to the largest
            if ( ! empty($this->boxes)) {
                uasort($this->boxes, array('self', 'sortBoxes'));
            }
        } catch (Vine_Exception $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Load an array of all of the valid box sizes. The expected array format is as
     * follows:
     * ---
     * [
     *     0 => [length, width, height, volume],
     *     1 => [length, width, height, volume],
     * ];
     * ---
     * @param   string  The array of valid box sizes.
     * @return  void
     */
    private function _loadBoxArray(array $boxes)
    {
        try {
            // Invalid box array
            if (empty($boxes)) {
                throw new VineDataException('Invalid box size array.');
            }

            // Standardize arrau
            $boxes = array_values($boxes);

            // Loop through all of the box sizes
            foreach ($boxes as $i => $b) {
                // All of the data needed is present
                if (isset($b[0]) && isset($b[1]) && isset($b[2]) && isset($b[3])) {
                    // Clean and standardize
                    $b[0] = (int) $b[0];
                    $b[1] = (int) $b[1];
                    $b[2] = (int) $b[2];
                    $b[3] = (int) $b[3];

                    // Save this box
                    $this->boxes[$i] = [
                        'length' => $b[0],
                        'width'  => $b[1],
                        'height' => $b[2],
                        'volume' => $b[3],
                    ];

                    // Is this the biggest box?
                    if ($this->biggestVolume < $b[3]) {
                        $this->biggestVolume = $b[3];
                        $this->biggestKey    = $i;
                    }
                }
            }

            // Sort the box sizes from the smallest to the largest
            if ( ! empty($this->boxes)) {
                uasort($this->boxes, ['self', 'sortBoxes']);
            }
        } catch (VineDataException $e) {
            Vine_Exception::handle($e);
        }
    }
}
