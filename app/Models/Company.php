<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * Returns the user of the company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns the state of a company.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }

    /**
     * Returns the mapping between contacts and companies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mapContactCompany()
    {
        return $this->hasMany('App\Models\MapCompanyContact', 'company_id', 'id');
    }
    /** Relation with Map table
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getMapCompanyCustomer()
    {
        return $this->hasMany('App\Models\MapCompanyContact', 'company_id', 'id');
    }
}
