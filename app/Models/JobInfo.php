<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobInfo extends Model
{

    protected $fillable = [
        'project_id',
        'customer_company_id',
        'company_name',
        'company_fname',
        'company_lname',
        'company_address',
        'company_city',
        'company_state',
        'company_zip',
        'company_email',
        'company_phone',
        'company_office_phone',
        'job_name',
        'job_address',
        'job_city',
        'job_state',
        'job_zip',
        'customer_id',
        'contract_amount',
        'first_day_of_work',
        'is_subcontractor',
        'is_gc',
        'gc_company',
        'gc_address',
        'gc_city',
        'gc_state',
        'gc_zip',
        'gc_phone',
        'gc_fax',
        'gc_web',
        'gc_first_name',
        'gc_last_name',
        'gc_title',
        'gc_direct_phone',
        'gc_cell',
        'gc_email',
        'is_viewable',
        'status',
        'date_completed',
        'signature',
        'signature_date',
    ];

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

