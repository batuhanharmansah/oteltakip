<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'code',
        'image_path',
    ];

    /**
     * Get scans for this QR code
     */
    public function scans()
    {
        return $this->hasMany(QrScan::class);
    }

    /**
     * Get the full image path
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
