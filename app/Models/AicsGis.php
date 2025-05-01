<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AicsGis extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birthdate',
        'age',
        'sex',
        'address',
        'civil_status',
        'occupation',
        'education',
        'monthly_income',
        'mobile_number',
        'relation_to_beneficiary',
        'ik_name',
        'ik_birthdate',
        'ik_age',
        'ik_sex',
        'ik_address',
        'ik_civil_status',
        'ik_occupation',
        'ik_education',
        'ik_monthly_income',
        'ik_mobile_number',
        'type_of_assistance',
        'amount_provided',
        'social_worker_assessment',
        'interviewee',
        'reviewee',
        'status',
        'date',
    ];

    public function aics_compositions() : HasMany
    {
        return $this->hasMany(AicsComposition::class);
    }

    public function assistance_type() : BelongsTo
    {
        return $this->belongsTo(AssistanceType::class, 'type_of_assistance');
    }

    public function interviewee_staff() : BelongsTo
    {
        return $this->belongsTo(AicsStaff::class, 'interviewee');
    }

    public function reviewee_staff() : BelongsTo
    {
        return $this->belongsTo(AicsStaff::class, 'reviewee');
    }
}
