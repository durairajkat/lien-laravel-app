<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Remedy extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'remedies';

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }


    public function type()
    {
        return $this->belongsTo('App\Models\ProjectType', 'project_type_id');
    }

    public function remedyDates()
    {
        return $this->hasMany(RemedyDate::class);
    }
}
