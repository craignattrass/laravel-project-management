<?php

namespace CraigNattrass\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectModule extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'category',
        'order',
    ];

    public function endpoints()
    {
        return $this->hasMany(ProjectEndpoint::class, 'module_id');
    }

    public function cliCommands()
    {
        return $this->hasMany(ProjectCliCommand::class, 'module_id');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class, 'module_id');
    }

    public function bugs()
    {
        return $this->hasMany(ProjectBug::class, 'module_id');
    }

    public function flows()
    {
        return $this->hasMany(ProjectFlow::class, 'module_id');
    }
}
