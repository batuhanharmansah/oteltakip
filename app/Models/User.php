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
        'shift_type',
        'start_time',
        'end_time',
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
            'start_time' => 'datetime',
            'end_time' => 'datetime',
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

    /**
     * Check if user is on day shift
     */
    public function isDayShift(): bool
    {
        return $this->shift_type === 'day';
    }

    /**
     * Check if user is on night shift
     */
    public function isNightShift(): bool
    {
        return $this->shift_type === 'night';
    }

    /**
     * Get shift type in Turkish
     */
    public function getShiftTypeTextAttribute(): string
    {
        return $this->shift_type === 'day' ? 'Gündüz' : 'Gece';
    }

    /**
     * Get formatted start time
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_time ? date('H:i', strtotime($this->start_time)) : '08:00';
    }

    /**
     * Get formatted end time
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return $this->end_time ? date('H:i', strtotime($this->end_time)) : '17:00';
    }

    /**
     * Get QR scans for a specific date
     */
    public function getScansForDate($date)
    {
        return $this->qrScans()
            ->whereDate('scanned_at', $date)
            ->orderBy('scanned_at')
            ->get();
    }

    /**
     * Get work hours for a specific date
     */
    public function getWorkHoursForDate($date)
    {
        $scans = $this->getScansForDate($date);
        
        if ($scans->count() < 2) {
            return [
                'total_hours' => 0,
                'check_in' => null,
                'check_out' => null,
                'is_late' => false,
                'is_early_leave' => false,
                'late_minutes' => 0,
                'early_leave_minutes' => 0
            ];
        }

        $checkIn = $scans->where('scan_type', 'check_in')->first();
        $checkOut = $scans->where('scan_type', 'check_out')->first();

        if (!$checkIn || !$checkOut) {
            return [
                'total_hours' => 0,
                'check_in' => $checkIn ? $checkIn->scanned_at : null,
                'check_out' => $checkOut ? $checkOut->scanned_at : null,
                'is_late' => false,
                'is_early_leave' => false,
                'late_minutes' => 0,
                'early_leave_minutes' => 0
            ];
        }

        $checkInTime = $checkIn->scanned_at;
        $checkOutTime = $checkOut->scanned_at;
        $expectedStart = \Carbon\Carbon::parse($date . ' ' . $this->start_time);
        $expectedEnd = \Carbon\Carbon::parse($date . ' ' . $this->end_time);

        $totalHours = $checkInTime->diffInHours($checkOutTime, true);
        $isLate = $checkInTime->gt($expectedStart);
        $isEarlyLeave = $checkOutTime->lt($expectedEnd);
        $lateMinutes = $isLate ? $checkInTime->diffInMinutes($expectedStart) : 0;
        $earlyLeaveMinutes = $isEarlyLeave ? $expectedEnd->diffInMinutes($checkOutTime) : 0;

        return [
            'total_hours' => round($totalHours, 2),
            'check_in' => $checkInTime,
            'check_out' => $checkOutTime,
            'is_late' => $isLate,
            'is_early_leave' => $isEarlyLeave,
            'late_minutes' => $lateMinutes,
            'early_leave_minutes' => $earlyLeaveMinutes
        ];
    }
}
