<?php

/**
 * Address Composer
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Address
{
    /**
     * The default value to return when a requested field is empty.
     * ---
     * @var  mixed
     */
    protected $default = NULL;

    /**
     * The address fields.
     * ---
     * @var  string
     */
    protected $firstName  = NULL;
    protected $lastName   = NULL;
    protected $company    = NULL;
    protected $department = NULL;
    protected $address1   = NULL;
    protected $address2   = NULL;
    protected $city       = NULL;
    protected $province   = NULL;
    protected $postal     = NULL;
    protected $country    = NULL;
    protected $phone1     = NULL;
    protected $phone1Ext  = NULL;
    protected $phone2     = NULL;
    protected $phone2Ext  = NULL;
    protected $phone3     = NULL;
    protected $phone3Ext  = NULL;
    protected $fax        = NULL;
    protected $email      = NULL;

    /**
     * Class constructor.
     * ---
     * @param   mixed  (opt) The default value to return for empty fields.
     * @return  void
     */
    public function __construct($default = NULL)
    {
        $this->default = $default;
    }

    /**
     * Set the first name.
     * ---
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        if (trim($firstName)) {
            $this->firstName = trim($firstName);
        }
    }

    /**
     * Set the last name.
     * ---
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        if (trim($lastName)) {
            $this->lastName = trim($lastName);
        }
    }

    /**
     * Set the department name.
     * ---
     * @param   string
     * @return  void
     */
    public function setDepartment($department)
    {
        if (trim($department)) {
            $this->department = trim($department);
        }
    }

    /**
     * Set the company name.
     * ---
     * @param   string
     * @return  void
     */
    public function setCompany($company)
    {
        if (trim($company)) {
            $this->company = trim($company);
        }
    }

    /**
     * Set the address line 1.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        if (trim($address1)) {
            $this->address1 = trim($address1);
        }
    }

    /**
     * Set the address line 2.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        // Always optional
        if (trim($address2)) {
            $this->address2 = trim($address2);
        }
    }

    /**
     * Set the city.
     * ---
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        // Tiny countries may only have one city (i.e. Singapore)
        if (trim($city)) {
            $this->city = trim($city);
        }
    }

    /**
     * Set the state/province.
     * ---
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        // Not all countries require this field (i.e. most of Europe)
        if (trim($province)) {
            $this->province = trim($province);
        }
    }

    /**
     * Set the postal code.
     * ---
     * @param   string
     * @return  void
     */
    public function setPostal($postal)
    {
        // Not all countries require this field (i.e. Ireland)
        if (trim($postal)) {
            $this->postal = trim($postal);
        }
    }

    /**
     * Set the country.
     * ---
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        if (trim($country)) {
            $this->country = trim($country);
        }
    }

    /**
     * Set phone1 number.
     * ---
     * @param   string  Phone number.
     * @param   string  Phone extention.
     * @return  void
     */
    public function setPhone1($phone, $ext = '')
    {
        if (trim($phone)) {
            $this->phone1    = trim($phone);
            $this->phone1Ext = trim($ext) ? trim($ext) : '';
        }
    }

    /**
     * Set phone2 number.
     * ---
     * @param   string  Phone number.
     * @param   string  Phone extention.
     * @return  void
     */
    public function setPhone2($phone, $ext = '')
    {
        if (trim($phone)) {
            $this->phone2    = trim($phone);
            $this->phone2Ext = trim($ext) ? trim($ext) : '';
        }
    }

    /**
     * Set phone3 number.
     * ---
     * @param   string  Phone number.
     * @param   string  Phone extention.
     * @return  void
     */
    public function setPhone3($phone, $ext = '')
    {
        if (trim($phone)) {
            $this->phone3    = trim($phone);
            $this->phone3Ext = trim($ext) ? trim($ext) : '';
        }
    }

    /**
     * Set fax number.
     * ---
     * @param   string  Fax number.
     * @return  void
     */
    public function setFax($fax)
    {
        if (trim($fax)) {
            $this->fax = trim($fax);
        }
    }

    /**
     * Set email address.
     * ---
     * @param   string  Email address.
     * @return  void
     */
    public function setEmail($email)
    {
        if (Vine_Verify::email($email)) {
            $this->email = trim($email);
        }
    }

    /**
     * Get the first name.
     * ---
     * @return  string
     */
    public function getFirstName()
    {
        return $this->firstName ? $this->firstName : $this->default;
    }

    /**
     * Get the last name.
     * ---
     * @return  string
     */
    public function getLastName()
    {
        return $this->lastName ? $this->lastName : $this->default;
    }

    /**
     * Get the department name.
     * ---
     * @return  string
     */
    public function getDepartment()
    {
        return $this->department ? $this->department : $this->default;
    }

    /**
     * Get the company name.
     * ---
     * @return  string
     */
    public function getCompany()
    {
        return $this->company ? $this->company : $this->default;
    }

    /**
     * Get the address line 1.
     * ---
     * @return  string
     */
    public function getAddress1()
    {
        return $this->address1 ? $this->address1 : $this->default;
    }

    /**
     * Get the address line 2.
     * ---
     * @return  string
     */
    public function getAddress2()
    {
        return $this->address2 ? $this->address2 : $this->default;
    }

    /**
     * Get the city.
     * ---
     * @return  string
     */
    public function getCity()
    {
        return $this->city ? $this->city : $this->default;;
    }

    /**
     * Get the state/province.
     * ---
     * @return  string
     */
    public function getProvince()
    {
        return $this->province ? $this->province : $this->default;
    }

    /**
     * Get the postal code.
     * ---
     * @return  string
     */
    public function getPostal()
    {
        return $this->postal ? $this->postal : $this->default;
    }

    /**
     * Get the country.
     * ---
     * @return  string
     */
    public function getCountry()
    {
        return $this->country ? $this->country : $this->default;
    }

    /**
     * Get phone1 number.
     * ---
     * @param   bool  Include extension (formatted 555-555-5555x123)?
     * @return  string
     */
    public function getPhone1($incExt = FALSE)
    {
        if ($incExt && strlen($this->phone1Ext)) {
            return $this->phone1 . 'x' . $this->phone1Ext;
        } else {
            return $this->phone1 ? $this->phone1 : $this->default;
        }
    }

    /**
     * Get phone1's extension.
     * ---
     * @return  string
     */
    public function getPhone1Ext()
    {
        return $this->phone1Ext ? $this->phone1Ext : $this->default;
    }

    /**
     * Get phone2 number.
     * ---
     * @param   bool  Include extension (formatted 555-555-5555x123)?
     * @return  string
     */
    public function getPhone2($incExt = FALSE)
    {
        if ($incExt && strlen($this->phone2Ext)) {
            return $this->phone2 . 'x' . $this->phone2Ext;
        } else {
            return $this->phone2 ? $this->phone2 : $this->default;
        }
    }

    /**
     * Get phone2's extension.
     * ---
     * @return  string
     */
    public function getPhone2Ext()
    {
        return $this->phone2Ext ? $this->phone2Ext : $this->default;
    }

    /**
     * Get phone3 number.
     * ---
     * @param   bool  Include extension (formatted 555-555-5555x123)?
     * @return  string
     */
    public function getPhone3($incExt = FALSE)
    {
        if ($incExt && strlen($this->phone3Ext)) {
            return $this->phone3 . 'x' . $this->phone3Ext;
        } else {
            return $this->phone3 ? $this->phone3 : $this->default;
        }
    }

    /**
     * Get phone3's extension.
     * ---
     * @return  string
     */
    public function getPhone3Ext()
    {
        return $this->phone3Ext ? $this->phone3Ext : $this->default;
    }

    /**
     * Get fax number.
     * ---
     * @return  string
     */
    public function getFax()
    {
        return $this->fax ? $this->fax : $this->default;
    }

    /**
     * Get email address.
     * ---
     * @return  string
     */
    public function getEmail()
    {
        return $this->email ? $this->email : $this->default;
    }

    /**
     * Compile letter-ready address based on provided input.
     * ---
     * @param   string  New line string. Defaults to "<br />" - Use "/n" for non-HTML.
     * @param   bool    UPPERCASE all letters? Defaults to FALSE.
     * @param   bool    Keep address punctuation? Defaults to TRUE.
     * @param   bool    Escape all output (for HTML-ready results)? Defaults to TRUE.
     * @param   bool    Use abbreviated country before postal, or full name on it's own line?
     * @return  string
     */
    public function format(
        $newLine      = '<br />',
        $uppercase    = FALSE,
        $punctuation  = TRUE,
        $escape       = TRUE,
        $shortCountry = TRUE
    ) {
        // All data
        $name       = '';
        $search     = "\n";
        $replace    = $newLine;
        $countries  = require VINE_PATH . 'countries.php';
        $fullName   = trim($this->firstName . ' ' . $this->lastName);
        $company    = $this->company;
        $department = $this->department;
        $address1   = $this->address1;
        $address2   = $this->address2;
        $city       = $this->city;
        $province   = $this->province;
        $postal     = $this->postal;
        $country    = isset($countries[$this->country]) && ! $shortCountry
                    ? $countries[$this->country][0]
                    : $this->country;

        // Escape data for safe HTML output
        if ($escape) {
            $fullName   = Vine_Html::output($fullName);
            $company    = Vine_Html::output($company);
            $department = Vine_Html::output($department);
            $address1   = Vine_Html::output($address1);
            $address2   = Vine_Html::output($address2);
            $city       = Vine_Html::output($city);
            $province   = Vine_Html::output($province);
            $postal     = Vine_Html::output($postal);
            $country    = Vine_Html::output($country);
        }

        // Avoid duplicate companies or departments in address
        if (   0 === strcasecmp($this->simplify($department), $this->simplify($company))
            || 0 === strcasecmp($this->simplify($department), $this->simplify($fullName))
            || 0 === strcasecmp($this->simplify($department), $this->simplify($address1))) {
            $department = NULL;
        }

        // Avoid duplicate companies or departments in address
        if (0 === strcasecmp($this->simplify($fullName), $this->simplify($company))) {
            $company = NULL;
        }

        // Avoid duplicate companies or departments in address
        if (0 === strcasecmp($this->simplify($address1), $this->simplify($company))) {
            $company = NULL;
        }

        // Start compiling recipients for this address
        $name = '';

        // Prefix full name with "Attn:" only when there's also a department or company
        if ($fullName && ($department || $company)) {
            $name .= 'Attn: ' . $fullName . $search;
        // When there's no department or company, use full name by itself
        } elseif ($fullName) {
            $name .= $fullName . $search;
        }

        // Append department name
        if ($department) {
            $name .= $department . $search;
        }

        // Append company name
        if ($company) {
            $name .= $company . $search;
        }

        // Show full length country name
        if ( ! $shortCountry) {
            // Compile each address line
            $line1  = $address1 ? $address1 . $search : '';
            $line2  = $address2 ? $address2 . $search : '';
            $line3  = $city ? $city . ', ' : '';
            $line3 .= $province ? $province . ' ' : '';
            $line3 .= $postal ? $postal : '';
            $line3  = rtrim(rtrim($line3, ' '), ',');
            $line3  = strlen($line3) ? $line3 . $search : '';
            $line4  = $country;

            // Compile address
            $result = rtrim($line1 . $line2 . $line3 . $line4);
            $result = $name . $result;
        // Show abbreviated country name
        } else {
            // Compile each address line
            $line1  = $address1 ? $address1 . $search : '';
            $line2  = $address2 ? $address2 . $search : '';
            $line3  = $city ? $city . ', ' : '';
            $line3 .= $province ? $province . ' ' : '';
            $line3 .= $country ? $country . ' ' : '';
            $line3 .= $postal ? $postal : '';
            $line3  = rtrim(rtrim($line3, ' '), ',');
            $line3  = strlen($line3) ? $line3 : '';

            // Compile address
            $result = rtrim($line1 . $line2 . $line3);
            $result = $name . $result;
        }

        // UPPERCASE all letters
        if ($uppercase) {
            $result = strtoupper($result);
        }

        // Remove all punctuation
        if ( ! $punctuation) {
            $result = $this->simplify($result, FALSE);
        }

        // Format address with applicable separators
        $result = str_replace($search, $replace, $result);

        // Final address
        return $result;
    }

    /**
     * Remove punctuation characters from an address string.
     * ---
     * @param   string  Address string to remove punctation characters from.
     * @param   bool    [optional] Remove punctuation characters? Default = TRUE.
     * @return  string  Cleaned address string.
     */
    protected function simplify($input, $all = TRUE)
    {
        // Remove all characters outside of A-Z0-9
        if ($all) {
            return Vine_Quick::alphaNumeric($input);
        // Remove basic punctuation characters
        } else {
            return str_replace(['.', ',', ';', "'", '"', '?', '!', '`'], '', $input);
        }
    }
}
