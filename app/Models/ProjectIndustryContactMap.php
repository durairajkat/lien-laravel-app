<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIndustryContactMap extends Model
{
    protected $fillable = [
        'projectId',
        'contactId',
        'company_contact_id'
    ];

    public function contacts()
    {

        return $this->hasOne('App\Models\Contact','id','contactId');

    }


    public function project()
    {

        return $this->hasOne('App\Models\ProjectDetail','id','projectId');

    }



    public function fetchMap() {

        return $this->belongsTo('App\Models\MapCompanyContact','id');

    }



}

