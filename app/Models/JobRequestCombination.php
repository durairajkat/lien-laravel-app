<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class JobRequestCombination for Job Request combination table
 * Currently not in use.
 * @package App\Models
 */
class JobRequestCombination extends Model
{
    /**
     * Define table name for this Model
     * @var string
     */
    protected $table = 'job_request_combinations';

    /**
     * Relation with Deadline
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deadline()
    {
        return $this->hasMany('App\Models\JobRequestDeadline','combination_id');
    }

    /**
     * Relation with Combination Label
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CombinationLabel()
    {
        return $this->hasMany('App\Models\JobRequestLabel','combination_id');
    }

    /**
     * Relation with state
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    /**
     * Relation with Role table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\TierTable','role_id');
    }

    /**
     * Relation with Type Table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('App\Models\ProjectType','type_id');
    }

    /**
     * Relation with Property Type table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property_type()
    {
        return $this->belongsTo('App\Models\PropertyType','property_type_id');
    }
}
