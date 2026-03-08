<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'status',
        'order',
        'display_on_home',
    ];

    protected $casts = [
        'status' => 'boolean',
        'display_on_home' => 'boolean',
        'order' => 'integer',
    ];

    // Relationship with products (many-to-many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    // Parent category relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child categories relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope for root categories (no parent)
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // Get full category path (breadcrumb)
    public function getPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Get product count for this specific category only
     * Does not include parent or children counts
     */
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    /**
     * Get total product count including all children categories
     * For root categories, this includes all products from subcategories
     * For child categories, this is just their own count
     */
    public function getTotalProductCountAttribute()
    {
        $count = $this->products()->count();
        
        // Only add children counts if this is a root category
        if (is_null($this->parent_id)) {
            foreach ($this->children as $child) {
                $count += $child->products()->count();
            }
        }
        
        return $count;
    }
}
