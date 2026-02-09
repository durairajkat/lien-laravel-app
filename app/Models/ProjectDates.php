<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectDates for project dates table
 * @package App\Models
 */
class ProjectDates extends Model
{
    /**
     * Shows the related remedy date for a project
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remedyDate() {
        return $this->belongsTo('App\Models\RemedyDate','date_id','id');
    }
}
