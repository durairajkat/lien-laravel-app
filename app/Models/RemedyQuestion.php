<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RemedyQuestion for remedy question table
 * @package App\Models
 */
class RemedyQuestion extends Model
{
    /**
     * Relation with state table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    /**
     * Relation with Project type table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('App\Models\ProjectType','project_type_id');
    }

    /**
     * Relation with Tiers table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tier()
    {
        return $this->belongsTo('App\Models\TierTable','tier_id');
    }
}
