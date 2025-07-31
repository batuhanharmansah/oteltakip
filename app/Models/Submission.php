<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'item_id',
        'user_id',
        'is_checked',
        'photo_path',
        'photo_data',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the assignment this submission belongs to
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the checklist item this submission is for
     */
    public function item()
    {
        return $this->belongsTo(ChecklistItem::class, 'item_id');
    }

    /**
     * Get the user who made this submission
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
