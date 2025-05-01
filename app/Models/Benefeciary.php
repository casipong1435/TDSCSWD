<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Benefeciary extends Model
{
    use HasFactory;

    protected $fillable = [
            'barangay_id',
            'user_id',
            'monthly_income_id',
            'first_name',
            'last_name',
            'middle_name',
            'extension_name',
            'date_of_birth',
            'age',
            'civil_status',
            'sex',
            'educational_attainment',
            'occupation',
            'work_status',
            'street',
            'city',
            'province',
            'region',
            'religion',
            'contact_number',
            'pantawid_benefeciary',
            'indigenous_person',
            'lgbtq',
            'remarks',
            'image',
            'status',
            //1 = senior, 2 = pwd, 3 = women
            'benefeciary_type',
            // 0 = not in list, 1 = approved, 2 archived, 3 on application, 4 rejected
            'approved_status',
            'reason',
            'date_rejected',
            'date_approved',
    ];

    public function barangay() : BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function senior() : HasOne
    {
        return $this->hasOne(Senior::class, 'benefeciary_id');
    }

    public function pwd() : HasOne
    {
        return $this->hasOne(Pwd::class, 'benefeciary_id');
    }

    public function women() : HasOne
    {
        return $this->hasOne(Women::class, 'benefeciary_id');
    }

    public function transition_data() : HasMany
    {
        return $this->hasMany(TransitionData::class, 'benefeciary_id');
    }

    public function birthday_data() : HasMany
    {
        return $this->hasMany(BirthdayData::class, 'benefeciary_id');
    }

    public function monthly_income() : BelongsTo
    {
        return $this->belongsTo(MonthlyIncome::class, 'monthly_income_id');
    }

    public function program_beneficiary() : HasMany
    {
        return $this->hasMany(ProgramBeneficiary::class);
    }
}
