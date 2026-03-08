<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'status',
        'display_on_home',
    ];

    protected $casts = [
        'order' => 'integer',
        'display_on_home' => 'boolean',
    ];

    /**
     * Get all projects in this category (as primary category)
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_category_id');
    }

    /**
     * Get all projects where this is secondary category
     */
    public function projectsSecondary()
    {
        return $this->hasMany(Project::class, 'project_category_id_2');
    }

    /**
     * Get all projects (both primary and secondary)
     */
    public function allProjects()
    {
        return Project::where('project_category_id', $this->id)
            ->orWhere('project_category_id_2', $this->id)
            ->get();
    }

    /**
     * Count all projects in this category
     */
    public function getProjectCountAttribute()
    {
        return Project::where('project_category_id', $this->id)
            ->orWhere('project_category_id_2', $this->id)
            ->where('status', 'published')
            ->count();
    }

    /**
     * Scope a query to only include active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to order by custom order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
