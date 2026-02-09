<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LienLawSlideChart extends Model
{
    /**
     * Relation with State
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }
    /**
     * Relation with Project_types table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projectType()
    {
        return $this->belongsTo('App\Models\ProjectType', 'project_type');
    }
}
