<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'created_by',
    ];

    /**
     * Get the user who created this checklist
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get items in this checklist
     */
    public function items()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    /**
     * Get assignments for this checklist
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
