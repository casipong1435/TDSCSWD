<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_name',
        'lat',
        'long'
    ];

    public function user() : HasMany
    {
        return $this->hasMany(User::class, 'barangay_id');
    }

    public function benefeciary() : HasMany
    {
        return $this->hasMany(Benefeciary::class, 'barangay_id');
    }
}
