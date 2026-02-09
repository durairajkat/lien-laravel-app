<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimData extends Model
{
    /**
     * define table
     * @var string
     */
    protected $table = 'claim_data';
    /**
     * Use timestamp
     * @var bool
     */
    public $timestamps = true;

    /**
     * relation with user table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Relation with state
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }
}
