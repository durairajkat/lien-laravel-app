<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditApplication extends Model
{
    /**
     * define table
     * @var string
     */
    protected $table = 'credit_applications';
    /**
     * Use timestamp
     * @var bool
     */
    public $timestamps = true;
}
