<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnconditionalWaiverProgress extends Model
{
    /**
     * Define table
     * @var string
     */
    protected $table = 'unconditional_waiver_progresses';
    /**
     * use timestamp
     * @var bool
     */
    public $timestamps = true;
}
