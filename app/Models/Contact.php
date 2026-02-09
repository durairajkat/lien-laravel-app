<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contact for contacts table
 * @package App\Models
 */
class Contact extends Model
{
    /**
     * Define table Name
     * @var string
     */
    protected $table = 'contacts';

    /**
     * Fetch all the phone number for a particular contact.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPhones()
    {
        return $this->hasMany('App\Models\ContactPhone','contactId','id');
    }
    /**
     * Relation with State
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'state_id');
    }

    /**
     * relation with contact information
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactInformation()
    {
        return $this->hasMany('App\Models\ContactInformation','contact_id','id');
    }
}
