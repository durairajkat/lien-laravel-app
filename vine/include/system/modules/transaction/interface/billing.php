<?php

interface Transaction_Interface_Billing
{
    public function getBillingId();
    public function setBillingId($billingId);
    public function doBillingCreate();
    public function doBillingDelete();
    public function doBillingUpdate();
    public function doBillingPreAuth($amount);
    public function doBillingPostAuth($amount);
    public function doBillingSale($amount);
    public function doBillingCredit($amount);
}