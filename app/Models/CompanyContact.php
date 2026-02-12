<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    protected $fillable = [
        'user_id',
        'contact_type',
        'type',
        'role_id',
        'title',
        'title_other',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cell'
    ];

    public function user()
    {

        return $this->belongsTo('App\User');

    }


    public function mapContactCompany()
    {

        return $this->hasOne('App\Models\MapCompanyContact', 'company_contact_id');

    }


}

