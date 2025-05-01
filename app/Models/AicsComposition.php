<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AicsComposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'aics_gis_id',
        'comp_name',
        'comp_age',
        'comp_relation_to_client',
        'comp_education',
        'comp_occupation',
        'comp_monthly_income',
    ];
}
