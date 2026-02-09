<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProjectDates for project dates table
 * @package App\Models
 */
class ProjectDeadline extends Model
{
    /**
     * Define table
     * @var string
     */
    protected $table = 'project_deadline';

    /**
     * relation with remedy step
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remedy_step()
    {
        return $this->belongsTo('App\Models\RemedyStep', 'remedy_step_id', 'id');
    }
}
