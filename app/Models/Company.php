<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company',
        'website',
        'address',
        'city',
        'state_id',
        'zip',
        'phone',
        'fax',
        'created_by',
        'project_id',
        'is_selected'
    ];
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

    public function contacts()
    {
        return $this->belongsToMany(
            CompanyContact::class,
            'map_company_contacts',
            'company_id',
            'company_contact_id'
        );
    }
}
