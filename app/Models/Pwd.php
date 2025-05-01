<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pwd extends Model
{
    use HasFactory;

    protected $fillable = [
        'benefeciary_id',
        'pwd_id_number',
        'date_pic_issued',
        'pic_status',
    ];

    public function benefeciary() : BelongsTo
    {
        return $this->belongsTo(Benefeciary::class);
    }
}
