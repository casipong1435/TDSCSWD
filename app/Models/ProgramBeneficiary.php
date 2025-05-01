<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'benefeciary_id',
        //0 Pending, 1 Received
        'status'
    ];

    public function beneficiary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class, 'benefeciary_id');
    }

    public function program() : BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

}
