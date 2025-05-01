<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'barangay_id',
        'username',
        'email',
        'password',
        // 0 = focal, 1 = barangay, 2 = staff, 3 = admin, 4 beneficiary
        'role',
        //0 = none, 1 = senior, 2 = pwd, 3 = women
        'focal_role',
        'user_image'
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function role(): Attribute
    {
        return new Attribute (
            get: fn($value) => ['focal', 'barangay', 'staff', 'admin', 'beneficiary'][$value],
        );
    }


    public function barangay() : BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function benefeciary() : HasMany
    {
        return $this->hasMany(Benefeciary::class, 'barangay_id');
    }

}
