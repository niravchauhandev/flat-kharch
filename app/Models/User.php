<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'is_active',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'role' => 'integer',
            'is_active' => 'integer',
            'status' => 'integer',
        ];
    }

    /**
     * Check if user is an Admin (1: Admin)
     */
    public function isAdmin(): bool
    {
        return (int) $this->role === 1;
    }

    /**
     * Check if user is a regular Flat Member (2: Member)
     */
    public function isMember(): bool
    {
        return (int) $this->role === 2;
    }

    /**
     * Check if user is Active (1: Active, 2: Inactive)
     */
    public function isActive(): bool
    {
        return (int) $this->is_active === 1 && (int) $this->status === 1;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->where('status', 1);
    }

    /**
     * Get formatted Avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        return get_user_avatar($this->avatar, $this->name);
    }
}
