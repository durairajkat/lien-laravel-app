<?php

interface Transaction_Interface_Transaction
{
    public function getError();
    public function setError($error);
    public function isValid();
    public function getTransactionId();
    public function setTransactionId($id);
    public function setFirstName($firstName);
    public function setLastName($lastName);
    public function setAddress1($address1);
    public function setAddress2($address2);
    public function setCity($city);
    public function setProvince($province);
    public function setCountry($country);
    public function setPostalCode($code);
    public function setCardNumber($number);
    public function setExpirationDate($month, $year);
    public function setSecurityCode($cvv);
    public function setCurrency($currency);
    public function setParam($param, $value);
    public function doPreAuth($amount);
    public function doPostAuth($amount);
    public function doSale($amount);
    public function doCredit($amount);
}