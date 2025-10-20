<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Favorite;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Quan hệ role, dùng withDefault() để tránh null
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id')->withDefault([
            'name' => 'customer',
            'display_name' => 'Khách'
        ]);
    }
    public function isAdmin(): bool
    {
        return optional($this->role)->name === 'admin';
    }

    public function isStaff(): bool
    {
        return optional($this->role)->name === 'staff';
    }

    public function isCustomer(): bool
    {
        return optional($this->role)->name === 'customer';
    }


    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

}
