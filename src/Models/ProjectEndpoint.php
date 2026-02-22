<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEndpoint extends Model
{
    protected $fillable = [
        'module_id',
        'method',
        'uri',
        'name',
        'controller',
        'action',
        'description',
        'parameters',
        'response_example',
        'middleware',
        'requires_auth',
        'is_documented',
        'last_scanned_at',
    ];

    protected $casts = [
        'parameters' => 'array',
        'response_example' => 'array',
        'requires_auth' => 'boolean',
        'is_documented' => 'boolean',
        'last_scanned_at' => 'datetime',
    ];

    public function module()
    {
        return $this->belongsTo(ProjectModule::class, 'module_id');
    }
}
