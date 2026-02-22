<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'status',
        'priority',
        'type',
        'due_date',
        'completed_at',
        'order',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'module_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
