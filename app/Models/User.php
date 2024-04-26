<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "device_token",
        "email",
        "avatar",
        "birthdate",
        "verified_at",
        "verified_by",
        "blocked_at",
        "blocked_by",
        "block_message",
        "role",
        "password",
    ];

    protected $append = ['is_verified', 'is_blocked'];

    public function getIsVerifiedAttribute()
    {
        return $this->attributes['verified_at'] != null;
    }

    public function getIsBlockedAttribute()
    {
        return $this->attributes['blocked_at'] != null;
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role == 'root' || $this->role == 'admin';
    }

    /**
     * Get all of the units for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Get the adminVerified that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminVerified(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the adminBlock that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminBlock(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }
}
