<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFlow extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'slug',
        'description',
        'mermaid_diagram',
        'type',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'module_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
