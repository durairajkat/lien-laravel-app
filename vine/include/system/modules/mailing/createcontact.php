<?php

/**
 * Create a contact for any of the supported API's.
 * @author  Tell Konkle
 * @date    2012-05-28
 * @see     Mailing_AWeber::createContact()
 * @see     Mailing_BenchmarkEmail::createContact()
 * @see     Mailing_ConstantContact::createContact()
 * @see     Mailing_iContact::createContact()
 * @see     Mailing_MailChimp::createContact()
 */
class Mailing_CreateContact
{
    /**
     * All of the set contact data.
     * @var  array
     * @see  set*()
     * @see  getData()
     */
    protected $data = array
    (
        'email'      => NULL,
        'first_name' => NULL,
        'last_name'  => NULL,
        'company'    => NULL,
        'address1'   => NULL,
        'address2'   => NULL,
        'city'       => NULL,
        'province'   => NULL,
        'country'    => 'US',
        'postal'     => NULL,
        'phone'      => NULL,
        'fax'        => NULL,
        'website'    => NULL,
    );

    /**
     * Set contact's email address.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        $this->data['email'] = (string) $email;
    }

    /**
     * Set contact's first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->data['first_name'] = (string) $firstName;
    }

    /**
     * Set contact's last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->data['last_name'] = (string) $lastName;
    }

    /**
     * Set contact's company name.
     * @param   string
     * @return  void
     */
    public function setCompany($company)
    {
        $this->data['company'] = (string) $company;
    }

    /**
     * Set contact's address.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->data['address1'] = (string) $address1;
    }

    /**
     * Set the second line of contact's address, if applicable.
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        if (strlen($address2)) {
            $this->data['address2'] = (string) $address2;
        }
    }

    /**
     * Set the contact's city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->data['city'] = (string) $city;
    }

    /**
     * Set the contact's state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->data['province'] = (string) $province;
    }

    /**
     * Set the contact's country. Should be a 2 character ISO-3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->data['country'] = substr((string) $country, 0, 2);
    }

    /**
     * Set the contact's zip/postal code.
     * @param   string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        $this->data['postal'] = (string) $postal;
    }

    /**
     * Set the contact's phone number.
     * @param   string
     * @return  void
     */
    public function setPhone($phone)
    {
        $this->data['phone'] = preg_replace('/[^0-9\.x\s-()]/', '', (string) $phone);
    }

    /**
     * Set the contact's fax number.
     * @param   string
     * @return  void
     */
    public function setFax($fax)
    {
        $this->data['fax'] = preg_replace('/[^0-9\.x\s-()]/', '', (string) $fax);
    }

    /**
     * Set the contact's website. Must be a valid URL. Invalid URL's will be ignored.
     * @param   string
     * @return  void
     */
    public function setWebSite($website)
    {
        if (Vine_Verify::url($website)) {
            $this->data['website'] = $website;
        }
    }

    /**
     * Get all of the data that has been set, as an array.
     * @return  array
     */
    public function getData()
    {
        return $this->data;
    }
}