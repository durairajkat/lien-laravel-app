<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienProviderStates extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'lien_provider_states';
    /**
     * use timestamp
     * @var bool
     */
    public $timestamps = true;

    public function state()
    {
        return $this->hasMany('App\Models\State', 'id', 'state_id');
    }
}
