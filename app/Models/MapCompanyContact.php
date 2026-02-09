<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapCompanyContact extends Model
{
    /**
     * Returns the company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\MapCompanyContact', 'id', 'id');
    }

    public function getProject()
    {
        return $this->belongsTo('App\Models\ProjectDetail', 'id', 'customer_contract_id');
    }

    /**
     * Returns the contact
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contacts()
    {
        return $this->belongsTo('App\Models\CompanyContact', 'company_contact_id', 'id');
    }
    public function contactsAd()
    {
        return $this->belongsTo('App\Models\MapCompanyContact', 'id', 'id');
    }
    /** Get contact
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function getContacts()
    {
        return $this->belongsTo('App\Models\CompanyContact', 'company_contact_id', 'id');
    }
}
