<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class State extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    protected $table = 'states';

    protected $fillable = [
        'name',
        'short_code',
        'country_id',
        'code'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
