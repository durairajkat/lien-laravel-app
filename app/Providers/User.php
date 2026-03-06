<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

/**
 * Class User for user table
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_name', 'role', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Encrypt password
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Relation with Role
     * @return $this
     */
    public function role_type()
    {
        return $this->belongsTo('App\Models\Role', 'role')->select('type');
    }

    /**
     * Relation with User Details
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function details()
    {
        return $this->belongsTo('App\Models\UserDetails', 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lienProvider()
    {
        return $this->hasMany('App\Models\MemberLienMap', 'user_id', 'id');
    }

    /**
     * Returns the list of projects belonging to a user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {

        return $this->hasMany('App\Models\ProjectDetail', 'user_id', 'id');
    }

    /**
     * Check User is Admin or not
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->role_type()->get()[0]->type == 'Super admin') {
            return true;
        }
        return false;
    }

    /**
     * Check User is Admin or not
     * @return bool
     */
    public function isLien()
    {
        if ($this->role_type()->get()[0]->type == 'Lien-Providers') {
            return true;
        }
        return false;
    }


    /**
     * Check User is Member or not
     * @return bool
     */
    public function isMember()
    {
        if ($this->role_type()->get()[0]->type == 'Member' || $this->role_type()->get()[0]->type == 'Sub-Member') {
            if ($this->status == 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Check User is Member or not
     * @return bool
     */
    public function checkMember()
    {
        if (!is_null($this->role_type()->first()) && ($this->role_type()->first()->type == 'Member' || $this->role_type()->first()->type == 'Sub-Member')) {
            if ($this->status == 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Check User is Lien provider or not
     * @return bool
     */
    public function checkLienProvider()
    {
        if (!is_null($this->role_type()->first()) && $this->role_type()->first()->type == 'Lien-Providers') {
            if ($this->status == 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * returns the lien provider details of a lien provider user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lienUser()
    {
        return $this->hasOne('App\Models\LienProvider');
    }
    /**
     * Provides the contacts for a user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany('App\Models\Contact');
    }

    /**
     * Provides the contacts for a user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCompanyContacts()
    {
        return $this->hasMany('App\Models\CompanyContact');
    }


    /**
     * Provides the customers associated with a user.
     * @return $this
     */
    public function checkCustomer()
    {
        return $this->getCompanyContacts()->where('type', '=', '0');
    }

    /**
     * Gets the child users.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childUsers()
    {
        return $this->hasMany('App\User', 'parent_id');
    }

    /**
     * Returns all the subusers of a member user.
     * @return $this
     */
    public function subUsers()
    {
        return $this->childUsers()->where('role', '=', '6');
    }

    /**
     * Returns all the companies associated with a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasOne('App\Models\Company');
    }

    /**
     * Returns all the company contacts associated with a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function company_contacts()
    {
        return $this->hasMany('App\Models\CompanyContact');
    }

    /**
     * Returns all the parent associated with a child user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentUser()
    {
        return $this->belongsTo('App\User', 'parent_id');
    }

    public function mapcompanyContacts()
    {
        return $this->hasOne('App\Models\MapCompanyContact', 'user_id');
    }

    /**
     * Returns the payment details of the user.
     * @return mixed
     */
    public function getPaymentDetails()
    {
        return $this->hasOne('App\Models\MemberPackage', 'user_id');
    }

    /**
     * Returns the billing details of the user.
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->hasOne('App\Models\MemberBillingAddress', 'user_id');
    }

    public static function getAllChild($user_id) {
       return DB::select(
           DB::raw("
                SELECT  id, name
                FROM    (SELECT id, parent_id, name FROM users
                         ORDER BY parent_id, id) users_sorted,
                        (SELECT @pv := '$user_id') initialisation
                WHERE   find_in_set(parent_id, @pv)
                AND     length(@pv := concat(@pv, ',', id))"
           )
       );
    }

    public static function getAllParents($user_id) {
        return DB::select(
            DB::raw("
                SELECT T2.id, T2.name
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent_id FROM users WHERE id = _id) AS parent_id,
                        @l := @l + 1 AS lvl
                    FROM
                    (SELECT @r := $user_id, @l := 0) vars,
                        users m
                    WHERE @r <> 0) T1
                JOIN users T2 ON T1._id = T2.id
                ORDER BY T1.lvl DESC"
            )
        );
    }
}
