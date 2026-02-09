<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobInfo extends Model
{
    /**
     * relation with job contact
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobContact()
    {
        return $this->hasMany('App\Models\JobContract','job_info_id','id');
    }

    /**
     * relation with state
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCompanyState()
    {
        return $this->belongsTo('App\Models\State', 'company_state');
    }

    /**
     * relation with state
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getJobState()
    {
        return $this->belongsTo('App\Models\State', 'job_state');
    }

    /**
     * Relation with Customer_contracts table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customerContract()
    {
        return $this->belongsTo('App\Models\Contact', 'customer_id');
    }
    /**
     * Relation with industry_contacts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function industryContacts()
    {
        return $this->hasMany('App\Models\JobContract','job_info_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobFiles()
    {
        return $this->hasMany('App\Models\JobInfoFiles','job_info_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getProject()
    {
        return $this->belongsTo('App\Models\ProjectDetail','project_id');
    }
}
