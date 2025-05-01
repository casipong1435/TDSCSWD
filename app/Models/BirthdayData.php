<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BirthdayData extends Model
{
    use HasFactory;

    protected $fillable  = [
        'benefeciary_id',
        'barangay_id',
        'month',
        'year',
        //0 pending, 1 = approved, 2 = rejected
        'status',
        'date_approved',
        'user_id'
    ];

    public function benefeciary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
    
    public function barangay() : HasOne
    {
        return $this->hasOne(Barangay::class);
    }
}
