<?php

/**
 * @author  Tell Konkle
 * @date    2019-03-07
 */
class Shipping_Ups extends Shipping_Base implements Shipping_Interface
{
    /**
     * Paths to XML templates.
     */
    const TPL_ACCESS_PATH  = 'ups/access-request.xml';
    const TPL_RATE_PATH    = 'ups/rate-request.xml';
    const TPL_PACKAGE_PATH = 'ups/package.xml';

    /**
     * URLs to APIs.
     */
    const URL_TEST = 'https://wwwcie.ups.com/ups.app/xml/Rate';
    const URL_LIVE = 'https://onlinetools.ups.com/ups.app/xml/Rate';

    /**
     * Default package/box type, pickup, service, and customer classification.
     */
    const DEFAULT_BOX_TYPE = '02';
    const DEFAULT_PICKUP   = '03';
    const DEFAULT_SERVICE  = '03';
    const DEFAULT_CLASS    = '04';

    /**
     * Supported customer classifications.
     * ---
     * @var  array
     */
    protected static $classifications = [
        '00' => 'Specialized Rates',
        '01' => 'Daily Rates',
        '04' => 'Retail Rates',
        '53' => 'Standard List Rates',
    ];

    /**
     * The customer classification for this shipment.
     * ---
     * @var  string
     */
    protected $classification = NULL;

    /**
     * Supported UPS package types.
     * ---
     * @var  array
     */
    protected static $boxTypes = [
        '00' => 'UNKNOWN',
        '01' => 'UPS Letter',
        '02' => 'Package',
        '03' => 'Tube',
        '04' => 'Pak',
        '21' => 'Express Box',
        '24' => '25KG Box',
        '25' => '10KG Box',
        '30' => 'Pallet',
        '2a' => 'Small Express Box',
        '2b' => 'Medium Express Box',
        '2c' => 'Large Express Box',
    ];

    /**
     * The type of packaging (i.e. boxes, envelopers, etc.).
     * ---
     * @var  string
     */
    protected $boxType = NULL;

    /**
     * Supported UPS pickup/dropoff types.
     * ---
     * @var  array
     */
    protected static $pickups = [
        '01' => 'Daily Pickup',
        '03' => 'Customer Counter',
        '06' => 'One Time Pickup',
    ];

    /**
     * The pickup/dropoff method (i.e. pickup, dropoff, etc.).
     * ---
     * @var  string
     */
    protected $pickup = NULL;

    /**
     * Supported UPS services.
     * ---
     * @var  array
     */
    protected static $services = [
        '01' => 'UPS Next Day Air®',
        '02' => 'UPS Second Day Air®',
        '03' => 'UPS Ground',
        '07' => 'UPS Worldwide ExpressSM',
        '08' => 'UPS Worldwide ExpeditedSM',
        '11' => 'UPS Standard Shipments',
        '12' => 'UPS Three-Day Select®',
        '14' => 'UPS Next Day Air® Early A.M. SM',
        '54' => 'UPS Worldwide Express PlusSM',
        '59' => 'UPS Second Day Air A.M.®',
        '65' => 'UPS Saver',
    ];

    /**
     * UPS services listed by order of importance. Helps pick default method.
     * ---
     * @var  array
     */
    protected static $servicesOrder = [
        '03' => 1,  // UPS Ground
        '65' => 2,  // UPS Saver
        '11' => 3,  // UPS Standard Shipments
        '12' => 4,  // UPS Three-Day Select®
        '59' => 5,  // UPS Second Day Air A.M.®
        '02' => 6,  // UPS Second Day Air®
        '01' => 7,  // UPS Next Day Air®
        '14' => 8,  // UPS Next Day Air® Early A.M. SM
        '54' => 9,  // UPS Worldwide Express PlusSM
        '08' => 10, // UPS Worldwide ExpeditedSM
        '07' => 11, // UPS Worldwide ExpressSM
    ];

    /**
     * The service type (i.e. express, ground, etc.).
     * ---
     * @var  string
     */
    protected $service = NULL;

    /**
     * UPS API access credentials.
     * ---
     * @var  string
     */
    protected $userId   = NULL;
    protected $password = NULL;
    protected $key      = NULL;
    protected $account  = NULL;

    /**
     * XML request and response.
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
     * Class constructor. Set the UPS API access credentials.
     * ---
     * @param   string  UPS user ID.
     * @param   string  UPS account password.
     * @param   string  UPS license number/key.
     * @param   string  [optional] UPS account number (shipper number).
     * @return  void
     */
    public function __construct($userId, $password, $key, $account = NULL)
    {
        $this->userId   = trim($userId);
        $this->password = trim($password);
        $this->key      = trim($key);
        $this->account  = trim($account);
    }

    /**
     * Class destructor. Log all communications to and from the UPS APIs.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Compile log data
        $data = "REQUEST:\n\n"
              . $this->lastRequest . "\n\n"
              . "RESPONSE:\n\n"
              . (SHIPPING_DEBUG
              ? (new Vine_Tidy($this->lastResponse))->xml()
              : $this->lastResponse);

        // Put the log into the log directory
        Vine_Log::logManual($data, 'shipping-ups.log');
    }

    /**
     * Set the customer classification.
     * ---
     * [!!!] The classification must be a valid value. See the API documentation for
     *       a list of valid customer classification codes.
     * ---
     * @param   string  A customer classification code.
     * @return  void
     */
    public function setClassification($type)
    {
        // Standardize
        $type = strtolower(trim($type));

        // Verify
        if ( ! isset(self::$classifications[$type])) {
            $this->setError('Invalid customer classification.');
        }

        // Save
        $this->classification = $type;
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

        // Use test mode URL or production mode URL?
        $url = $this->testMode ? self::URL_TEST : self::URL_LIVE;

        // XML request to send to the API
        $request = $this->_compileRateRequest(TRUE);

        // Start cURL
        $handle = curl_init();

        // Prepare cURL
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HEADER, FALSE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request);

        // Execute cURL
        $this->lastResponse = curl_exec($handle);

        // Close cURL
        curl_close($handle);

        // (bool) Parse the XML response
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
     * Get an array of all of the supported customer classifications.
     * ---
     * @return  array
     */
    public static function getClassifications()
    {
        return self::$classifications;
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
     * Compile an XML rate request.
     * ---
     * @param   bool  TRUE = Shop for services, FALSE = Get rate for specific service.
     * @return  bool  TRUE if request successfully compiled, FALSE otherwise.
     */
    private function _compileRateRequest($shop = TRUE)
    {
        // Load access template
        $tpl    = file_get_contents(dirname(__FILE__) . '/' . self::TPL_ACCESS_PATH);
        $access = new SimpleXMLElement($tpl);

        // Modify template to suit
        $access->AccessLicenseNumber = Vine_Html::output($this->key);
        $access->UserId              = Vine_Html::output($this->userId);
        $access->Password            = Vine_Html::output($this->password);

        // Load rate template
        $tpl  = file_get_contents(dirname(__FILE__) . '/' . self::TPL_RATE_PATH);
        $rate = new SimpleXMLElement($tpl);

        // Set request type
        $rate->Request->RequestAction = 'Rate';
        $rate->Request->RequestOption = $shop ? 'Shop' : 'Rate';

        // Get applicable data
        $from     = $this->from;
        $to       = $this->to;
        $pickups  = self::$pickups;
        $services = self::$services;
        $packages = $this->packages;
        $pickup   = $this->pickup ? $this->pickup : self::DEFAULT_PICKUP;
        $service  = $this->service ? $this->service : self::DEFAULT_SERVICE;
        $customer = $this->classification ? $this->classification : self::DEFAULT_CLASS;
        $append   = '';

        // Set the pickup type
        $rate->PickupType->Code        = $pickup;
        $rate->PickupType->Description = $pickups[$pickup];

        // Customer classification can only be used for US based shippers
        if ('US' !== $from->getCountry()) {
            unset($rate->CustomerClassification);
        // Specify customer classification
        } else {
            $rate->CustomerClassification->Code = $customer;
        }

        // Set shipper number
        if ($this->account) {
            $rate->Shipment->Shipper->ShipperNumber = Vine_Html::output($this->account);
        // Shipper number not needed
        } else {
            unset($rate->Shipment->Shipper->ShipperNumber);
            unset($rate->Shipment->RateInformation);
        }

        // Set shipper address
        $rate->Shipment->Shipper->Address->AddressLine1      = $from->getAddress1();
        $rate->Shipment->Shipper->Address->AddressLine2      = $from->getAddress2();
        $rate->Shipment->Shipper->Address->City              = $from->getCity();
        $rate->Shipment->Shipper->Address->StateProvinceCode = $from->getProvince();
        $rate->Shipment->Shipper->Address->PostalCode        = $from->getPostal();
        $rate->Shipment->Shipper->Address->CountryCode       = $from->getCountry();

        // Set ship from address
        $rate->Shipment->ShipFrom->CompanyName                = $from->getCompany();
        $rate->Shipment->ShipFrom->AttentionName              = $from->getName();
        $rate->Shipment->ShipFrom->Address->AddressLine1      = $from->getAddress1();
        $rate->Shipment->ShipFrom->Address->AddressLine2      = $from->getAddress2();
        $rate->Shipment->ShipFrom->Address->City              = $from->getCity();
        $rate->Shipment->ShipFrom->Address->StateProvinceCode = $from->getProvince();
        $rate->Shipment->ShipFrom->Address->PostalCode        = $from->getPostal();
        $rate->Shipment->ShipFrom->Address->CountryCode       = $from->getCountry();

        // Set ship to address
        $rate->Shipment->ShipTo->CompanyName                = $to->getCompany();
        $rate->Shipment->ShipTo->AttentionName              = $to->getName();
        $rate->Shipment->ShipTo->Address->AddressLine1      = $to->getAddress1();
        $rate->Shipment->ShipTo->Address->AddressLine2      = $to->getAddress2();
        $rate->Shipment->ShipTo->Address->City              = $to->getCity();
        $rate->Shipment->ShipTo->Address->StateProvinceCode = $to->getProvince();
        $rate->Shipment->ShipTo->Address->PostalCode        = $to->getPostal();
        $rate->Shipment->ShipTo->Address->CountryCode       = $to->getCountry();

        // Set the service type
        if ( ! $shop) {
            $rate->Shipment->Service->Code        = $service;
            $rate->Shipment->Service->Description = $services[$service];
        // Service type not needed
        } else {
            unset($rate->Shipment->Service);
        }

        // Loop through each package in this shipment
        foreach ($packages as $package) {
            $append .= $this->_compilePackage($package);
        }

        // Save request
        $this->lastRequest = $access->asXML() . "\n"
                           . $this->_removeXmlTag($rate->asXML());

        // Inject package XML into XML request
        $this->lastRequest = str_replace('<Append/>', $append, $this->lastRequest);

        // (string) Return request
        return $this->lastRequest;
    }

    /**
     * Compile the details for a specified package in the shipment.
     * ---
     * @param   array   A valid package array from Shipping_Package.
     * @return  string  XML string.
     */
    private function _compilePackage(array $package)
    {
        // Get applicable data
        $boxTypes = self::$boxTypes;
        $boxType  = $this->boxType ? $this->boxType : self::DEFAULT_BOX_TYPE;

        // Load access template
        $tpl = file_get_contents(dirname(__FILE__) . '/' . self::TPL_PACKAGE_PATH);
        $pkg = new SimpleXMLElement($tpl);

        // Set all of the package details
        $pkg->PackagingType->Code        = $boxType;
        $pkg->PackagingType->Description = $boxTypes[$boxType];
        $pkg->Dimensions->Length         = $package['length'];
        $pkg->Dimensions->Width          = $package['width'];
        $pkg->Dimensions->Height         = $package['height'];
        $pkg->PackageWeight->Weight      = $this->ouncesToPounds($package['weight']);

        // (string) Package XML
        return $this->_removeXmlTag($pkg->asXml());
    }

    /**
     * Parse an XML rate request response.
     * ---
     * @param   string  The last response from the UPS API.
     * @return  bool    TRUE if response parsed successfully, FALSE otherwise.
     */
    private function _parseRateResponse($res)
    {
        // API did not send back an XML document to parse, stop here
        if (FALSE === strpos($res, '<?xml')) {
            $this->setError('The API did not send back a valid response.');
            return FALSE;
        }

        // Load response so it can be parsed
        $xml = new SimpleXMLElement($res, LIBXML_NOERROR | LIBXML_NOWARNING);

        // Request failed
        if (0 == $xml->Response->ResponseStatusCode) {
            $this->setError((string) $xml->Response->Error->ErrorDescription);
            return FALSE;
        }

        // Available services
        foreach ($xml->RatedShipment as $s) {
            // This service code and total
            $c = (string) $s->Service->Code;
            $t = (string) $s->TotalCharges->MonetaryValue;

            // Not a service this wrapper supports
            if ( ! isset(self::$services[$c])) {
                continue;
            }

            // Compile rates
            $this->rates[$c] = [
                'code'  => $c,
                'name'  => self::$services[$c],
                'price' => number_format($t, 2, '.', ''),
            ];
        }

        // Sort the shipping rates
        if ($this->rates) {
            uasort($this->rates, ['self', 'sortRates']);
        }

        // (bool) Everything went good?
        return $this->rates ? TRUE : FALSE;
    }

    /**
     * Remove the <?xml tag from the beginning of an XML document. Keeps things tidy.
     * ---
     * @param   string  The XML to tidy up.
     * @return  string
     */
    private function _removeXmlTag($xml)
    {
        $xml = explode("\n", $xml); array_shift($xml);
        $xml = implode("\n", $xml);
        return $xml;
    }
}
