<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'address',
        'role',
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
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is employee
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * Get checklists created by this user
     */
    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'created_by');
    }

    /**
     * Get assignments for this user
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get submissions by this user
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get QR scans by this user
     */
    public function qrScans()
    {
        return $this->hasMany(QrScan::class);
    }
}
