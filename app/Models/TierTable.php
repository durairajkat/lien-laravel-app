<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TierTable for tier_table database.
 * @package App\Models
 */
class TierTable extends Model
{
    /**
     * Define Table name
     * @var string
     */
    protected $table = 'tier_tables';

    /**
     * Relation with Role table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo]
     */
    public function role()
    {
        return $this->belongsTo('App\Models\ProjectRole','role_id');
    }

    /**
     * Relation with customer table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\CustomerCode','customer_id');
    }
}
