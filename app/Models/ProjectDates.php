<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class ProjectDates extends Model
{

    protected $fillable = [
        'project_id',
        'date_id',
        'date_value',
    ];

    public function remedyDate()
    {

        return $this->belongsTo('App\Models\RemedyDate', 'date_id', 'id');
    }
}
