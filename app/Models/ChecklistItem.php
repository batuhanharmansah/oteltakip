<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'content',
    ];

    /**
     * Get the checklist this item belongs to
     */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Get submissions for this item
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'item_id');
    }
}
