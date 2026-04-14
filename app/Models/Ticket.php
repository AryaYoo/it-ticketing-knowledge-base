<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_to_user_id',
        'category_id',
        'title',
        'description',
        'client_image_path',
        'priority',
        'status',
        'resolution_problem_summary',
        'resolution_steps',
        'resolution_image_path',
        'resolved_at',
        'resolved_by_user_id',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by_user_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'open' => 'bg-warning bg-opacity-10 text-warning border border-warning-subtle',
            'in_progress' => 'bg-info bg-opacity-10 text-info border border-info-subtle',
            'resolved' => 'bg-success bg-opacity-10 text-success border border-success-subtle',
            'closed' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle',
            'escalated' => 'bg-danger bg-opacity-10 text-danger border border-danger-subtle',
            default => 'bg-secondary bg-opacity-10 text-secondary',
        };
    }
}
