<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AicsStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'position',
        //0 Active, Archived
        'status'
    ];
}
