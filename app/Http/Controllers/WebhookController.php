<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Plan;
use App\Models\Payment;

use Illuminate\Http\Request;
// use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController
{

    public function handleCustomerUpdated($payload)
    {
        $payment = new Payment();
        $payment->user_id = $payload->metadata->id;
        $payment->stripe_customer_id = $payload->id;
        $payment->save();
    }

    public function handleChargeSucceeded($payload)
    {
        $payment = Payment::where('stripe_customer_id', $payload->customer)->first();
        $payment->transaction_id = $payload->balance_transaction;
        $payment->save();
    }

    public function handleInvoicePaymentSucceeded($payload)
    {
        $payment = Payment::where('stripe_customer_id', $payload->customer)->first();
        $payment->payment_date = date('m-d-Y', strtotime($payload->date));
        $payment->amount = $payload->amount_paid;
        $payment->status = $payload->paid ? '1' : '0';
        $payment->save();
    }


    public function handleUpcomingInvoice(Request $payload)
    {
        $subscription = $payload->subscription;
        $customer = $payload->customer;

        $user = User::where('stripe_id', '=', $customer)->firstOrFail();
        $memberSubscription = $user->subscriptions()->whereNull('ends_at')->first();

        if (isset($memberSubscription) && $memberSubscription->name == 'trial') {
            $plan = Plan::where('name', '=', $user->actual_plan)->first();
            $user->subscription($memberSubscription->name)->cancelNow();
            if ($plan) {
                $user->newSubscription($plan->slug, $plan->stripe_plan)
                    ->create();
            }
        }
    }
}
