<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RemedyDate for remedy_dates table
 * @package App\Models
 */
class RemedyDate extends Model
{
    /**
     * Relation with Remedy Table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRemedy()
    {
        return $this->belongsTo('App\Models\Remedy','remedy_id');
    }
}
