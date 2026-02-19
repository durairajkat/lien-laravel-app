<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ProjectDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'project_name',
        'status',
        'user_id',
        'state_id',
        'project_type_id',
        'customer_id',
        'role_id',
        'answer1',
        'answer2',
        'customer_contract_id',
        'industry_contract_id',
        'address',
        'city',
        'zip',
        'county',
        'country',
        'start_date',
        'esitmated_end_date',
        'company_work',
        'api',
        'description',
        'county_id'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id')->select('id', 'name', 'country_id', 'code', 'short_code');
    }


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    public function project_type()
    {

        return $this->belongsTo('App\Models\ProjectType', 'project_type_id');
    }


    public function project_contract()
    {
        return $this->belongsTo('App\Models\ProjectContract', 'id', 'project_id')
        ->select('id', 'project_id', 'base_amount', 'extra_amount', 'credits', 'waiver', 'total_claim_amount', 'job_no', 'general_description');
    }


    public function customer_contract()
    {

        return $this->belongsTo('App\Models\MapCompanyContact', 'customer_contract_id');
    }

    public function role()
    {

        return $this->belongsTo('App\Models\ProjectRole', 'role_id');
    }

    /**
     * Relation with industry_contracts table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function industry_contract()
    {

        return $this->belongsTo('App\Models\Contact', 'industry_contract_id');
    }



    /**
     * Relation with tier_table table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function tier()
    {

        return $this->belongsTo('App\Models\TierTable', 'customer_id');
    }



    /**
     * Relation with project_dates table
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function project_date()
    {

        return $this->hasMany('App\Models\ProjectDates', 'project_id')->select('id', 'project_id', 'date_id', 'date_value');
    }

    /**
     * Relation with industry_contacts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function industryContacts()
    {

        return $this->hasMany('App\Models\ProjectIndustryContactMap', 'projectId', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function originalCustomer()
    {

        return $this->belongsTo('App\Models\CustomerCode', 'customer_id')->select('id', 'name', 'description');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function jobInfo()
    {

        return $this->belongsTo('App\Models\JobInfo', 'id', 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\ProjectTask', 'project_id', 'id')->select('id', 'project_id','task_action_id','task_name', 'complete_date','comment', 'email_alert','assigned_to','assigned_by', 'assigned_at', 'status');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\ProjectDocument', 'project_id', 'id')->select('id', 'project_id', 'title', 'notes', 'date', 'filename');
    }

}
