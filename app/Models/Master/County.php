<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class County extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'state_id',
        'name',
        'county_fips_code',
        'state_fips_code'
    ];
}
