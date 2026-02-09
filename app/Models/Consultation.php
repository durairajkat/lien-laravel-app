<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Consultation for consultations
 * @package App\Models
 */
class Consultation extends Model
{
    /**
     * Relation with user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     protected $table = 'consultations';
   
}
