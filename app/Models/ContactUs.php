<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactUs for contact uses table
 * @package App\Models
 */
class ContactUs extends Model
{
    /**
     * Defile table name
     * @var string
     */
    protected $table = 'contact_uses';

    /**
     * Relation with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
