<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'stripe_plan',
        'cost',
        'trial_period',
        'description'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
