<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContract extends Model
{

    protected $fillable = [
        'project_id',
        'base_amount',
        'extra_amount',
        'credits',
        'waiver',
        'total_claim_amount',
        'receivable_status',
        'calculation_status',
        'general_description',
        'job_no'
    ];
}
