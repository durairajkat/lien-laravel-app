<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model JobRequestDeadline
 * @package App\Models
 */
class JobRequestDeadline extends Model
{
    /**
     * Relation with label table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deadlineLabel()
    {
        return $this->belongsTo('App\Models\JobRequestLabel','label_id');
    }
}
