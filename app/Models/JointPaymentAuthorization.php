<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JointPaymentAuthorization extends Model
{
    /**
     * table name
     * @var string
     */
    protected $table = 'joint_payment_authorizations';
    /**
     * use timestamp
     * @var bool
     */
    public $timestamps = true;
}
