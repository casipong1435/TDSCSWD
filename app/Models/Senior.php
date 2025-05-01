<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Senior extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefeciary_id',
        'senior_id_number',
        'date_sic_issued',
        'sic_status',
        'socpen_benefeciary',
    ];

    public function benefeciary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
    
}
