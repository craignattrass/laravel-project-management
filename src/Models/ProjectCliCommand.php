<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCliCommand extends Model
{
    protected $fillable = [
        'module_id',
        'signature',
        'name',
        'description',
        'class_name',
        'arguments',
        'options',
        'example_usage',
        'schedule',
        'is_documented',
        'last_scanned_at',
    ];

    protected $casts = [
        'arguments' => 'array',
        'options' => 'array',
        'is_documented' => 'boolean',
        'last_scanned_at' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'module_id');
    }
}
