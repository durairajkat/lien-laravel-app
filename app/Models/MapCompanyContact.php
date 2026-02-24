<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapCompanyContact extends Model
{
    protected $fillable = [
        'company_id',
        'company_contact_id',
        'user_id',
        'is_user',
        'address',
        'city',
        'state_id',
        'zip',
        'phone',
        'fax',
        'email',
        'website',
    ];

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

    public function contacts()
    {
        return $this->belongsTo('App\Models\CompanyContact', 'company_contact_id', 'id');
    }

    public function contactsAd()
    {
        return $this->belongsTo('App\Models\MapCompanyContact', 'id', 'id');
    }

    public function getContacts()
    {
        return $this->belongsTo('App\Models\CompanyContact', 'company_contact_id', 'id');
    }

}

