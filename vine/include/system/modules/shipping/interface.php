<?php

interface Shipping_Interface
{
    public function setTestMode($test);
    public function setFrom(Shipping_Address $address);
    public function setTo(Shipping_Address $address);
    public function setBoxType($type);
    public function setPickup($pickup);
    public function setService($service);
    public function setPackages(Shipping_Packages $packages);
}