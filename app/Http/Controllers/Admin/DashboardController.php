<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get product statistics
        $totalProducts = \App\Models\Product::count();
        $publishedProducts = \App\Models\Product::where('status', 'published')->count();
        
        // Get blog statistics
        $totalBlogs = \App\Models\Blog::count();
        $publishedBlogs = \App\Models\Blog::where('is_published', true)->count();
        $draftBlogs = \App\Models\Blog::where('is_published', false)->count();
        
        // Get top 10 most clicked products (sorted by clicks descending)
        $topClickedProducts = \App\Models\Product::orderBy('click_count', 'desc')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get(['name', 'click_count', 'view_count', 'brand']);
        
        // Get blog view stats for chart (top 10 published blogs by views)
        if (Schema::hasColumn('blogs', 'view_count')) {
            $blogViewStats = \App\Models\Blog::select('title', 'slug', 'view_count')
                ->where('is_published', true)
                ->orderByDesc('view_count')
                ->take(10)
                ->get();
        } else {
            $blogViewStats = \App\Models\Blog::select('title', 'slug')
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->take(10)
                ->get()
                ->map(function ($blog) {
                    $blog->view_count = 0;
                    return $blog;
                });
        }
        
        // Get product view stats for chart (top 10 by views)
        $productViewStats = \App\Models\Product::select('name', 'click_count', 'view_count', 'brand')
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('totalProducts', 'publishedProducts', 'totalBlogs', 'publishedBlogs', 'draftBlogs', 'topClickedProducts', 'blogViewStats', 'productViewStats'));
    }

    /**
     * Display the pages management view
     */
    public function pages()
    {
        return view('admin.pages');
    }

    /**
     * Update page content
     * Future: Add logic to edit pages or database content
     */
    public function update(Request $request)
    {
        // Validate and update page content
        // Return response
        return redirect()->route('admin.pages')->with('success', 'Page updated successfully');
    }

    /**
     * Display users management
     */
    public function users()
    {
        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$user->name} has been {$status}.");
    }

    /**
     * Display products management
     */
    public function products(Request $request)
    {
        $query = \App\Models\Product::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $brands = \App\Models\Brand::active()->orderBy('name')->pluck('name');
        $statuses = ['draft', 'published', 'archived'];
        
        // If AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'products' => $products->items(),
                'total' => $products->total(),
                'hasResults' => $products->count() > 0
            ]);
        }
        
        return view('admin.products', compact('products', 'brands', 'statuses'));
    }

    /**
     * Show create product form
     */
    public function createProduct()
    {
        $brands = \App\Models\Brand::active()->orderBy('name')->get();
        return view('admin.products-create', compact('brands'));
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:100',
                'sku' => 'nullable|string|max:100|unique:products,sku',
            'function_category' => 'nullable|string|max:100',
            'catalog' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'nullable|string',
            'spec_keys' => 'nullable|array',
            'spec_keys.*' => 'nullable|string',
            'spec_values' => 'nullable|array',
            'spec_values.*' => 'nullable|string',
            'specifications' => 'nullable',
            'image_url' => 'nullable|string|max:500',
            'image_alt' => 'nullable|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'manual_url' => 'nullable|string|max:500',
            'datasheet_url' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
            'min_order_quantity' => 'nullable|integer|min:1',
            'tags' => 'nullable|string',
            'categories' => 'nullable|string',
            'related_products' => 'nullable|string',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:100',
            'warranty_period' => 'nullable|string|max:100',
            'manufacturer_country' => 'nullable|string|max:100',
            'origin' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'structured_data' => 'nullable|string',
            'indexable' => 'nullable|boolean',
            'status' => 'nullable|in:draft,published,archived',
            'visibility' => 'nullable|in:visible,hidden',
            'featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'language' => 'nullable|string|max:10',
            'custom_fields' => 'nullable|string',
            'gallery_images' => 'nullable|array',
            'gallery_images.*.url' => 'nullable|string|max:500',
            'gallery_images.*.alt' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'action' => 'nullable|in:draft,publish',
        ]);

        // Generate slug from name
        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        
        // Auto-generate SEO fields if not provided (SEO best practices)
        if (empty($validated['meta_title'])) {
            $validated['meta_title'] = $request->name . ' | ' . ($request->brand ?? 'Products');
        }
        
        if (empty($validated['meta_description'])) {
            $description = $request->short_description ?: strip_tags($request->description ?? '');
            $validated['meta_description'] = \Illuminate\Support\Str::limit($description, 155);
        }
        
        if (empty($validated['canonical_url'])) {
            $validated['canonical_url'] = url('/product/' . $validated['slug']);
        }
        
        if (empty($validated['og_title'])) {
            $validated['og_title'] = $validated['meta_title'];
        }
        
        if (empty($validated['og_description'])) {
            $validated['og_description'] = $validated['meta_description'];
        }
        
        // Auto-generate meta keywords if not provided
        if (empty($validated['meta_keywords'])) {
            $keywords = [];
            if ($request->name) $keywords[] = $request->name;
            if ($request->brand) $keywords[] = $request->brand;
            if ($request->function_category) $keywords[] = $request->function_category;
            if (!empty($keywords)) {
                $validated['meta_keywords'] = implode(', ', array_unique($keywords));
            }
        }
        
        // Convert checkbox values
        $validated['featured'] = $request->has('featured') ? 1 : 0;
        $validated['is_new'] = $request->has('is_new') ? 1 : 0;
        $validated['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;
        $validated['indexable'] = $request->has('indexable') ? 1 : 0;
        
        // Convert comma-separated strings to arrays for JSON fields
        $jsonFields = ['tags', 'categories', 'related_products'];
        foreach ($jsonFields as $field) {
            if (isset($validated[$field]) && is_string($validated[$field])) {
                $validated[$field] = array_filter(array_map('trim', explode(',', $validated[$field])));
            }
        }
        
        // Handle gallery_images array - filter out empty values and ensure proper structure
        if (isset($validated['gallery_images']) && is_array($validated['gallery_images'])) {
            $validated['gallery_images'] = array_values(array_filter($validated['gallery_images'], function($image) {
                return !empty(trim($image['url'] ?? ''));
            }));
        }
        
        // Handle features array - filter out empty values
        if (isset($validated['features']) && is_array($validated['features'])) {
            $validated['features'] = array_values(array_filter($validated['features'], function($feature) {
                return !empty(trim($feature));
            }));
        }
        
        // Handle specifications - combine keys and values into associative array
        if (isset($validated['spec_keys']) && isset($validated['spec_values'])) {
            $specifications = [];
            $keys = $validated['spec_keys'];
            $values = $validated['spec_values'];
            
            for ($i = 0; $i < count($keys); $i++) {
                $key = trim($keys[$i] ?? '');
                $value = trim($values[$i] ?? '');
                
                if (!empty($key) && !empty($value)) {
                    $specifications[$key] = $value;
                }
            }
            
            $validated['specifications'] = !empty($specifications) ? $specifications : null;
            
            // Remove the temporary arrays
            unset($validated['spec_keys']);
            unset($validated['spec_values']);
        }

        // Fallback: If specs weren't provided as keys/values arrays, accept JSON/array from `specifications` input
        if ((empty($validated['specifications']) || is_string($validated['specifications'])) && $request->filled('specifications')) {
            $specsInput = $request->input('specifications');
            if (is_string($specsInput)) {
                $decoded = json_decode($specsInput, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                    $validated['specifications'] = $decoded;
                }
            } elseif (is_array($specsInput) && !empty($specsInput)) {
                $validated['specifications'] = $specsInput;
            }
        }
        
        // Convert JSON strings to arrays for complex JSON fields (excluding features and specifications)
        $complexJsonFields = ['custom_fields', 'structured_data'];
        foreach ($complexJsonFields as $field) {
            if (isset($validated[$field]) && is_string($validated[$field]) && !empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated[$field] = $decoded;
                }
            }
        }
        
        // Handle action button - override status based on button clicked
        if ($request->action === 'publish') {
            $validated['status'] = 'published';
            if (!$request->published_at) {
                $validated['published_at'] = now();
            }
        } else {
            // Explicitly set as draft when not publishing (draft button or no action)
            $validated['status'] = 'draft';
            // Clear published_at if saving as draft
            $validated['published_at'] = null;
        }
        
        // SEO validation and warnings
        if ($validated['status'] === 'published') {
            $warnings = [];
            
            if (empty($validated['meta_title']) && empty($request->name)) {
                $warnings[] = 'Product name is required for SEO optimization';
            }
            
            if (empty($validated['meta_description']) && empty($request->short_description) && empty($request->description)) {
                $warnings[] = 'Product description is recommended for better SEO';
            }
            
            if (empty($validated['image_alt']) && !empty($validated['image_url'])) {
                $warnings[] = 'Image alt text is recommended for accessibility and SEO';
            }
            
            if (!empty($warnings)) {
                \Illuminate\Support\Facades\Log::info('SEO Warnings for Product Creation', $warnings);
            }
        }

        // Log for debugging
        \Illuminate\Support\Facades\Log::info('Product Creation', [
            'action' => $request->action,
            'final_status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null
        ]);

        $product = \App\Models\Product::create($validated);
        
        // Sync categories if provided
        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }
        
        $message = $request->action === 'publish' ? 'Product published successfully!' : 'Product saved as draft!';
        return redirect()->route('admin.products')->with('success', $message);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Improve SKU duplicate error message
            $errors = $e->errors();
            if (isset($errors['sku'])) {
                return redirect()->back()->withInput()->withErrors([
                    'sku' => 'This SKU is already used by another product. Each product must have a unique SKU. Please use a different SKU.'
                ]);
            }
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Show edit product form
     */
    public function editProduct($id)
    {
        // Load product fresh with categories relationship
        $product = \App\Models\Product::with('categories')->findOrFail($id);
        $brands = \App\Models\Brand::active()->orderBy('name')->get();
        
        // categories loaded
        
        return view('admin.products-edit', compact('product', 'brands'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, \App\Models\Product $product)
    {
        try {
            // request received for update
            \Illuminate\Support\Facades\Log::error('DEBUG PAYLOAD: ' . json_encode($request->all()));

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'brand' => 'required|string|max:100',
                'sku' => [
                    'nullable',
                    'string',
                    'max:100',
                    \Illuminate\Validation\Rule::unique('products')->ignore($product->id)->whereNull('deleted_at')
                ],
                'function_category' => 'nullable|string|max:100',
                'catalog' => 'nullable|string|max:100',
                'short_description' => 'nullable|string',
                'description' => 'nullable|string',
                'features' => 'nullable|array',
                'features.*' => 'nullable|string',
                'spec_keys' => 'nullable|array',
                'spec_keys.*' => 'nullable|string',
                'spec_values' => 'nullable|array',
                'spec_values.*' => 'nullable|string',
                'specifications' => 'nullable',
                'image_url' => 'nullable|string|max:500',
                'image_alt' => 'nullable|string|max:255',
                'video_url' => 'nullable|string|max:500',
                'manual_url' => 'nullable|string|max:500',
                'datasheet_url' => 'nullable|string|max:500',
                'price' => 'nullable|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'currency' => 'nullable|string|max:10',
                'stock_quantity' => 'nullable|integer|min:0',
                'stock_status' => 'nullable|in:in_stock,out_of_stock,on_backorder',
                'min_order_quantity' => 'nullable|integer|min:1',
                'tags' => 'nullable|string',
                'categories' => 'nullable|string',
                'category_ids' => 'nullable|array',
                'category_ids.*' => 'nullable|integer|exists:categories,id',
                'related_products' => 'nullable|string',
                'weight' => 'nullable|string|max:50',
                'dimensions' => 'nullable|string|max:100',
                'color' => 'nullable|string|max:50',
                'material' => 'nullable|string|max:100',
                'warranty_period' => 'nullable|string|max:100',
                'manufacturer_country' => 'nullable|string|max:100',
                'origin' => 'nullable|string|max:100',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
                'canonical_url' => 'nullable|string|max:500',
                'og_image' => 'nullable|string|max:500',
                'og_title' => 'nullable|string|max:255',
                'og_description' => 'nullable|string|max:500',
                'structured_data' => 'nullable|string',
                'indexable' => 'nullable|boolean',
                'status' => 'nullable|in:draft,published,archived',
                'visibility' => 'nullable|in:visible,hidden',
                'featured' => 'nullable|boolean',
                'is_new' => 'nullable|boolean',
                'is_bestseller' => 'nullable|boolean',
                'language' => 'nullable|string|max:10',
                'custom_fields' => 'nullable|string',
                'gallery_images' => 'nullable|array',
                'gallery_images.*.url' => 'nullable|string|max:500',
                'gallery_images.*.alt' => 'nullable|string|max:255',
                'published_at' => 'nullable|date',
                'action' => 'nullable|in:draft,publish',
            ]);

            // Update slug if name changed and save old slug
            if ($request->name !== $product->name) {
                $validated['old_slug'] = $product->slug;
                $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
            }
            
            // Auto-update SEO fields if not provided or if name/content changed (SEO best practices)
            if (empty($validated['meta_title']) || $request->name !== $product->name) {
                $validated['meta_title'] = $request->name . ' | ' . ($request->brand ?? 'Products');
            }
            
            if (empty($validated['meta_description']) || 
                $request->short_description !== $product->short_description) {
                $description = $request->short_description ?: strip_tags($request->description ?? '');
                if (!empty($description)) {
                    $validated['meta_description'] = \Illuminate\Support\Str::limit($description, 155);
                }
            }
            
            if (empty($validated['canonical_url']) || isset($validated['slug'])) {
                $slug = $validated['slug'] ?? $product->slug;
                $validated['canonical_url'] = url('/product/' . $slug);
            }
            
            if (empty($validated['og_title'])) {
                $validated['og_title'] = $validated['meta_title'] ?? $product->meta_title;
            }
            
            if (empty($validated['og_description'])) {
                $validated['og_description'] = $validated['meta_description'] ?? $product->meta_description;
            }
            
            // Auto-update meta keywords if not provided or if key attributes changed
            if (empty($validated['meta_keywords']) || 
                $request->name !== $product->name || 
                $request->brand !== $product->brand) {
                $keywords = [];
                if ($request->name) $keywords[] = $request->name;
                if ($request->brand) $keywords[] = $request->brand;
                if ($request->function_category) $keywords[] = $request->function_category;
                if (!empty($keywords)) {
                    $validated['meta_keywords'] = implode(', ', array_unique($keywords));
                }
            }
            
            // Convert checkbox values
            $validated['featured'] = $request->has('featured') ? 1 : 0;
            $validated['is_new'] = $request->has('is_new') ? 1 : 0;
            $validated['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;
            $validated['indexable'] = $request->has('indexable') ? 1 : 0;
            
            // Convert comma-separated strings to arrays for JSON fields
            $jsonFields = ['tags', 'related_products']; // Removed 'categories' - using relationship
            foreach ($jsonFields as $field) {
                if (isset($validated[$field]) && is_string($validated[$field])) {
                    $validated[$field] = array_filter(array_map('trim', explode(',', $validated[$field])));
                }
            }
            
            // Handle gallery_images array - filter out empty values and ensure proper structure
            if (isset($validated['gallery_images']) && is_array($validated['gallery_images'])) {
                $validated['gallery_images'] = array_values(array_filter($validated['gallery_images'], function($image) {
                    return !empty(trim($image['url'] ?? ''));
                }));
            }
            
            // Handle features array - filter out empty values
            if (isset($validated['features']) && is_array($validated['features'])) {
                $validated['features'] = array_values(array_filter($validated['features'], function($feature) {
                    return !empty(trim($feature));
                }));
            }
            
            // Handle specifications - combine keys and values into associative array
            if (isset($validated['spec_keys']) && isset($validated['spec_values'])) {
                $specifications = [];
                $keys = $validated['spec_keys'];
                $values = $validated['spec_values'];
                
                for ($i = 0; $i < count($keys); $i++) {
                    $key = trim($keys[$i] ?? '');
                    $value = trim($values[$i] ?? '');
                    
                    if (!empty($key) && !empty($value)) {
                        $specifications[$key] = $value;
                    }
                }
                
                $validated['specifications'] = !empty($specifications) ? $specifications : null;
                
                // Remove the temporary arrays
                unset($validated['spec_keys']);
                unset($validated['spec_values']);
            }

            // Fallback: If specs weren't provided as keys/values arrays, accept JSON/array from `specifications` input
            if ((empty($validated['specifications']) || is_string($validated['specifications'])) && $request->filled('specifications')) {
                $specsInput = $request->input('specifications');
                if (is_string($specsInput)) {
                    $decoded = json_decode($specsInput, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                        $validated['specifications'] = $decoded;
                    }
                } elseif (is_array($specsInput) && !empty($specsInput)) {
                    $validated['specifications'] = $specsInput;
                }
            }
            
            // Convert JSON strings to arrays for complex JSON fields (excluding features and specifications)
            $complexJsonFields = ['custom_fields', 'structured_data'];
            foreach ($complexJsonFields as $field) {
                if (isset($validated[$field]) && is_string($validated[$field]) && !empty($validated[$field])) {
                    $decoded = json_decode($validated[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $validated[$field] = $decoded;
                    }
                }
            }
            
            // Handle action button - override status based on button clicked
            if ($request->action === 'publish') {
                $validated['status'] = 'published';
                if (!$request->published_at && !$product->published_at) {
                    $validated['published_at'] = now();
                }
            } else {
                // Explicitly set as draft when not publishing (draft button or no action)
                $validated['status'] = 'draft';
                // Don't clear published_at for updates if it was previously set
            }
            
            // Log before update
            \Illuminate\Support\Facades\Log::info('Before Update', [
                'validated_data' => $validated,
                'product_before' => $product->toArray()
            ]);
            
            $product->update($validated);
            
            // Sync categories - if no categories selected, detach all
            $categoryIds = $request->input('category_ids', []);
            $product->categories()->sync($categoryIds);
            
            // Log after update
            \Illuminate\Support\Facades\Log::info('After Update', [
                'product_after' => $product->fresh()->toArray()
            ]);
            
            $message = $request->action === 'publish' ? 'Product updated and published!' : 'Product updated successfully!';
            return redirect()->route('admin.products')->with('success', $message);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Improve SKU duplicate error message
            $errors = $e->errors();
            if (isset($errors['sku'])) {
                return redirect()->back()->withInput()->withErrors([
                    'sku' => 'This SKU is already used by another product. Each product must have a unique SKU. Please use a different SKU like "' . ($request->sku ?? '') . '-1"'
                ]);
            }
            throw $e;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Save product features directly via AJAX
     */
    public function saveFeatures(Request $request, \App\Models\Product $product)
    {
        try {
            $validated = $request->validate([
                'features' => 'nullable|array',
                'features.*' => 'nullable|string',
            ]);

            $features = [];
            if (isset($validated['features']) && is_array($validated['features'])) {
                $features = array_values(array_filter($validated['features'], function($feature) {
                    return !empty(trim($feature));
                }));
            }

            $product->update(['features' => $features]);

            return response()->json([
                'success' => true,
                'message' => 'Features saved successfully',
                'features' => $features
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to save features: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving features: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function deleteProduct(\App\Models\Product $product)
    {
        try {
            // Collect image paths from product
            $imagePaths = [];

            // Main image
            if (!empty($product->image_url)) {
                $imagePaths[] = $product->image_url;
            }

            // Gallery images - stored as JSON or array
            $gallery = $product->gallery_images;
            if (is_string($gallery)) {
                $decoded = json_decode($gallery, true);
                $gallery = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
            }
            if (is_array($gallery)) {
                foreach ($gallery as $g) {
                    $url = null;
                    if (is_array($g) && isset($g['url'])) {
                        $url = $g['url'];
                    } elseif (is_string($g)) {
                        $url = $g;
                    }
                    if ($url) $imagePaths[] = $url;
                }
            }

            // Normalize and unique paths
            $imagePaths = array_values(array_unique(array_filter($imagePaths)));

            foreach ($imagePaths as $path) {
                // Skip remote URLs
                if (strpos($path, '://') !== false) continue;

                // Normalize leading slash
                $relative = ltrim($path, '/');
                $publicFile = public_path($relative);

                // Check if any other product uses this path (exclude current product)
                $otherUsage = \App\Models\Product::where('id', '!=', $product->id)
                    ->where(function($q) use ($relative) {
                        $q->where('image_url', $relative)
                          ->orWhere('image_url', '/' . $relative)
                          ->orWhere('gallery_images', 'LIKE', '%"' . $relative . '"%')
                          ->orWhere('gallery_images', 'LIKE', '%"/' . $relative . '"%');
                    })->exists();

                if (!$otherUsage && file_exists($publicFile)) {
                    try {
                        @unlink($publicFile);
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to delete product image file: ' . $publicFile . ' - ' . $e->getMessage());
                    }
                }
            }

            // Now delete the product record (this will remove pivot rows)
            $product->delete();

            return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Product deletion error: ' . $e->getMessage());
            return redirect()->route('admin.products')->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product status
     */
    public function toggleProductStatus(\App\Models\Product $product)
    {
        $newStatus = $product->status === 'published' ? 'draft' : 'published';
        $product->update(['status' => $newStatus]);
        
        return redirect()->back()->with('success', "Product status changed to {$newStatus}.");
    }

    /**
     * Display brands management
     */
    public function brands()
    {
        $brands = \App\Models\Brand::orderBy('name')->paginate(20);
        return view('admin.brands', compact('brands'));
    }

    /**
     * Show create brand form
     */
    public function createBrand()
    {
        return view('admin.brands-create');
    }

    /**
     * Store new brand
     */
    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            // logo_url may be a remote URL or a relative local path (assets/...)
            'logo_url' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:500',
            'status' => 'nullable|boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        $validated['status'] = $request->has('status') ? 1 : 0;

        // If the user indicated they removed the logo before saving, ensure it's null
        if ($request->boolean('remove_logo')) {
            $validated['logo_url'] = null;
        }

        \App\Models\Brand::create($validated);
        
        return redirect()->route('admin.brands')->with('success', 'Brand created successfully!');
    }

    /**
     * Show edit brand form
     */
    public function editBrand(\App\Models\Brand $brand)
    {
        return view('admin.brands-edit', compact('brand'));
    }

    /**
     * Update brand
     */
    public function updateBrand(Request $request, \App\Models\Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            // allow relative paths like assets/AIcontrol_imgs/ALLBrandImgs/...
            'logo_url' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:500',
            'status' => 'nullable|boolean',
        ]);

        if ($request->name !== $brand->name) {
            $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        }
        
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // If the admin requested removal of the current logo, delete the file (local only) and clear the value
        if ($request->boolean('remove_logo')) {
            if (!empty($brand->logo_url) && strpos($brand->logo_url, '://') === false) {
                $relative = ltrim($brand->logo_url, '/');
                $publicFile = public_path($relative);
                if (file_exists($publicFile)) {
                    try { @unlink($publicFile); } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to delete brand logo file: ' . $publicFile . ' - ' . $e->getMessage());
                    }
                }
            }
            $validated['logo_url'] = null;
        }

        $brand->update($validated);
        
        return redirect()->route('admin.brands')->with('success', 'Brand updated successfully!');
    }

    /**
     * Delete brand
     */
    public function deleteBrand(\App\Models\Brand $brand)
    {
        $brand->delete();
        
        return redirect()->route('admin.brands')->with('success', 'Brand deleted successfully!');
    }

    /**
     * Manage Categories
     */
    public function categories()
    {
        $categories = \App\Models\Category::with('children', 'parent')
                                         ->roots()
                                         ->orderBy('order')
                                         ->orderBy('name')
                                         ->get();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Show create category form
     */
    public function createCategory(Request $request)
    {
        $parentCategories = \App\Models\Category::active()
                                                ->roots()
                                                ->orderBy('name')
                                                ->get();
        $parentId = $request->get('parent');
        return view('admin.categories-create', compact('parentCategories', 'parentId'));
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean',
            'display_on_home' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['display_on_home'] = $request->has('display_on_home') ? 1 : 0;
        $validated['order'] = $request->order ?? 0;
        
        \App\Models\Category::create($validated);
        
        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    /**
     * Show edit category form
     */
    public function editCategory(\App\Models\Category $category)
    {
        $parentCategories = \App\Models\Category::active()
                                                ->roots()
                                                ->where('id', '!=', $category->id)
                                                ->orderBy('name')
                                                ->get();
        return view('admin.categories-edit', compact('category', 'parentCategories'));
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, \App\Models\Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean',
            'display_on_home' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Prevent setting self as parent
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
        }

        if ($request->name !== $category->name) {
            $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        }
        
        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['display_on_home'] = $request->has('display_on_home') ? 1 : 0;
        $validated['order'] = $request->order ?? 0;
        
        $category->update($validated);
        
        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }

    /**
     * Delete category
     */
    public function deleteCategory(\App\Models\Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated products. Please reassign or remove products first.');
        }

        // Check if category has child categories
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
        }

        $category->delete();
        
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }

    /**
     * Display blog categories management
     */
    public function blogCategories()
    {
        $rootCategories = \App\Models\BlogCategory::roots()
            ->with('children')
            ->orderBy('order')
            ->get();

        return view('admin.blog-categories', compact('rootCategories'));
    }

    /**
     * Show form to create a new blog category
     */
    public function createBlogCategory(Request $request)
    {
        $parentId = $request->get('parent');
        $parentCategory = $parentId ? \App\Models\BlogCategory::find($parentId) : null;
        
        return view('admin.blog-categories-create', compact('parentCategory'));
    }

    /**
     * Store a new blog category
     */
    public function storeBlogCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|boolean',
            'order' => 'required|integer|min:0',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

    $validated['status'] = $request->boolean('status');

    \App\Models\BlogCategory::create($validated);

        $redirectRoute = $request->parent_id 
            ? route('admin.blog-categories') 
            : route('admin.blog-categories');

        return redirect($redirectRoute)->with('success', 'Blog category created successfully!');
    }

    /**
     * Show form to edit a blog category
     */
    public function editBlogCategory(\App\Models\BlogCategory $blogCategory)
    {
        // Get all categories except current and its children for parent selection
        $availableParents = \App\Models\BlogCategory::where('id', '!=', $blogCategory->id)
            ->whereNull('parent_id')
            ->get();

        return view('admin.blog-categories-edit', compact('blogCategory', 'availableParents'));
    }

    /**
     * Update a blog category
     */
    public function updateBlogCategory(Request $request, \App\Models\BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|boolean',
            'order' => 'required|integer|min:0',
        ]);

        // Prevent setting itself as parent
        if ($validated['parent_id'] == $blogCategory->id) {
            return back()->with('error', 'A category cannot be its own parent.');
        }

    $validated['status'] = $request->boolean('status');

    $blogCategory->update($validated);

        return redirect()->route('admin.blog-categories')->with('success', 'Blog category updated successfully!');
    }

    /**
     * Delete a blog category
     */
    public function deleteBlogCategory(\App\Models\BlogCategory $blogCategory)
    {
        // Check if category has blogs
        if ($blogCategory->blogs()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated blogs. Please reassign or remove blogs first.');
        }

        // Check if category has child categories
        if ($blogCategory->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Please delete or reassign subcategories first.');
        }

    // Perform permanent deletion (forceDelete) because we use SoftDeletes on BlogCategory
    // This removes the row from the database instead of just setting deleted_at.
    $blogCategory->forceDelete();

    return redirect()->route('admin.blog-categories')->with('success', 'Blog category permanently deleted.');
    }

    /**
     * Upload image and store in public/assets/aicontrol_imgs/AllProductImages
     */
    public function uploadImage(Request $request)
    {
        try {
            // Allow common raster formats plus SVG for brand logos
            $request->validate([
                'image' => 'required|mimes:jpeg,png,jpg,gif,webp,svg|max:5120', // Max 5MB
            ]);

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file provided'
                ], 400);
            }

            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $baseFileName = pathinfo($originalName, PATHINFO_FILENAME);
            
            // Determine target folder (default: AllProductImages)
            $target = $request->input('target', null);
            if ($target === 'brand') {
                // Brand logos go to ALLBrandImgs
                $uploadPath = public_path('assets/AIcontrol_imgs/ALLBrandImgs');
                $responseBase = 'assets/AIcontrol_imgs/ALLBrandImgs/';
            } else {
                // Default products folder (existing behavior)
                $uploadPath = public_path('assets/aicontrol_imgs/AllProductImages');
                $responseBase = 'assets/aicontrol_imgs/AllProductImages/';
            }
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Check if file with same name exists
            $fileName = $originalName;
            $filePath = $uploadPath . '/' . $fileName;
            $fileExists = file_exists($filePath);

            // If a desired filename was provided, use it (after sanitizing)
            $desired = $request->input('filename');
            if ($desired) {
                // Ensure the desired filename has the same extension
                $desired = basename($desired); // sanitize
                $desiredExt = pathinfo($desired, PATHINFO_EXTENSION);
                if (strtolower($desiredExt) !== strtolower($extension)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Filename extension does not match uploaded file.',
                    ], 422);
                }

                $fileName = $desired;
                $filePath = $uploadPath . '/' . $fileName;
                if (file_exists($filePath)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A file with the requested filename already exists. Please choose a different name.',
                        'path' => $responseBase . $fileName,
                        'filename' => $fileName,
                        'exists' => true
                    ], 409);
                }

                // Move file with desired name
                $image->move($uploadPath, $fileName);

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'path' => $responseBase . $fileName,
                    'filename' => $fileName,
                    'renamed' => false
                ]);
            }

            // No desired filename provided: check if file exists and return conflict
            if ($fileExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'A file with the same name already exists. Please rename your file before uploading or provide a different filename.',
                    'path' => $responseBase . $fileName,
                    'filename' => $fileName,
                    'originalName' => $originalName,
                    'exists' => true
                ], 409);
            }

            // Move the file with original name
            $image->move($uploadPath, $fileName);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'path' => $responseBase . $fileName,
                'filename' => $fileName,
                'renamed' => false
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== PROJECT CATEGORIES ====================
    
    /**
     * Display project categories
     */
    public function projectCategories()
    {
        $categories = \App\Models\ProjectCategory::orderBy('order')->orderBy('name')->paginate(15);
        return view('admin.project-categories', compact('categories'));
    }

    /**
     * Show create project category form
     */
    public function createProjectCategory()
    {
        return view('admin.project-categories-create');
    }

    /**
     * Store new project category
     */
    public function storeProjectCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:project_categories,name',
            'slug' => 'nullable|string|max:255|unique:project_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'display_on_home' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';
        $validated['display_on_home'] = $request->has('display_on_home') ? 1 : 0;
        \App\Models\ProjectCategory::create($validated);

        return redirect()->route('admin.project-categories')->with('success', 'Project category created successfully!');
    }

    /**
     * Show edit project category form
     */
    public function editProjectCategory($id)
    {
        $category = \App\Models\ProjectCategory::findOrFail($id);
        return view('admin.project-categories-edit', compact('category'));
    }

    /**
     * Update project category
     */
    public function updateProjectCategory(Request $request, $id)
    {
        $category = \App\Models\ProjectCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:project_categories,name,' . $id,
            'slug' => 'required|string|max:255|unique:project_categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'display_on_home' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';
        $validated['display_on_home'] = $request->has('display_on_home') ? 1 : 0;
        $category->update($validated);

        return redirect()->route('admin.project-categories')->with('success', 'Project category updated successfully!');
    }

    /**
     * Delete project category
     */
    public function deleteProjectCategory($id)
    {
        $category = \App\Models\ProjectCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.project-categories')->with('success', 'Project category deleted successfully!');
    }

    // ==================== PROJECTS ====================
    
    /**
     * Display projects
     */
    public function projects()
    {
        $projects = \App\Models\Project::with(['category', 'categorySecondary'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        return view('admin.projects', compact('projects'));
    }

    /**
     * Show create project form
     */
    public function createProject()
    {
        $categories = \App\Models\ProjectCategory::active()->ordered()->get();
        return view('admin.projects-create', compact('categories'));
    }

    /**
     * Store new project
     */
    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug',
            'short_description' => 'nullable|string|max:500',
            'project_category_id' => 'nullable|exists:project_categories,id',
            'project_category_id_2' => 'nullable|exists:project_categories,id',
            'detail_1_title' => 'nullable|string|max:255',
            'detail_1_value' => 'nullable|string|max:255',
            'detail_2_title' => 'nullable|string|max:255',
            'detail_2_value' => 'nullable|string|max:255',
            'detail_3_title' => 'nullable|string|max:255',
            'detail_3_value' => 'nullable|string|max:255',
            'detail_4_title' => 'nullable|string|max:255',
            'detail_4_value' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|max:2048',
            'thumbnail_image' => 'nullable|image|max:2048',
            'overview_title' => 'nullable|string|max:255',
            'overview_content' => 'nullable|string',
            'slider_image_files.*' => 'nullable|image|max:2048',
            'slider_image_urls.*' => 'nullable|string',
            'slider_image_alts.*' => 'nullable|string',
            'secondary_title' => 'nullable|string|max:255',
            'detail_steps_title' => 'nullable|array',
            'detail_steps_description' => 'nullable|array',
            'gallery_image_1' => 'nullable|image|max:2048',
            'gallery_image_2' => 'nullable|image|max:2048',
            'gallery_image_3' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:70',  // SEO optimal length
            'meta_description' => 'nullable|string|max:160',  // SEO optimal length
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề dự án',
            'meta_title.max' => 'Meta title không nên quá 70 ký tự (tối ưu SEO)',
            'meta_description.max' => 'Meta description không nên quá 160 ký tự (tối ưu SEO)',
            'short_description.max' => 'Mô tả ngắn không nên quá 500 ký tự',
        ]);

        // Auto-generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        // Handle featured checkbox
        $validated['featured'] = $request->has('featured') ? 1 : 0;

        // Auto-set published_at if status is published and published_at is not set
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Handle image uploads - all to public/assets/AIcontrol_imgs/AllProjectImgs
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['banner_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['banner_image']);
        }

        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = time() . '_thumb_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['thumbnail_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['thumbnail_image']);
        }

        if ($request->hasFile('og_image')) {
            $file = $request->file('og_image');
            $filename = time() . '_og_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['og_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['og_image']);
        }

        // Handle gallery images
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('gallery_image_' . $i)) {
                $file = $request->file('gallery_image_' . $i);
                $filename = time() . '_gallery' . $i . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
                $validated['gallery_image_' . $i] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
            } else {
                unset($validated['gallery_image_' . $i]);
            }
        }

        // Handle slider images with file uploads and alt texts
        $sliderImages = [];
        $sliderFiles = $request->file('slider_image_files', []);
        $sliderUrls = $request->input('slider_image_urls', []);
        $sliderAlts = $request->input('slider_image_alts', []);
        
        foreach ($sliderFiles as $index => $file) {
            $imageData = [];
            
            // Handle file upload
            if ($file && $file->isValid()) {
                $filename = time() . '_slider' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
                $imageData['url'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
            } 
            // Handle URL input if no file uploaded
            elseif (!empty($sliderUrls[$index])) {
                $imageData['url'] = $sliderUrls[$index];
            }
            
            // Add alt text if provided
            if (!empty($sliderAlts[$index])) {
                $imageData['alt'] = $sliderAlts[$index];
            }
            
            // Only add if we have at least a URL
            if (!empty($imageData['url'])) {
                $sliderImages[] = $imageData;
            }
        }
        
        // Also process standalone URLs (if no file was uploaded for that index)
        foreach ($sliderUrls as $index => $url) {
            // Skip if we already processed this index via file upload
            if (isset($sliderFiles[$index]) && $sliderFiles[$index] && $sliderFiles[$index]->isValid()) {
                continue;
            }
            
            if (!empty($url)) {
                $imageData = ['url' => $url];
                if (!empty($sliderAlts[$index])) {
                    $imageData['alt'] = $sliderAlts[$index];
                }
                $sliderImages[] = $imageData;
            }
        }
        
        $validated['slider_images'] = $sliderImages;

        // Build detail_steps array from title and description arrays
        $detailSteps = [];
        if (isset($validated['detail_steps_title']) && isset($validated['detail_steps_description'])) {
            $titles = $validated['detail_steps_title'];
            $descriptions = $validated['detail_steps_description'];
            
            for ($i = 0; $i < count($titles); $i++) {
                if (!empty($titles[$i]) || !empty($descriptions[$i])) {
                    $detailSteps[] = [
                        'title' => $titles[$i] ?? '',
                        'description' => $descriptions[$i] ?? ''
                    ];
                }
            }
        }
        $validated['detail_steps'] = $detailSteps;

        // Remove the separate arrays
        unset($validated['detail_steps_title']);
        unset($validated['detail_steps_description']);

        \App\Models\Project::create($validated);

        return redirect()->route('admin.projects')->with('success', 'Dự án đã được tạo thành công!');
    }

    /**
     * Show edit project form
     */
    public function editProject($id)
    {
        $project = \App\Models\Project::findOrFail($id);
        $categories = \App\Models\ProjectCategory::active()->ordered()->get();
        return view('admin.projects-edit', compact('project', 'categories'));
    }

    /**
     * Update project
     */
    public function updateProject(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:projects,slug,' . $id,
            'short_description' => 'nullable|string',
            'project_category_id' => 'nullable|exists:project_categories,id',
            'project_category_id_2' => 'nullable|exists:project_categories,id',
            'detail_1_title' => 'nullable|string|max:255',
            'detail_1_value' => 'nullable|string|max:255',
            'detail_2_title' => 'nullable|string|max:255',
            'detail_2_value' => 'nullable|string|max:255',
            'detail_3_title' => 'nullable|string|max:255',
            'detail_3_value' => 'nullable|string|max:255',
            'detail_4_title' => 'nullable|string|max:255',
            'detail_4_value' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|max:2048',
            'thumbnail_image' => 'nullable|image|max:2048',
            'overview_title' => 'nullable|string|max:255',
            'overview_content' => 'nullable|string',
            'slider_image_files.*' => 'nullable|image|max:2048',
            'slider_image_urls.*' => 'nullable|string|max:500',
            'slider_image_alts.*' => 'nullable|string|max:255',
            'existing_slider_images' => 'nullable|array',
            'secondary_title' => 'nullable|string|max:255',
            'detail_steps_title' => 'nullable|array',
            'detail_steps_description' => 'nullable|array',
            'gallery_image_1' => 'nullable|image|max:2048',
            'gallery_image_2' => 'nullable|image|max:2048',
            'gallery_image_3' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'featured' => 'nullable',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        }

        // Handle featured checkbox
        $validated['featured'] = $request->has('featured') ? 1 : 0;

        // Auto-set published_at if status is published and published_at is not set
        if ($validated['status'] === 'published' && empty($validated['published_at']) && empty($project->published_at)) {
            $validated['published_at'] = now();
        }

        // Handle image uploads - all to public/assets/AIcontrol_imgs/AllProjectImgs
        if ($request->hasFile('banner_image')) {
            // Delete old image if exists
            if ($project->banner_image && file_exists(public_path($project->banner_image))) {
                unlink(public_path($project->banner_image));
            }
            $file = $request->file('banner_image');
            $filename = time() . '_banner_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['banner_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['banner_image']);
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($project->thumbnail_image && file_exists(public_path($project->thumbnail_image))) {
                unlink(public_path($project->thumbnail_image));
            }
            $file = $request->file('thumbnail_image');
            $filename = time() . '_thumb_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['thumbnail_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['thumbnail_image']);
        }

        if ($request->hasFile('og_image')) {
            if ($project->og_image && file_exists(public_path($project->og_image))) {
                unlink(public_path($project->og_image));
            }
            $file = $request->file('og_image');
            $filename = time() . '_og_' . $file->getClientOriginalName();
            $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
            $validated['og_image'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
        } else {
            unset($validated['og_image']);
        }

        // Handle gallery images
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile('gallery_image_' . $i)) {
                $oldImage = $project->{'gallery_image_' . $i};
                if ($oldImage && file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }
                $file = $request->file('gallery_image_' . $i);
                $filename = time() . '_gallery' . $i . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
                $validated['gallery_image_' . $i] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
            } else {
                unset($validated['gallery_image_' . $i]);
            }
        }

        // Process slider images with file uploads and alt texts
        $sliderFiles = $request->file('slider_image_files', []);
        $sliderUrls = $request->input('slider_image_urls', []);
        $sliderAlts = $request->input('slider_image_alts', []);
        $existingSliderImages = $request->input('existing_slider_images', []);
        
        $sliderImages = [];
        
        // First, preserve existing images that weren't removed
        if (is_array($existingSliderImages)) {
            foreach ($existingSliderImages as $existing) {
                if (is_string($existing)) {
                    // Old format: simple string URL
                    $sliderImages[] = ['url' => $existing, 'alt' => ''];
                } elseif (is_array($existing) && isset($existing['url'])) {
                    // New format: object with url and alt
                    $sliderImages[] = $existing;
                }
            }
        }
        
        // Process new uploads/URLs
        $maxCount = max(count($sliderFiles), count($sliderUrls), count($sliderAlts));
        for ($index = 0; $index < $maxCount; $index++) {
            $imageData = [];
            
            // Check if there's a file upload
            if (isset($sliderFiles[$index]) && $sliderFiles[$index] && $sliderFiles[$index]->isValid()) {
                $file = $sliderFiles[$index];
                $filename = time() . '_slider' . $index . '_' . $file->getClientOriginalName();
                $file->move(public_path('assets/AIcontrol_imgs/AllProjectImgs'), $filename);
                $imageData['url'] = 'assets/AIcontrol_imgs/AllProjectImgs/' . $filename;
            } 
            // Otherwise check if there's a URL
            elseif (!empty($sliderUrls[$index])) {
                $imageData['url'] = $sliderUrls[$index];
            }
            
            // Add alt text if provided
            if (!empty($sliderAlts[$index])) {
                $imageData['alt'] = $sliderAlts[$index];
            }
            
            // Only add if we have a URL
            if (!empty($imageData['url'])) {
                $sliderImages[] = $imageData;
            }
        }
        
        $validated['slider_images'] = $sliderImages;

        // Build detail_steps array from title and description arrays
        $detailSteps = [];
        if (isset($validated['detail_steps_title']) && isset($validated['detail_steps_description'])) {
            $titles = $validated['detail_steps_title'];
            $descriptions = $validated['detail_steps_description'];
            
            for ($i = 0; $i < count($titles); $i++) {
                if (!empty($titles[$i]) || !empty($descriptions[$i])) {
                    $detailSteps[] = [
                        'title' => $titles[$i] ?? '',
                        'description' => $descriptions[$i] ?? ''
                    ];
                }
            }
        }
        $validated['detail_steps'] = $detailSteps;

        // Remove the separate arrays
        unset($validated['detail_steps_title']);
        unset($validated['detail_steps_description']);

        $project->update($validated);

        return redirect()->route('admin.projects')->with('success', 'Dự án đã được cập nhật!');
    }

    /**
     * Delete a single image attached to a project (banner, thumbnail, og, gallery_image_1..3 or slider_images by index)
     * Expects POST payload: field (string) and optional index (int) when field is 'slider_images'
     */
    public function deleteProjectImage(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);

        $field = $request->input('field');

        // Allow slider_images (requires index) or the individual image fields
        $allowed = ['banner_image', 'thumbnail_image', 'og_image', 'gallery_image_1', 'gallery_image_2', 'gallery_image_3', 'slider_images'];

        if (!in_array($field, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid image field specified.'], 422);
        }

        // Handle slider_images removal by index
        if ($field === 'slider_images') {
            $index = $request->input('index');
            if (!is_numeric($index)) {
                return response()->json(['success' => false, 'message' => 'Index is required for slider_images.'], 422);
            }

            $slider = $project->slider_images;
            if (is_string($slider)) {
                $decoded = json_decode($slider, true);
                $slider = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
            }

            if (!is_array($slider) || !array_key_exists($index, $slider)) {
                return response()->json(['success' => false, 'message' => 'Slider image not found at that index.'], 404);
            }

            $item = $slider[$index];
            $url = is_array($item) ? ($item['url'] ?? null) : $item;

            // If remote URL, just remove from array
            if ($url && strpos($url, '://') !== false) {
                array_splice($slider, $index, 1);
                $project->slider_images = $slider;
                $project->save();
                return response()->json(['success' => true, 'message' => 'Slider image removed (remote URL).']);
            }

            if ($url) {
                $relative = ltrim($url, '/');
                $publicFile = public_path($relative);
                if (file_exists($publicFile)) {
                    try { @unlink($publicFile); } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to delete slider image file: ' . $publicFile . ' - ' . $e->getMessage());
                    }
                }
            }

            // Remove the item and save
            array_splice($slider, $index, 1);
            $project->slider_images = $slider;
            $project->save();

            return response()->json(['success' => true, 'message' => 'Slider image removed successfully.']);
        }

        // Single-field handling (banner/thumb/og/gallery)
        $current = $project->{$field};

        if (empty($current)) {
            return response()->json(['success' => false, 'message' => 'No image found for this field.'], 404);
        }

        // If remote URL, don't attempt to unlink file system; just clear the DB value
        if (strpos($current, '://') !== false) {
            $project->{$field} = null;
            $project->save();
            return response()->json(['success' => true, 'message' => 'Image cleared (remote URL).']);
        }

        $relative = ltrim($current, '/');
        $publicFile = public_path($relative);

        if (file_exists($publicFile)) {
            try {
                @unlink($publicFile);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete project image file: ' . $publicFile . ' - ' . $e->getMessage());
            }
        }

        $project->{$field} = null;
        $project->save();

        return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
    }

    /**
     * Delete a single image attached to a product (main image or gallery_images by index)
     * Expects POST payload: field (string) and optional index (int) when field is 'gallery_images'
     */
    public function deleteProductImage(Request $request, $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $field = $request->input('field');

        $allowed = ['image_url', 'gallery_images'];
        if (!in_array($field, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid image field specified.'], 422);
        }

        // Handle gallery_images removal by index
        if ($field === 'gallery_images') {
            $index = $request->input('index');
            if (!is_numeric($index)) {
                return response()->json(['success' => false, 'message' => 'Index is required for gallery_images.'], 422);
            }

            $gallery = $product->gallery_images;
            if (is_string($gallery)) {
                $decoded = json_decode($gallery, true);
                $gallery = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
            }

            if (!is_array($gallery) || !array_key_exists($index, $gallery)) {
                return response()->json(['success' => false, 'message' => 'Gallery image not found at that index.'], 404);
            }

            $item = $gallery[$index];
            $url = is_array($item) ? ($item['url'] ?? null) : $item;

            // If remote URL, just remove from array
            if ($url && strpos($url, '://') !== false) {
                array_splice($gallery, $index, 1);
                $product->gallery_images = $gallery;
                $product->save();
                return response()->json(['success' => true, 'message' => 'Gallery image removed (remote URL).']);
            }

            if ($url) {
                $relative = ltrim($url, '/');
                $publicFile = public_path($relative);
                if (file_exists($publicFile)) {
                    try { @unlink($publicFile); } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to delete gallery image file: ' . $publicFile . ' - ' . $e->getMessage());
                    }
                }
            }

            // Remove the item and save
            array_splice($gallery, $index, 1);
            $product->gallery_images = $gallery;
            $product->save();

            return response()->json(['success' => true, 'message' => 'Gallery image removed successfully.']);
        }

        // Single-field handling (main image)
        $current = $product->{$field};

        if (empty($current)) {
            return response()->json(['success' => false, 'message' => 'No image found for this field.'], 404);
        }

        if (strpos($current, '://') !== false) {
            $product->{$field} = null;
            $product->save();
            return response()->json(['success' => true, 'message' => 'Image cleared (remote URL).']);
        }

        $relative = ltrim($current, '/');
        $publicFile = public_path($relative);

        if (file_exists($publicFile)) {
            try { @unlink($publicFile); } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to delete product image file: ' . $publicFile . ' - ' . $e->getMessage());
            }
        }

        $product->{$field} = null;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
    }

    /**
     * Delete project
     */
    public function deleteProject($id)
    {
        try {
            $project = \App\Models\Project::findOrFail($id);

            // Collect image paths to remove (banner, thumbnail, og, gallery, slider)
            $imagePaths = [];

            if (!empty($project->banner_image)) $imagePaths[] = $project->banner_image;
            if (!empty($project->thumbnail_image)) $imagePaths[] = $project->thumbnail_image;
            if (!empty($project->og_image)) $imagePaths[] = $project->og_image;

            for ($i = 1; $i <=3; $i++) {
                $g = $project->{'gallery_image_' . $i};
                if (!empty($g)) $imagePaths[] = $g;
            }

            // Slider images may be stored as array or JSON string
            $slider = $project->slider_images;
            if (is_string($slider)) {
                $decoded = json_decode($slider, true);
                $slider = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
            }
            if (is_array($slider)) {
                foreach ($slider as $s) {
                    if (is_array($s) && isset($s['url'])) $imagePaths[] = $s['url'];
                    elseif (is_string($s)) $imagePaths[] = $s;
                }
            }

            // Normalize and unique
            $imagePaths = array_values(array_unique(array_filter($imagePaths)));

            foreach ($imagePaths as $path) {
                // Skip remote URLs
                if (strpos($path, '://') !== false) continue;

                $relative = ltrim($path, '/');
                $publicFile = public_path($relative);

                // Check if any other project uses this path (exclude current project)
                $otherUsage = \App\Models\Project::where('id', '!=', $project->id)
                    ->where(function($q) use ($relative) {
                        $q->where('banner_image', $relative)
                          ->orWhere('banner_image', '/' . $relative)
                          ->orWhere('thumbnail_image', $relative)
                          ->orWhere('thumbnail_image', '/' . $relative)
                          ->orWhere('og_image', $relative)
                          ->orWhere('og_image', '/' . $relative)
                          ->orWhere('gallery_image_1', $relative)
                          ->orWhere('gallery_image_1', '/' . $relative)
                          ->orWhere('gallery_image_2', $relative)
                          ->orWhere('gallery_image_2', '/' . $relative)
                          ->orWhere('gallery_image_3', $relative)
                          ->orWhere('gallery_image_3', '/' . $relative)
                          ->orWhere('slider_images', 'LIKE', '%"' . $relative . '"%')
                          ->orWhere('slider_images', 'LIKE', '%"/' . $relative . '"%');
                    })->exists();

                if (!$otherUsage && file_exists($publicFile)) {
                    try { @unlink($publicFile); } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed to delete project image file: ' . $publicFile . ' - ' . $e->getMessage());
                    }
                }
            }

            // Permanently delete the project row from DB
            $project->forceDelete();

            return redirect()->route('admin.projects')->with('success', 'Dự án đã được xóa!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Project deletion error: ' . $e->getMessage());
            return redirect()->route('admin.projects')->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }
}
