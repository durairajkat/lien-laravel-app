<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TierRemedyStep for tier_remedy_steps table
 * @package App\Models
 */
class TierRemedyStep extends Model
{
    /**
     * Relation with tier_tables
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tier()
    {
        return $this->belongsTo('App\Models\TierTable','tier_id');
    }

    /**
     * Relation with Remedy_steps Table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remedy_step()
    {
        return $this->belongsTo('App\Models\RemedyStep','remedy_step_id');
    }
}
