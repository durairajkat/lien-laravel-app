<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIndustryContactMap extends Model
{
    /**
     * Relation with Contact
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contacts()
    {
        return $this->hasOne('App\Models\Contact','id','contactId');
    }

    /**
     * Relation with project
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project()
    {
        return $this->hasOne('App\Models\ProjectDetail','id','projectId');
    }

    /**
     * Relation with Company Contact
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fetchMap() {
        return $this->belongsTo('App\Models\MapCompanyContact','id');
    }

}
