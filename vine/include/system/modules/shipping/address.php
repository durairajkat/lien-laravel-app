<?php

/**
 * Setup a Ship From or Ship To address.
 * ---
 * @author  Tell Konkle
 * @date    2019-03-07
 */
class Shipping_Address
{
    /**
     * Address lines.
     * ---
     * @var  string
     */
    protected $company  = NULL;
    protected $name     = NULL;
    protected $address1 = NULL;
    protected $address2 = NULL;
    protected $city     = NULL;
    protected $province = NULL;
    protected $postal   = NULL;
    protected $country  = NULL;

    /**
     * Address errors if any found.
     * ---
     * @var  array
     */
    protected $errors = NULL;

    /**
     * Set an address error.
     * ---
     * @param   string  Error message.
     * @return  void
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Get an address error.
     * ---
     * @return  array|bool  FALSE if no errors found, array otherwise.
     */
    public function getErrors()
    {
        return $this->errors ? $this->errors : FALSE;
    }

    /**
     * Is the address valid?
     * ---
     * @return  bool
     */
    public function isValid()
    {
        return $this->errors ? FALSE : TRUE;
    }

    /**
     * Set the company name for the address.
     * ---
     * @param  string
     * @return  void
     */
    public function setCompany($company)
    {
        // Standardize
        $company = trim($company);

        // Verify
        if ( ! Vine_Verify::length($company, 0, 128)) {
            $this->setError('Invalid company.');
        }

        // Save
        $this->company = $company;
    }

    /**
     * Set the name of the person or company for the address.
     * ---
     * @param   string
     * @return  void
     */
    public function setName($name)
    {
        // Standardize
        $name = trim($name);

        // Verify
        if ( ! Vine_Verify::length($name, 2, 128)) {
            $this->setError('Invalid name.');
        }

        // Save
        $this->name = $name;
    }

    /**
     * Set the address line 1.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        // Standardize
        $address1 = trim($address1);

        // Verify
        if ( ! Vine_Verify::length($address1, 2, 128)) {
            $this->setError('Invalid address line 1.');
        }

        // Save
        $this->address1 = $address1;
    }

    /**
     * Set the address line 2.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        // Standardize
        $address2 = trim($address2);

        // Verify
        if ( ! Vine_Verify::length($address2, 0, 128)) {
            $this->setError('Invalid address line 2.');
        }

        // To save or not to save
        $this->address2 = $address2 ? $address2 : NULL;
    }

    /**
     * Set the address city.
     * ---
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        // Standardize
        $city = trim($city);

        // Verify
        if ( ! Vine_Verify::length($city, 2, 128)) {
            $this->setError('Invalid city');
        }

        // Save
        $this->city = $city;
    }

    /**
     * Set the address state/province.
     * ---
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        // Standardize
        $province = trim($province);

        // Verify
        if ( ! Vine_Verify::length($province, 0, 128)) {
            $this->setError('Invalid province');
        }

        // To save or not to save
        $this->province = $province ? $province : NULL;
    }

    /**
     * Set the address zip code/postal code.
     * ---
     * @param   string
     * @return  void
     */
    public function setPostal($postal)
    {
        // Standardize
        $postal = trim($postal);

        // Verify
        if ( ! Vine_Verify::length($postal, 0, 32)) {
            $this->setError('Invalid postal code.');
        }

        // To save or not to save
        $this->postal = $postal ? $postal : NULL;
    }

    /**
     * Set the address country code.
     * ---
     * @param   string  Two-letter country code.
     * @return  void
     */
    public function setCountry($country)
    {
        // Standardize
        $country = strtoupper(trim($country));

        // Verify
        if ( ! Vine_Verify::country($country)) {
            $this->setError('Invalid country.');
        }

        // Save
        $this->country = $country;
    }

    /**
     * Get the address company.
     * ---
     * @return  string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Get the address name.
     * ---
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the address line 1.
     * ---
     * @return  string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Get the address line 2.
     * ---
     * @return  string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Get the address city.
     * ---
     * @return  string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get the address state / province.
     * ---
     * @return  string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Get the address zip code / postal code.
     * ---
     * @return  string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Get the addresss country code.
     * ---
     * @return  string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
