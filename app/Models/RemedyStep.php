<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemedyStep extends Model
{

    public function getRemedy()
    {
        return $this->belongsTo('App\Models\Remedy', 'remedy_id');
    }


    public function remedy_date()
    {
        return $this->belongsTo('App\Models\RemedyDate', 'remedy_date_id');
    }
}
