<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'checklist_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the user assigned to this task
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the checklist for this assignment
     */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Get submissions for this assignment
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
