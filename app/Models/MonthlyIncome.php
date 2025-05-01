<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_range'
    ];

    public function benefeciary () : belongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
}
