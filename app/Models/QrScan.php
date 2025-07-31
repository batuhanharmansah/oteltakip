<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'qr_code_id',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /**
     * Get the user who scanned this QR code
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR code that was scanned
     */
    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}
