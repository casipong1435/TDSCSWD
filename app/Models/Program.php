<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'purpose',
        'beneficiaries',
        //0 Pending, 1 in beneficiary selection, 2 to approve selected beneficiary, 3 approved list
        'status',
        'start_date',
        'end_date',
        'venue',
        'reason',
        'date_rejected',
    ];

    public function program_beneficiary() : HasMany
    {
        return $this->hasMany(ProgramBeneficiary::class);
    }
}
