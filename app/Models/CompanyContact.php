<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    /**
     * Returns the user of the contact
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Returns the mapping between contacts and companies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mapContactCompany()
    {
        return $this->hasOne('App\Models\MapCompanyContact', 'company_contact_id');
    }

    /**
     * Returns the companies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    /*  public function getCompany() {
        return $this->mapContactCompany->company();
    } */
}
