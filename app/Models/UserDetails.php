<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserDetails extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['user_id', 'company', 'company_id', 'first_name', 'last_name', 'phone', 'address', 'city', 'state_id', 'country', 'zip', 'image', 'office_phone', 'website', 'lien_status', 'created_by'];
    /**
     * Relation with Users table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Relation with states table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }

    /**
     * Relation with company table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCompany()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}

