<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBug extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'severity',
        'status',
        'type',
        'steps_to_reproduce',
        'expected_behavior',
        'actual_behavior',
        'file_path',
        'line_number',
        'stack_trace',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'module_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }
}
