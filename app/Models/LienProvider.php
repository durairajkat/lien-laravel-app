<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class LienProvider extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'company_id',
        'user_id',
        'company',
        'companyPhone',
        'firstName',
        'lastName',
        'address',
        'city',
        'stateId',
        'zip',
        'phone',
        'fax',
        'email',
        'logo',
        'role_name',
        'is_deletable'
    ];
    /**
     * Relation with states.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'stateId');
    }

    /**
     * Relation with states.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getCompany()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    /**
     * Relation with users.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getUser()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function states()
    {
        return $this->hasMany('App\Models\LienProviderStates', 'lien_id', 'id');
    }
}
