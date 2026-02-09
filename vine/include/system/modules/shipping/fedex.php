<?php

/**
 * @author  Tell Konkle
 * @date    2017-11-28
 */
class Shipping_Fedex extends Shipping_Base implements Shipping_Interface
{
    /**
     * Relative paths to the test and production WSDL files.
     */
    const WSDL_RATE_TEST = 'fedex/test-rate-service-v14.wsdl';
    const WSDL_RATE_PROD = 'fedex/prod-rate-service-v14.wsdl';

    /**
     * Default package/box type, pickup, service, and ship time.
     */
    const DEFAULT_BOX_TYPE  = 'YOUR_PACKAGING';
    const DEFAULT_PICKUP    = 'BUSINESS_SERVICE_CENTER';
    const DEFAULT_SERVICE   = 'FEDEX_GROUND';
    const DEFAULT_SHIP_TIME = '+1 day';

    /**
     * Supported FedEx package types.
     * ---
     * @var  array
     */
    protected static $boxTypes = [
        'FEDEX_BOX'      => 'FedEx® Box',
        'FEDEX_ENVELOPE' => 'FedEx® Envelope',
        'FEDEX_PAK'      => 'FedEx® Pack',
        'FEDEX_TUBE'     => 'FedEx® Tube',
        'YOUR_PACKAGING' => 'Your Packaging',
    ];

    /**
     * The type of packaging (i.e. boxes, envelopers, etc.).
     * ---
     * @var  string
     */
    protected $boxType = NULL;

    /**
     * Supported FedEx pickup/dropoff types.
     * ---
     * [!!!] A complete list of FedEx dropoff types relative to their API values could not
     *       be found. This list was manually compiled and may be incomplete.
     * ---
     * @var  array
     */
    protected static $pickups = [
        'REGULAR_PICKUP'          => 'Regular Pickup',
        'REQUEST_COURIER'         => 'Request Courier',
        'DROP_BOX'                => 'Drop Box',
        'BUSINESS_SERVICE_CENTER' => 'Business Service Center',
        'STATION'                 => 'Station',
    ];

    /**
     * The pickup/dropoff method (i.e. pickup, dropoff, etc.).
     * ---
     * @var  string
     */
    protected $pickup = NULL;

    /**
     * Supported FedEx services.
     * ---
     * [!!!] A complete list of FedEx services relative to their API values could not be
     *       found. This list was manually compiled and may be incomplete.
     * ---
     * @var  array
     */
    protected static $services = [
        'EUROPE_FIRST_INTERNATIONAL_PRIORITY' => 'FedEx Europe First® International Priority',
        'FEDEX_1_DAY_FREIGHT'                 => 'FedEx 1Day® Freight',
        'FEDEX_2_DAY'                         => 'FedEx 2Day®',
        'FEDEX_2_DAY_AM'                      => 'FedEx 2Day® A.M.',
        'FEDEX_2_DAY_FREIGHT'                 => 'FedEx 2Day® Freight',
        'FEDEX_3_DAY_FREIGHT'                 => 'FedEx 3Day® Freight',
        'FEDEX_EXPRESS_SAVER'                 => 'FedEx Express Saver®',
        'FEDEX_FIRST_FREIGHT'                 => 'FedEx First Freight®',
        'FEDEX_GROUND'                        => 'FedEx Ground®',
        'FEDEX_HOME_DELIVERY'                 => 'FedEx Home Delivery®',
        'FIRST_OVERNIGHT'                     => 'FedEx First Overnight®',
        'INTERNATIONAL_ECONOMY'               => 'FedEx International Economy®',
        'INTERNATIONAL_ECONOMY_FREIGHT'       => 'FedEx International Economy® Freight',
        'INTERNATIONAL_FIRST'                 => 'FedEx International First®',
        'INTERNATIONAL_PRIORITY'              => 'FedEx International Priority®',
        'INTERNATIONAL_PRIORITY_FREIGHT'      => 'FedEx International Priority® Freight',
        'PRIORITY_OVERNIGHT'                  => 'FedEx Priority Overnight®',
        'STANDARD_OVERNIGHT'                  => 'FedEx Standard Overnight®',
    ];

    /**
     * FedEx services listed by order of importance. Helps pick default method.
     * ---
     * @var  array
     */
    protected static $servicesOrder = [
        'FEDEX_GROUND'                        => 1,
        'FEDEX_EXPRESS_SAVER'                 => 2,
        'FEDEX_HOME_DELIVERY'                 => 3,
        'FEDEX_2_DAY'                         => 4,
        'FEDEX_2_DAY_AM'                      => 5,
        'STANDARD_OVERNIGHT'                  => 6,
        'PRIORITY_OVERNIGHT'                  => 7,
        'FIRST_OVERNIGHT'                     => 8,
        'FEDEX_FIRST_FREIGHT'                 => 9,
        'FEDEX_1_DAY_FREIGHT'                 => 10,
        'FEDEX_2_DAY_FREIGHT'                 => 11,
        'FEDEX_3_DAY_FREIGHT'                 => 12,
        'EUROPE_FIRST_INTERNATIONAL_PRIORITY' => 13,
        'INTERNATIONAL_ECONOMY'               => 14,
        'INTERNATIONAL_FIRST'                 => 15,
        'INTERNATIONAL_PRIORITY'              => 16,
        'INTERNATIONAL_ECONOMY_FREIGHT'       => 17,
        'INTERNATIONAL_PRIORITY_FREIGHT'      => 18,
    ];

    /**
     * The service type (i.e. express, ground, etc.).
     * ---
     * @var  string
     */
    protected $service = NULL;

    /**
     * FedEx API access credentials.
     * ---
     * @var  string
     */
    protected $key      = NULL;
    protected $password = NULL;
    protected $account  = NULL;
    protected $meter    = NULL;

    /**
     * SOAP request and response.
     * ---
     * @var  string
     */
    protected $lastRequest  = NULL;
    protected $lastResponse = NULL;

    /**
     * Parsed rates from API.
     * ---
     * @var  array
     */
    protected $rates = NULL;

    /**
     * The total weight and volume of the shipment. Used for debugging purposes.
     * ---
     * @var  float|int
     */
    protected $weight = 0;
    protected $volume = 0;

    /**
     * Class constructor. Set the UPS API access credentials.
     * ---
     * @param   string  FedEx access key.
     * @param   string  FedEx password.
     * @param   string  FedEx account number.
     * @param   string  FedEx meter number.
     * @return  void
     */
    public function __construct($key, $password, $account, $meter)
    {
        try {
            // This class requires SoapClient
            if ( ! class_exists('SoapClient')) {
                throw new Exception("Soap extension hasn't been installed/enabled.");
            }

            // Don't cache WSDL file
            ini_set('soap.wsdl_cache_enabled', '0');

            // Save access credentials
            $this->key      = trim($key);
            $this->password = trim($password);
            $this->account  = trim($account);
            $this->meter    = trim($meter);
        // Fatal exception
        } catch (Exception $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Class destructor. Log all communications to and from the FedEx APIs.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Compile log data
        $data = "REQUEST:\n\n"
              . print_r($this->lastRequest, TRUE) . "\n\n"
              . "RESPONSE:\n\n"
              . print_r($this->lastResponse, TRUE);

        // Put the log into the log directory
        Vine_Log::logManual($data, 'shipping-fedex.log');
    }

    /**
     * Set the packaging/box type.
     * ---
     * [!!!] The packaging type must be a valid value. See the API documentation for a
     *       list of valid packaging types.
     * ---
     * @param   string  A valid packaging/box type.
     * @return  void
     */
    public function setBoxType($type)
    {
        // Standardize
        $type = strtolower(trim($type));

        // Verify
        if ( ! isset(self::$boxTypes[$type])) {
            $this->setError('Invalid box/packaging type.');
        }

        // Save
        $this->boxType = $type;
    }

    /**
     * Set the pickup type.
     * ---
     * [!!!] The pickup type must be a valid value. See the API documentation for a list
     *       of valid pickup types.
     * ---
     * @param   string  A valid pickup type.
     * @return  void
     */
    public function setPickup($pickup)
    {
        // Standardize
        $pickup = trim($pickup);

        // Verify
        if ( ! isset(self::$pickups[$pickup])) {
            $this->setError('Invalid pickup method.');
        }

        // Save
        $this->pickup = $pickup;
    }

    /**
     * Set the service type.
     * ---
     * [!!!] The service type must be a valid value. See the API documentation for a list
     *       of valid service types.
     * ---
     * @param   string  A valid service type.
     * @return  void
     */
    public function setService($service)
    {
        // Standardize
        $service = trim($service);

        // Verify
        if ( ! isset(self::$services[$service])) {
            $this->setError('Invalid service.');
        }

        // Save
        $this->service = $service;
    }

    /**
     * Send rate request to UPS API.
     * ---
     * @return  bool  TRUE if everything went well, FALSE otherwise.
     */
    public function processRateRequest()
    {
        // No need to communicate with the API if something already went wrong
        if ( ! $this->isValid()) {
            return FALSE;
        }

        // Compile the SOAP request
        $this->_compileRateRequest(TRUE);

        // Use test WSDL file
        if ($this->testMode) {
            $wsdl = dirname(__FILE__) . '/' . self::WSDL_RATE_TEST;
        // Use production WSDL file
        } else {
            $wsdl = dirname(__FILE__) . '/' . self::WSDL_RATE_PROD;
        }

        // Send request to FedEx and save result
        $client = new SoapClient($wsdl, array('trace' => 1));
        $this->lastResponse = $client->getRates($this->lastRequest);

        // (bool) Parse the SOAP response
        return $this->_parseRateResponse($this->lastResponse);
    }

    /**
     * Get shipping rates from a successful rate request.
     * ---
     * @return  bool|array  FALSE if no rates found, array otherwise.
     */
    public function getRates()
    {
        return $this->rates ? $this->rates : FALSE;
    }

    /**
     * Get a rate for a specified service type.
     * ---
     * @return  bool|string  FALSE if rate not found, string otherwise.
     */
    public function getRate($service)
    {
        return isset($this->rates[$service]) ? $this->rates[$service] : FALSE;
    }

    /**
     * Get an array of all of the supported box types.
     * ---
     * @return  array
     */
    public static function getBoxTypes()
    {
        return self::$boxTypes;
    }

    /**
     * Get an array of all of the supported pickup/dropoff types.
     * ---
     * @return  array
     */
    public static function getPickups()
    {
        return self::$pickups;
    }

    /**
     * Get an array of all of the supported services.
     * ---
     * @return  array
     */
    public static function getServices()
    {
        return self::$services;
    }

    /**
     * Get an array of all supported services in the order of their importance.
     * ---
     * @return  array
     */
    public static function getServicesOrder()
    {
        return self::$servicesOrder;
    }

    /**
     * Compile a SOAP rate request.
     * ---
     * @param   bool  TRUE = Shop for services, FALSE = Get rate for specific service.
     * @return  bool  TRUE if request successfully compiled, FALSE otherwise.
     */
    private function _compileRateRequest($shop = TRUE)
    {
        // Get applicable data
        $pickup   = $this->pickup  ? $this->pickup  : self::DEFAULT_PICKUP;
        $service  = $this->service ? $this->service : self::DEFAULT_SERVICE;
        $boxType  = $this->boxType ? $this->boxType : self::DEFAULT_BOX_TYPE;
        $packages = $this->_compilePackages();

        // Compile request
        $this->lastRequest = [
            'WebAuthenticationDetail' => [
                'UserCredential' => [
                    'Key'      => $this->key,
                    'Password' => $this->password,
                ],
            ],
            'ClientDetail' => [
                'AccountNumber' => $this->account,
                'MeterNumber'   => $this->meter,
            ],
            'TransactionDetail' => [
                'CustomerTransactionId' => 'Content Shelf Rate Request',
            ],
            'Version' => [
                'ServiceId'    => 'crs',
                'Major'        => '14',
                'Intermediate' => '0',
                'Minor'        => '0',
            ],
            'ReturnTransitAndCommit' => FALSE,
            'RequestedShipment' => [
                'DropoffType'               => $pickup,
                'ShipTimestamp'             => date('c', strtotime(self::DEFAULT_SHIP_TIME)),
                'ServiceType'               => $service,
                'PackagingType'             => $boxType,
                'Shipper'                   => $this->_compileShipFrom(),
                'Recipient'                 => $this->_compileShipTo(),
                'ShippingChargesPayment'    => $this->_compileShippingCharges(),
                'RateRequestTypes'          => 'LIST',
                'PackageCount'              => count($this->packages),
                'RequestedPackageLineItems' => $packages,
            ],
        ];

        // Shop for available services, remove packaging and service type
        if ($shop) {
            unset($this->lastRequest['RequestedShipment']['ServiceType']);
            unset($this->lastRequest['RequestedShipment']['PackagingType']);
        }

        // (bool) Everything was successfully compiled
        return TRUE;
    }

    /**
     * Compile the package details for the shipment.
     * ---
     * @return  array  A PHP array compatible with the SOAP schema.
     */
    private function _compilePackages()
    {
        // Get applicable data
        $packages = $this->packages;
        $result   = [];

        // Loop through and compile each package in the shipment
        $i = 1; foreach ($packages as $package) {
            // Compile package
            $result[] = [
                'SequenceNumber'    => $i,
                'GroupPackageCount' => 1,
                'Weight' => [
                    'Value' => $this->ouncesToPounds($package['weight'], 1),
                    'Units' => 'LB',
                ],
                'Dimensions' => [
                    'Length' => $package['length'],
                    'Width'  => $package['width'],
                    'Height' => $package['height'],
                    'Units'  => 'IN',
                ],
            ];

            // Add totals (for debugging)
            $this->weight += $this->ouncesToPounds($package['weight'], 1);
            $this->volume += $package['volume'];

            // Increment
            $i++;
        }

        // (array) All of the compiled packages
        return $result;
    }

    /**
     * Compile the ship from address.
     * ---
     * @return  array  SOAP ready ship from address.
     */
    private function _compileShipFrom()
    {
        // Compile address
        $address = [
            'Contact' => [
                'PersonName'  => $this->from->getName(),
                'CompanyName' => $this->from->getCompany(),
            ],
            'Address' => [
                'StreetLines' => [
                    $this->from->getAddress1(),
                    $this->from->getAddress2(),
                ],
                'City'                => $this->from->getCity(),
                'StateOrProvinceCode' => $this->from->getProvince(),
                'PostalCode'          => $this->from->getPostal(),
                'CountryCode'         => $this->from->getCountry(),
            ],
        ];

        // Remove person name if it's not used
        if ( ! strlen($address['Contact']['PersonName'])) {
            unset($address['Contact']['PersonName']);
        }

        // Remove company name if it's not used
        if ( ! strlen($address['Contact']['CompanyName'])) {
            unset($address['Contact']['CompanyName']);
        }

        // Remove address line 2 if it's not used
        if ( ! strlen($address['Address']['StreetLines'][1])) {
            unset($address['Address']['StreetLines'][1]);
        }

        // (array) Compiled address
        return $address;
    }

    /**
     * Compile the ship to address.
     * ---
     * @return  array  SOAP ready ship to address.
     */
    private function _compileShipTo()
    {
        // Compile address
        $address = [
            'Contact' => [
                'PersonName'  => $this->to->getName(),
                'CompanyName' => $this->to->getCompany(),
            ],
            'Address' => [
                'StreetLines' => [
                    $this->to->getAddress1(),
                    $this->to->getAddress2(),
                ],
                'City'                => $this->to->getCity(),
                'StateOrProvinceCode' => $this->to->getProvince(),
                'PostalCode'          => $this->to->getPostal(),
                'CountryCode'         => $this->to->getCountry(),
            ],
        ];

        // Remove person name if it's not used
        if ( ! strlen($address['Contact']['PersonName'])) {
            unset($address['Contact']['PersonName']);
        }

        // Remove company name if it's not used
        if ( ! strlen($address['Contact']['CompanyName'])) {
            unset($address['Contact']['CompanyName']);
        }

        // Remove address line 2 if it's not used
        if ( ! strlen($address['Address']['StreetLines'][1])) {
            unset($address['Address']['StreetLines'][1]);
        }

        // (array) Compiled address
        return $address;
    }

    /**
     * Compile the shipping charge details for a rate request.
     * ---
     * @return  array  SOAP ready shipping charges array.
     */
    private function _compileShippingCharges()
    {
        // Compile shipping charge details
        $result = [
            'PaymentType' => 'SENDER',
            'Payor'       => [
                'ResponsibleParty' => [
                    'AccountNumber' => $this->account,
                    'Contact'       => $this->from->getName(),
                    'Address'       => [
                        'CountryCode' => $this->from->getCountry(),
                    ],
                ],
            ],
        ];

        // (array) Compiled details
        return $result;
    }

    /**
     * Parse a SOAP rate request response.
     * ---
     * @param   mixed  The last response from the FedEx API.
     * @return  bool   TRUE if response parsed successfully, FALSE otherwise.
     */
    private function _parseRateResponse($res)
    {
        // API did not send back an SOAP object to parse, stop here
        if ( ! $res || ! isset($res->HighestSeverity)) {
            $this->setError('The API did not send back a valid response.');
            return FALSE;
        }

        // Request failed, stop here
        if ('FAILURE' === $res->HighestSeverity || 'ERROR' === $res->HighestSeverity) {
            $this->setError($res->Notifications->Message);
            return FALSE;
        }

        // Request successful but no valid rates
        if ( ! isset($res->RateReplyDetails)) {
            // A message has been sent back
            if (isset($res->Notifications->Messaage)) {
                $this->setError($res->Notifications->Message);
            // No message has been sent back (very unlikely)
            } else {
                // Generic error message
                $this->setError('No valid shipping rates could be calculated. Please '
                              . 'verify your shipping address.');

                // Stop here
                return FALSE;
            }
        }

        // Valid rates have been returned
        if (is_array($res->RateReplyDetails)) {
            // Loop through each rate returned
            foreach ($res->RateReplyDetails as $rate) {
                // Service code
                $c = $rate->ServiceType;

                // Total charge
                $t = $rate->RatedShipmentDetails[0]
                   ->ShipmentRateDetail
                   ->TotalNetCharge
                   ->Amount;

                // This is not a supported service, skip to next rate in loop
                if ( ! isset(self::$services[$c])) {
                    continue;
                }

                // Compile rate
                $this->rates[$c] = [
                    'code'  => $c,
                    'name'  => self::$services[$c],
                    'price' => number_format($t, 2, '.', ''),
                ];
            }
        }

        // Sort the shipping rates
        if ($this->rates) {
            uasort($this->rates, ['self', 'sortRates']);
        }

        // (bool) Everything went good?
        return $this->rates ? TRUE : FALSE;
    }
}