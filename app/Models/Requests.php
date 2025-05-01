<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requests extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        //0 = pending, 1 = checked barangay, 2 = verified staff, 3 = approved admin, 4 = rejectd
        'status',
        'benefeciary_id',
        'date_checked',
        'date_verified',
        'date_approved',
        'date_rejected',
        'barangay_id',
        'rejected_by',
        'reason'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barangay() : HasOne
    {
        return $this->hasOne(Barangay::class);
    }

    public function benefeciary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
}
