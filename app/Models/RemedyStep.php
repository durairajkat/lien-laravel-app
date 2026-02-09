<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RemedyStep for Remedy_steps table
 * @package App\Models
 */
class RemedyStep extends Model
{
    /**
     * Relation with remedy table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRemedy()
    {
        return $this->belongsTo('App\Models\Remedy','remedy_id');
    }

    /**
     * Relation with Remedy_dates table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remedy_date()
    {
        return $this->belongsTo('App\Models\RemedyDate','remedy_date_id');
    }
}
