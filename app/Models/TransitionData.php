<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransitionData extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefeciary_id',
        //1 = senior, 2 = pwd, 3 = women
        'from_benefeciary_type',
        //0 pending, 1 forwarded, 2 approved, 3 rejected
        'status',
        'date_approved',
        'barangay_id',
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
