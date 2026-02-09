<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Remedy for remedies table
 * @package App\Models
 */
class Remedy extends Model
{
    /**
     * Define table for this Model
     * @var string
     */
    protected $table = 'remedies';

    /**
     * Relation With state table
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
}
