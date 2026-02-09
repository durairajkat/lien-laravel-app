<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



/**

 * Class Role for user roles table

 * @package App\Models

 */

class Role extends Model
{
    use SoftDeletes;

    /**
     * Define table name
     * @var string
     */
    protected $table = 'roles';

    /**
     * Casts
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Creator (user who created the role)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(\App\User::class, 'created_by');
    }

     public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

}

