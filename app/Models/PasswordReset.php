<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset for reset password table
 * @package App\Models
 */
class PasswordReset extends Model
{
    /**
     * Define Table for this Model
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * Turn off Timestamp
     * @var bool
     */
    public $timestamps = false;
}
