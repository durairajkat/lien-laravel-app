<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberLienMap extends Model
{
    public function getLien()
    {
        return $this->hasMany('App\Models\LienProvider','id','lien_id');
    }


    /***
     * Finds the lien of the member.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function findLien()
    {
        return $this->belongsTo('App\Models\LienProvider','lien_id');
    }
}
