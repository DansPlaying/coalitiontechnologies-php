<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'priority'];

    protected $casts = [
        'priority' => 'integer',
        'project_id' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope tasks to a specific project (or null for unassigned tasks).
     * Passing -1 as $projectId returns tasks across all projects.
     */
    public function scopeForProject(Builder $query, ?int $projectId): Builder
    {
        if ($projectId === null) {
            return $query->whereNull('project_id');
        }

        return $query->where('project_id', $projectId);
    }
}
