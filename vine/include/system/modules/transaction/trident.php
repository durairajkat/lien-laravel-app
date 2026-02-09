<?php

/**
 * Wrapper class for the Merchant e-Solutions (MeS) Trident API.
 *
 * Test Credit Card Numbers:
 *
 * Visa               4012301230123010    Expiry Date: Any future date.
 * Mastercard         5123012301230120    Expiry Date: Any future date.
 * American Express   349999999999991     Expiry Date: Any future date.
 * JCB                3528288605211810    Expiry Date: Any future date.
 * Discover           6011011231231235    Expiry Date: Any future date.
 *
 * Test AVS Info:
 *
 * Street Address     Zip Code     AVS Result Code
 * 123                55555        Y – street and postal code match
 * 123                999991111    Y – street and postal code match (Visa)
 *                                 X – street and postal code match (MasterCard)
 * 123                EH8 9ST      D - exact match, international
 * 123                Other Zip    A - address match, zip mismatch
 * 234                Any Zip      U - address unavailable
 * 345                Any Zip      G - verification unavailable due to international
 *                                 issuer non-participation (Visa and MasterCard only)
 * 456                Any Zip      R - issuer system unavailable, retry
 * 235                Any Zip      S – AVS not supported
 * Other Address      55555        Z - address mismatch, 5-digit zip match
 * Other Address      EH8 9ST      Z - address mismatch, international zip match
 *
 * @author  Tell Konkle
 * @date    2014-03-13
 * @see     http://resources.merchante-solutions.com/display/MESPUB/Home
 */
class Transaction_Trident implements Transaction_Interface_Transaction
{
    /**
     * The default currency and country code to use when making a transaction.
     */
    const DEFAULT_CURRENCY = 'USD';
    const DEFAULT_COUNTRY  = 'US';

    /**
     * Transaction type codes. 
     */
    const CODE_SALE     = 'D';
    const CODE_PREAUTH  = 'P';
    const CODE_POSTAUTH = 'S';
    const CODE_REFUND   = 'U';
    const CODE_CREDIT   = 'C';

    /**
     * The test and production API urls to send all requests to. 
     */
    const URL_TEST = 'https://cert.merchante-solutions.com/mes-api/tridentApi';
    const URL_LIVE = 'https://api.merchante-solutions.com/mes-api/tridentApi';

    /**
     * The default end-user error message when a response can't be fully parsed.
     */
    const DEFAULT_ERROR = 'The transaction could not be processed.';

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * An array containing all of the parameters and values to send in the next API
     * operation.
     *
     * @var  array
     */
    protected $params = array();

    /**
     * An array containing the last response from the Trident API.
     * @var  array
     */
    protected $lastResponse = array();

    /**
     * ID of the last successful transaction, or ID manually set from a previous
     * transaction.
     *
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * Enable/disable test mode.
     * @var  bool
     * @see  __construct()
     */
    protected $testMode = FALSE;

    /**
     * ISO 3166-1 numeric country codes with their ISO 3166-1 alpha-2 counterparts.
     * @var     array
     * @author  Tell Konkle
     * @date    2014-03-11
     */
    protected $countryCodes = array
    (
        'AF' => '004', // Afghanistan
        'AX' => '248', // Åland Islands
        'AL' => '008', // Albania
        'DZ' => '012', // Algeria
        'AS' => '016', // American Samoa
        'AD' => '020', // Andorra
        'AO' => '024', // Angola
        'AI' => '660', // Anguilla
        'AQ' => '010', // Antarctica
        'AG' => '028', // Antigua and Barbuda
        'AR' => '032', // Argentina
        'AM' => '051', // Armenia
        'AW' => '533', // Aruba
        'AU' => '036', // Australia
        'AT' => '040', // Austria
        'AZ' => '031', // Azerbaijan
        'BS' => '044', // Bahamas
        'BD' => '048', // Bahrain
        'BD' => '050', // Bangladesh
        'BB' => '052', // Barbados
        'BY' => '112', // Belarus
        'BE' => '056', // Belgium
        'BZ' => '084', // Belize
        'BJ' => '204', // Benin
        'BM' => '060', // Bermuda
        'BT' => '064', // Bhutan
        'BO' => '068', // Bolivia, Plurinational State of
        'BQ' => '535', // Bonaire, Sint Eustatius and Saba
        'BA' => '070', // Bosnia and Herzegovina
        'BW' => '072', // Botswana
        'BV' => '074', // Bouvet Island
        'BR' => '076', // Brazil
        'IO' => '086', // British Indian Ocean Territory
        'BN' => '096', // Brunei Darussalam
        'BG' => '100', // Bulgaria
        'BF' => '854', // Burkina Faso
        'BI' => '108', // Burundi
        'KH' => '116', // Cambodia
        'CM' => '120', // Cameroon
        'CA' => '124', // Canada
        'CV' => '132', // Cape Verde
        'KY' => '136', // Cayman Islands
        'CF' => '140', // Central African Republic
        'TD' => '148', // Chad
        'CL' => '152', // Chile
        'CN' => '156', // China
        'CX' => '162', // Christmas Island
        'CC' => '166', // Cocos (Keeling) Islands
        'CO' => '170', // Colombia
        'KM' => '174', // Comoros
        'CG' => '178', // Congo
        'CD' => '180', // Congo, the Democratic Republic of the
        'CK' => '184', // Cook Islands
        'CR' => '188', // Costa Rica
        'CI' => '384', // Côte d'Ivoire
        'HR' => '191', // Croatia
        'CU' => '192', // Cuba
        'CW' => '531', // Curaçao
        'CY' => '196', // Cyprus
        'CZ' => '203', // Czech Republic
        'DK' => '208', // Denmark
        'DJ' => '262', // Djibouti
        'DM' => '212', // Dominica
        'DO' => '214', // Dominican Republic
        'EC' => '218', // Ecuador
        'EG' => '818', // Egypt
        'SV' => '222', // El Salvador
        'GQ' => '226', // Equatorial Guinea
        'ER' => '232', // Eritrea
        'EE' => '233', // Estonia
        'ET' => '231', // Ethiopia
        'FK' => '238', // Falkland Islands (Malvinas)
        'FO' => '234', // Faroe Islands
        'FJ' => '242', // Fiji
        'FI' => '246', // Finland
        'FR' => '250', // France
        'GF' => '254', // French Guiana
        'PF' => '258', // French Polynesia
        'TF' => '260', // French Southern Territories
        'GA' => '266', // Gabon
        'GM' => '270', // Gambia
        'GE' => '268', // Georgia
        'DE' => '276', // Germany
        'GH' => '288', // Ghana
        'GI' => '292', // Gibraltar
        'GR' => '300', // Greece
        'GL' => '304', // Greenland
        'GD' => '308', // Grenada
        'GP' => '312', // Guadeloupe
        'GU' => '316', // Guam
        'GT' => '320', // Guatemala
        'GG' => '831', // Guernsey
        'GN' => '324', // Guinea
        'GW' => '624', // Guinea-Bissau
        'GY' => '328', // Guyana
        'HT' => '332', // Haiti
        'HM' => '334', // Heard Island and McDonald Islands
        'VA' => '336', // Holy See (Vatican City State)
        'HN' => '340', // Honduras
        'HK' => '344', // Hong Kong
        'HU' => '348', // Hungary
        'IS' => '352', // Iceland
        'IN' => '356', // India
        'ID' => '360', // Indonesia
        'IR' => '364', // Iran, Islamic Republic of
        'IQ' => '368', // Iraq
        'IS' => '372', // Ireland
        'IM' => '833', // Isle of Man
        'IL' => '376', // Israel
        'IT' => '380', // Italy
        'JM' => '388', // Jamaica
        'JP' => '392', // Japan
        'JE' => '832', // Jersey
        'JO' => '400', // Jordan
        'KZ' => '398', // Kazakhstan
        'KE' => '404', // Kenya
        'KI' => '296', // Kiribati
        'KP' => '408', // Korea, Democratic People's Republic of
        'KR' => '410', // Korea, Republic of
        'KW' => '414', // Kuwait
        'KG' => '417', // Kyrgyzstan
        'LA' => '418', // Lao People's Democratic Republic
        'LV' => '428', // Latvia
        'LB' => '422', // Lebanon
        'LS' => '426', // Lesotho
        'LR' => '430', // Liberia
        'LR' => '434', // Libya
        'LI' => '438', // Liechtenstein
        'LT' => '440', // Lithuania
        'LU' => '442', // Luxembourg
        'MO' => '446', // Macao
        'MK' => '807', // Macedonia, the former Yugoslav Republic of
        'MG' => '450', // Madagascar
        'MW' => '454', // Malawi
        'MY' => '458', // Malaysia
        'MV' => '462', // Maldives
        'ML' => '466', // Mali
        'MT' => '470', // Malta
        'MH' => '584', // Marshall Islands
        'MQ' => '474', // Martinique
        'MR' => '478', // Mauritania
        'MU' => '480', // Mauritius
        'YT' => '175', // Mayotte
        'MX' => '484', // Mexico
        'FM' => '583', // Micronesia, Federated States of
        'MD' => '498', // Moldova, Republic of
        'MC' => '492', // Monaco
        'MN' => '496', // Mongolia
        'ME' => '499', // Montenegro
        'MS' => '500', // Montserrat
        'MA' => '504', // Morocco
        'MZ' => '508', // Mozambique
        'MM' => '104', // Myanmar
        'NA' => '516', // Namibia
        'NR' => '520', // Nauru
        'NP' => '524', // Nepal
        'NL' => '528', // Netherlands
        'NC' => '540', // New Caledonia
        'NZ' => '554', // New Zealand
        'NI' => '558', // Nicaragua
        'NE' => '562', // Niger
        'NG' => '566', // Nigeria
        'NU' => '570', // Niue
        'NF' => '574', // Norfolk Island
        'MP' => '580', // Northern Mariana Islands
        'NO' => '578', // Norway
        'OM' => '512', // Oman
        'PK' => '586', // Pakistan
        'PW' => '585', // Palau
        'PS' => '275', // Palestine, State of
        'PA' => '591', // Panama
        'PG' => '598', // Papua New Guinea
        'PY' => '600', // Paraguay
        'PE' => '604', // Peru
        'PH' => '608', // Philippines
        'PN' => '612', // Pitcairn
        'PL' => '616', // Poland
        'PT' => '620', // Portugal
        'PR' => '630', // Puerto Rico
        'QA' => '634', // Qatar
        'RE' => '638', // Réunion
        'RO' => '642', // Romania
        'RU' => '643', // Russian Federation
        'RW' => '646', // Rwanda
        'BL' => '652', // Saint Barthélemy
        'SH' => '654', // Saint Helena, Ascension and Tristan da Cunha
        'KN' => '659', // Saint Kitts and Nevis
        'LC' => '662', // Saint Lucia
        'MF' => '663', // Saint Martin (French part)
        'PM' => '666', // Saint Pierre and Miquelon
        'VC' => '670', // Saint Vincent and the Grenadines
        'WS' => '882', // Samoa
        'SM' => '674', // San Marino
        'ST' => '678', // Sao Tome and Principe
        'SA' => '682', // Saudi Arabia
        'SN' => '686', // Senegal
        'RS' => '688', // Serbia
        'SC' => '690', // Seychelles
        'SL' => '694', // Sierra Leone
        'SG' => '702', // Singapore
        'SX' => '534', // Sint Maarten (Dutch part)
        'SK' => '703', // Slovakia
        'SI' => '705', // Slovenia
        'SB' => '090', // Solomon Islands
        'SO' => '706', // Somalia
        'ZA' => '710', // South Africa
        'GS' => '239', // South Georgia and the South Sandwich Islands
        'SS' => '728', // South Sudan
        'ES' => '724', // Spain
        'LK' => '144', // Sri Lanka
        'SD' => '729', // Sudan
        'SR' => '740', // Suriname
        'SJ' => '744', // Svalbard and Jan Mayen
        'SZ' => '748', // Swaziland
        'SE' => '752', // Sweden
        'CH' => '756', // Switzerland
        'SY' => '760', // Syrian Arab Republic
        'TW' => '158', // Taiwan, Province of China
        'TJ' => '762', // Tajikistan
        'TZ' => '834', // Tanzania, United Republic of
        'TH' => '764', // Thailand
        'TL' => '626', // Timor-Leste
        'TG' => '768', // Togo
        'TK' => '772', // Tokelau
        'TO' => '776', // Tonga
        'TT' => '780', // Trinidad and Tobago
        'TN' => '788', // Tunisia
        'TR' => '792', // Turkey
        'TM' => '795', // Turkmenistan
        'TC' => '796', // Turks and Caicos Islands
        'TV' => '798', // Tuvalu
        'UG' => '800', // Uganda
        'UA' => '804', // Ukraine
        'AE' => '784', // United Arab Emirates
        'GB' => '826', // United Kingdom
        'US' => '840', // United States
        'UM' => '581', // United States Minor Outlying Islands
        'UY' => '858', // Uruguay
        'UZ' => '860', // Uzbekistan
        'VU' => '548', // Vanuatu
        'VE' => '862', // Venezuela, Bolivarian Republic of
        'VN' => '704', // Viet Nam
        'VG' => '092', // Virgin Islands, British
        'VI' => '850', // Virgin Islands, U.S.
        'WF' => '876', // Wallis and Futuna
        'EH' => '732', // Western Sahara
        'YE' => '887', // Yemen
        'ZM' => '894', // Zambia
        'ZW' => '716', // Zimbabwe
    );

    /**
     * ISO 4217 currency codes and numbers.
     * @var     array
     * @author  Tell Konkle
     * @date    2014-03-11
     */
    protected $currencyCodes = array
    (
        'AED' => '784',
        'AFN' => '971',
        'ALL' => '008',
        'AMD' => '051',
        'ANG' => '532',
        'AOA' => '973',
        'ARS' => '032',
        'AUD' => '036',
        'AWG' => '533',
        'AZN' => '944',
        'BAM' => '977',
        'BBD' => '052',
        'BDT' => '050',
        'BGN' => '975',
        'BHD' => '048',
        'BIF' => '108',
        'BMD' => '060',
        'BND' => '096',
        'BOB' => '068',
        'BOV' => '984',
        'BRL' => '986',
        'BSD' => '044',
        'BTN' => '064',
        'BWP' => '072',
        'BYR' => '974',
        'BZD' => '084',
        'CAD' => '124',
        'CDF' => '976',
        'CHE' => '947',
        'CHF' => '756',
        'CHW' => '948',
        'CLF' => '990',
        'CLP' => '152',
        'CNY' => '156',
        'COP' => '170',
        'COU' => '970',
        'CRC' => '188',
        'CUC' => '931',
        'CUP' => '192',
        'CVE' => '132',
        'CZK' => '203',
        'DJF' => '262',
        'DKK' => '208',
        'DOP' => '214',
        'DZD' => '012',
        'EGP' => '818',
        'ERN' => '232',
        'ETB' => '230',
        'EUR' => '978',
        'FJD' => '242',
        'FKP' => '238',
        'GBP' => '826',
        'GEL' => '981',
        'GHS' => '936',
        'GIP' => '292',
        'GMD' => '270',
        'GNF' => '324',
        'GTQ' => '320',
        'GYD' => '328',
        'HKD' => '344',
        'HNL' => '340',
        'HRK' => '191',
        'HTG' => '332',
        'HUF' => '348',
        'IDR' => '360',
        'ILS' => '376',
        'INR' => '356',
        'IQD' => '368',
        'IRR' => '364',
        'ISK' => '352',
        'JMD' => '388',
        'JOD' => '400',
        'JPY' => '392',
        'KES' => '404',
        'KGS' => '417',
        'KHR' => '116',
        'KMF' => '174',
        'KPW' => '408',
        'KRW' => '410',
        'KWD' => '414',
        'KYD' => '136',
        'KZT' => '398',
        'LAK' => '418',
        'LBP' => '422',
        'LKR' => '144',
        'LRD' => '430',
        'LSL' => '426',
        'LTL' => '440',
        'LYD' => '434',
        'MAD' => '504',
        'MDL' => '498',
        'MGA' => '969',
        'MKD' => '807',
        'MMK' => '104',
        'MNT' => '496',
        'MOP' => '446',
        'MRO' => '478',
        'MUR' => '480',
        'MVR' => '462',
        'MWK' => '454',
        'MXN' => '484',
        'MXV' => '979',
        'MYR' => '458',
        'MZN' => '943',
        'NAD' => '516',
        'NGN' => '566',
        'NIO' => '558',
        'NOK' => '578',
        'NPR' => '524',
        'NZD' => '554',
        'OMR' => '512',
        'PAB' => '590',
        'PEN' => '604',
        'PGK' => '598',
        'PHP' => '608',
        'PKR' => '586',
        'PLN' => '985',
        'PYG' => '600',
        'QAR' => '634',
        'RON' => '946',
        'RSD' => '941',
        'RUB' => '643',
        'RWF' => '646',
        'SAR' => '682',
        'SBD' => '090',
        'SCR' => '690',
        'SDG' => '938',
        'SEK' => '752',
        'SGD' => '702',
        'SHP' => '654',
        'SLL' => '694',
        'SOS' => '706',
        'SRD' => '968',
        'SSP' => '728',
        'STD' => '678',
        'SYP' => '760',
        'SZL' => '748',
        'THB' => '764',
        'TJS' => '972',
        'TMT' => '934',
        'TND' => '788',
        'TOP' => '776',
        'TRY' => '949',
        'TTD' => '780',
        'TWD' => '901',
        'TZS' => '834',
        'UAH' => '980',
        'UGX' => '800',
        'USD' => '840',
        'USN' => '997',
        'USS' => '998',
        'UYI' => '940',
        'UYU' => '858',
        'UZS' => '860',
        'VEF' => '937',
        'VND' => '704',
        'VUV' => '548',
        'WST' => '882',
        'XAF' => '950',
        'XAG' => '961',
        'XAU' => '959',
        'XBA' => '955',
        'XBB' => '956',
        'XBC' => '957',
        'XBD' => '958',
        'XCD' => '951',
        'XDR' => '960',
        'XOF' => '952',
        'XPD' => '964',
        'XPF' => '953',
        'XPT' => '962',
        'XTS' => '963',
        'XXX' => '999',
        'YER' => '886',
        'ZAR' => '710',
        'ZMW' => '967',
        'ZWL' => '932',
    );

    /**
     * All of the error codes that the API will return, alongside their respective error
     * messages.
     *
     * @var  array 
     */
    protected $errorCodes = array
    (
        '000' => TRUE,
        '101' => 'Invalid Profile ID or Profile Key.',
        '102' => 'Incomplete Request.',
        '103' => 'Invoice Number Length Error.',
        '104' => 'Reference Number Length Error.',
        '105' => 'AVS Address Length Error.',
        '106' => 'AVS Zip Length Error.',
        '107' => 'Merchant Name Length Error.',
        '108' => 'Merchant City Length Error.',
        '109' => 'Merchant State Length Error.',
        '110' => 'Merchant Zip Length Error.',
        '111' => 'Merchant Category Code Length Error.',
        '112' => 'Merchant Phone Length Error.',
        '113' => 'Reference Number Must Be Numeric.',
        '114' => 'Missing Card Holder Account Data.',
        '115' => 'Invalid Card Number.',
        '116' => 'Credits Not Allowed.',
        '117' => 'Card Type Not Accepted.',
        '118' => 'Currency Type Not Accepted.',
        '119' => 'Retry ID length error. Must be 16 characters or less.',
        '120' => 'An invoice number is required for a 3D enrollment check.',
        '121' => 'MOTO/e-Commerce indicator length error.',
        '122' => 'Non-USD offline transactions are not supported.',
        '123' => 'Client Reference Number length error.',
        '124' => 'Batch Number Required.',
        '125' => 'Invalid Batch Number.',
        '168' => 'Invalid Industry Code.',
        '201' => 'Invalid Transaction ID.',
        '202' => 'Invalid Transaction Amount.',
        '203' => 'Void Failed.',
        '204' => 'Transaction Already Settled.',
        '205' => 'Transaction Already Voided.',
        '206' => 'Transaction Already refunded.',
        '207' => 'Refund failed.',
        '208' => 'Failed to receive a response from auth host.',
        '209' => 'Invalid tax amount.',
        '210' => 'AVS result is declined by user.',
        '211' => 'CVV2 result is declined by user.',
        '212' => 'Refund amount must be between zero and the original amount.',
        '213' => 'Only sale transactions can be refunded.',
        '214' => 'Only one type of card data allowed per request.',
        '215' => 'Invalid Card ID.',
        '216' => 'Failed to load card data, retry request.',
        '217' => 'Failed to store card data, retry request.',
        '218' => 'Card ID parameter cannot be included in this type of transaction.',
        '219' => 'Offline transactions requires an authorization code.',
    );

    /**
     * Class constructor
     *
     * - Sets Profile ID.
     * - Sets Profile Key.
     * - Sets test mode.
     *
     * @param   string
     * @param   string
     * @return  void
     */
    public function __construct($profileId, $profileKey, $testMode = FALSE)
    {
	    $this->params['profile_id']  = $profileId;
	    $this->params['profile_key'] = $profileKey;
        $this->testMode              = (bool) $testMode;
    }

    /**
     * Class destructor.
     * @return  void
     */
    public function __destruct()
    {
        // Hide profile ID
        if (isset($this->params['profile_id'])) {
            $this->params['profile_id'] = '****';
        }

        // Hide profile key
        if (isset($this->params['profile_key'])) {
            $this->params['profile_key'] = '****';
        }

        // Hide credit card number
        if (isset($this->params['card_number'])) {
            $this->params['card_number'] = '****';
        }

        // Hide expiration date
        if (isset($this->params['card_exp_date'])) {
            $this->params['card_exp_date'] = '****';
        }

        // Hide security code
        if (isset($this->params['cvv2'])) {
            $this->params['cvv2'] = '****';
        }

        // Compile log data
        $data = "REQUEST:\n\n"
              . print_r($this->params, TRUE) . "\n"
              . "RESPONSE:\n\n"
              . print_r($this->lastResponse, TRUE);

        // Put the log into the log directory
        Vine_Log::logManual($data, 'mes-trident.log');
    }

    /**
     * Get human-readable error message.
     * @return  string  NULL if no error found.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set human readable error message.
     * @param   string
     * @return  void
     */
    public function setError($error)
    {
        if (strlen($error)) {
            $this->error = $error;
        }
    }

    /**
     * See if any errors or problems encountered during API communication.
     * @return  bool  TRUE if no errors found, FALSE otherwise.
     */
    public function isValid()
    {
        return NULL === $this->error;
    }

    /**
     * Get transaction ID generated from last successful transaction.
     * @return  string  NULL if no transaction ID present, string otherwise.
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Manually set transaction ID from previous preauth transaction or equivalent.
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
    }

    /**
     * Set the first name on card.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->params['cardholder_first_name'] = trim($firstName);
    }

    /**
     * Set the last name on card.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->params['cardholder_last_name'] = trim($lastName);
    }

    /**
     * Set the address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->params['cardholder_street_address'] = trim($address1);
    }

    /**
     * Set the second line of address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        // Second address line not applicable
        if ( ! strlen(trim($address2))) {
            return;
        }

        // Append address line 2 to address line 1
        if (isset($this->params['cardholder_street_address'])) {
            $this->params['cardholder_street_address'] .= ', ' . trim($address2);
        // Address line one hasn't been provided, yet (not good)
        } else {
            $this->params['cardholder_street_address'] = ', ' . trim($address2);
        }
    }

    /**
     * Set the city on file for card.
     *
     * [!!!] This method is not utilized in this class. The Trident API makes no use of
     *       city or state values.
     *
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        return;
    }

    /**
     * Set the state/province on file for card.
     *
     * [!!!] This method is not utilized in this class. The Trident API makes no use of
     *       city or state values.
     *
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        return;
    }

    /**
     * Set the zip/postal code on file for card.
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->params['cardholder_zip'] = trim($code);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        if ($this->getCountryCode($country)) {
            $this->params['country_code'] = $this->getCountryCode($country);
        } else {
            $this->params['country_code'] = $this->getCountryCode(self::DEFAULT_COUNTRY);
        }
    }

    /**
     * Set the ship-to first name.
     * @param   string
     * @return  void
     */
    public function setShipFirstName($firstName)
    {
        $this->params['ship_to_first_name'] = trim($firstName);
    }

    /**
     * Set the ship-to last name.
     * @param   string
     * @return  void
     */
    public function setShipLastName($lastName)
    {
        $this->params['ship_to_last_name'] = trim($lastName);
    }

    /**
     * Set the ship-to address.
     * @param   string
     * @return  void
     */
    public function setShipAddress1($address1)
    {
        $this->params['ship_to_address'] = trim($address1);
    }

    /**
     * Set the second line of ship-to address.
     * @param   string
     * @return  void
     */
    public function setShipAddress2($address2)
    {
        // Second address line not applicable
        if ( ! strlen(trim($address2))) {
            return;
        }

        // Append address line 2 to address line 1
        if (isset($this->params['ship_to_address'])) {
            $this->params['ship_to_address'] .= ', ' . trim($address2);
        // Address line one hasn't been provided, yet
        } else {
            $this->params['ship_to_address'] = ', ' . trim($address2);
        }
    }

    /**
     * Set the ship-to city.
     *
     * [!!!] This method is not utilized in this class. The Trident API makes no use of
     *       city or state values.
     *
     * @param   string
     * @return  void
     */
    public function setShipCity($city)
    {
        return;
    }

    /**
     * Set the ship-to state/province.
     *
     * [!!!] This method is not utilized in this class. The Trident API makes no use of
     *       city or state values.
     *
     * @param   string
     * @return  void
     */
    public function setShipProvince($province)
    {
        return;
    }

    /**
     * Set the ship-to zip/postal code.
     * @param   string|int
     * @return  void
     */
    public function setShipPostalCode($code)
    {
        $this->params['ship_to_zip'] = trim($code);
    }

    /**
     * Set the ship-to country. Should be a 2-letter ISO 3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setShipCountry($country)
    {
        if ($this->getCountryCode($country)) {
            $this->params['dest_country_code'] = $this->getCountryCode($country);
        } else {
            $this->params['dest_country_code'] = $this->getCountryCode(self::DEFAULT_COUNTRY);
        }
    }

    /**
     * Set the customer's phone number.
     * @param   string
     * @return  void
     */
    public function setPhone($phone)
    {
        // Sanitize phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Valid phone number
        if ($phone) {
            $this->params['cardholder_phone'] = $phone;
        }
    }

    /**
     * Set the customer's email address.
     * @param   string  Any valid email address.
     * @return  void
     */
    public function setEmail($email)
    {
        if (trim($email)) {
            $this->params['cardholder_email'] = trim($email);
        }
    }

    /**
     * Set the card number.
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->params['card_number'] = preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Set the card's expiration date.
     * @param   string|int  Expiration month. Must be two digits (01-12).
     * @param   string|int  Expiration year. Must be four digits (2014).
     * @return  void
     */
    public function setExpirationDate($month, $year)
    {
        // Clean month
        if (1 === strlen($month)) {
            $month = '0' . $month;
        }

        // Clean year (get last 2 digits of a YYYY format)
        $year = substr($year , (strlen($year) - 2), 2);

        // Set expiration date in MMYY format
        $this->params['card_exp_date'] = $month . $year;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['cvv2'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        if ($this->getCurrencyCode($currency)) {
            $this->params['currency_code'] = $this->getCurrencyCode($currency);
        } else {
            $this->params['currency_code'] = $this->getCurrencyCode(self::DEFAULT_CURRENCY);
        }
    }

    /**
     * Set the order/invoice number for the transaction.
     * @param   string
     * @return  void
     */
    public function setOrderNumber($orderNumber)
    {
        $this->params['invoice_number'] = trim($orderNumber);
    }

    /**
     * Set a custom paramater to the API.
     * @param   string  The parameter name.
     * @param   string  The parameter value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    /**
     * Do a pre-authorization transaction.
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        // Only include IP address if it's IPv4 (IPv6 is not compatible with  API)
        if (strlen(Vine_Request::getIp()) <= 15) {
            $this->params['ip_address'] = Vine_Request::getIp();
        }

        // Auto-set default currency
        if ( ! isset($this->params['currency_code'])) {
            $this->params['currency_code'] = $this->getCurrencyCode(self::DEFAULT_CURRENCY);
        }

        // Attempt pre-auth
        $this->params['transaction_type']   = self::CODE_PREAUTH;
        $this->params['transaction_amount'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do a capture on a prior authorization.
     *
     * [!!!] The transaction ID must already be set.
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Only include IP address if it's IPv4 (IPv6 is not compatible with  API)
        if (strlen(Vine_Request::getIp()) <= 15) {
            $this->params['ip_address'] = Vine_Request::getIp();
        }

        // Auto-set default currency
        if ( ! isset($this->params['currency_code'])) {
            $this->params['currency_code'] = $this->getCurrencyCode(self::DEFAULT_CURRENCY);
        }

        // Attempt capture
        $this->params['transaction_type']   = self::CODE_POSTAUTH;
        $this->params['transaction_id']     = $this->transactionId;
        $this->params['transaction_amount'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        // Only include IP address if it's IPv4 (IPv6 is not compatible with  API)
        if (strlen(Vine_Request::getIp()) <= 15) {
            $this->params['ip_address'] = Vine_Request::getIp();
        }

        // Auto-set default currency
        if ( ! isset($this->params['currency_code'])) {
            $this->params['currency_code'] = $this->getCurrencyCode(self::DEFAULT_CURRENCY);
        }

        // Attempt sale
        $this->params['transaction_type']   = self::CODE_SALE;
        $this->params['transaction_amount'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do a credit to a previous transaction.
     *
     * [!!!] The transaction ID must already be set.
     *
     * @param   string|float  The credit amount. Not all API's support amount.
     * @return  bool          TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Only include IP address if it's IPv4 (IPv6 is not compatible with  API)
        if (strlen(Vine_Request::getIp()) <= 15) {
            $this->params['ip_address'] = Vine_Request::getIp();
        }

        // Auto-set default currency
        if ( ! isset($this->params['currency_code'])) {
            $this->params['currency_code'] = $this->getCurrencyCode(self::DEFAULT_CURRENCY);
        }

        // Attempt credit
        $this->params['transaction_type']   = self::CODE_REFUND;
        $this->params['transaction_id']     = $this->transactionId;
        $this->params['transaction_amount'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Process the API operation as applicable.
     * @return  bool    TRUE if transaction successful, FALSE otherwise.
     */
    protected function process()
    {
        // Data to use in the request
        $request = $this->params;

        // Initializing curl
        $handle = curl_init($this->testMode ? self::URL_TEST : self::URL_LIVE);
        curl_setopt($handle, CURLOPT_HEADER, FALSE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($request));

        // Execute curl
        $result = curl_exec($handle);

        // Request failed completely, stop here
        if (FALSE === $result) {
            $this->lastResponse = FALSE;
            $this->setError(self::DEFAULT_ERROR);
            return FALSE;
        }

        // Convert response to PHP array and save it
        if ( ! is_array($result)) {
            parse_str($result, $this->lastResponse);
        // Save response directly
        } else {
            $this->lastResponse = $result;
        }

        // (bool) parse response
        return $this->parseResponse($this->lastResponse);
    }

    /**
     * Parse a AeS/Trident API response.
     * @param   array
     * @return  bool   TRUE if response was positive, FALSE if response was negative.
     */
    protected function parseResponse(array $response)
    {
        // Invalid transaction response, stop here
        if ( ! isset($response['error_code'])) {
            $this->setError(self::DEFAULT_ERROR);
            return FALSE;
        }

        // (bool|string) The tell all for this transaction
        $status = $this->getErrorCode($response['error_code']);

        // Transaction failed
        if (TRUE !== $status) {
            $this->setError($status);
            return FALSE;
        }

        // Save the transaction ID
        if (isset($response['transaction_id'])) {
            $this->transactionId = $response['transaction_id'];
        }

        // Response successful
        return TRUE;
    }

    /**
     * Get the ISO-3166 numeric country code using an ISO-3166 alpha-2 country
     * abbreviation.
     *
     * @param   string       Two-letter ISO-3166 alpha2 country abbreviation.
     * @return  bool|string  FALSE if country not found or 3 letter numeric string.
     */
    protected function getCountryCode($country)
    {
        $country = trim($country);

        if (isset($this->countryCodes[$country])) {
            return $this->countryCodes[$country];
        } else {
            return FALSE;
        }
    }

    /**
     * Get the ISO-4217 numeric currency code using a ISO-4217 currency abbreviation.
     * @param   string       Three-letter ISO-4217 currency abbreviation.
     * @return  bool|string  FALSE if currency not found or 3 letter numeric string.
     */
    protected function getCurrencyCode($currency)
    {
        $currency = trim($currency);

        if (isset($this->currencyCodes[$currency])) {
            return $this->currencyCodes[$currency];
        } else {
            return FALSE;
        }
    }

    /**
     * Get error message or success value based upon an error code from the API.
     * @param   string       The error code.
     * @return  bool|string  TRUE if transaction successful, FALSE or string if failed.
     */
    protected function getErrorCode($code)
    {
        if (isset($this->errorCodes[$code])) {
            return $this->errorCodes[$code];
        } else {
            return self::DEFAULT_ERROR;
        }
    }
}