<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Women extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefeciary_id',
        'kalipi_id_number',
        'date_kic_issued',
        'kic_status',
    ];

    public function Benefeciary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
}
