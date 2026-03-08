<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display all products (shop page)
     */
    public function index(Request $request)
    {
        // Debug logging
        Log::info('Shop filter request', [
            'brand' => $request->brand,
            'category' => $request->category,
            'q' => $request->q,
            'sort' => $request->sort,
            'all_params' => $request->all()
        ]);

        // Validate input
        $request->validate([
            'brand' => 'nullable|string',
            'category' => 'nullable|string',
            'q' => 'nullable|string|max:255',
            'sort' => 'nullable|in:newest,popular,price-low,price-high'
        ]);

        $query = Product::published()->with('categories');

        // Filter by brands (convert comma-separated to array)
        if ($request->has('brand') && !empty($request->brand)) {
            $brandArray = explode(',', $request->brand);
            $brandArray = array_filter($brandArray); // Remove empty values
            if (!empty($brandArray)) {
                Log::info('Filtering by brands', ['brands' => $brandArray]);
                $query->whereIn('brand', $brandArray);
            }
        }

        // Filter by categories using slugs (convert comma-separated to array)
        if ($request->has('category') && !empty($request->category)) {
            $categorySlugs = explode(',', $request->category);
            // keep as slugs (strings) and remove empty values
            $categorySlugs = array_filter(array_map('trim', $categorySlugs));

            if (!empty($categorySlugs)) {
                // Resolve slugs to IDs, and include direct children IDs so parent selection includes child products
                $categoryIds = [];
                $categories = \App\Models\Category::whereIn('slug', $categorySlugs)->with('children')->get();

                foreach ($categories as $cat) {
                    $categoryIds[] = $cat->id;
                    // If category has children, include them as well (two-level tree expected)
                    if ($cat->children && $cat->children->count() > 0) {
                        $categoryIds = array_merge($categoryIds, $cat->children->pluck('id')->toArray());
                    }
                }

                // Deduplicate and ensure integers
                $categoryIds = array_values(array_unique(array_map('intval', $categoryIds)));

                if (!empty($categoryIds)) {
                    Log::info('Filtering by category ids (including children)', ['category_slugs' => $categorySlugs, 'category_ids' => $categoryIds]);
                    $query->whereHas('categories', function($q) use ($categoryIds) {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                }
            }
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('sku', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('short_description', 'LIKE', "%{$keyword}%")
                  ->orWhere('tags', 'LIKE', "%{$keyword}%")
                  ->orWhere('brand', 'LIKE', "%{$keyword}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Get all active brands for the filter sidebar
        $brands = \App\Models\Brand::where('status', 1)
            ->orderBy('name')
            ->get();

        // Get all active categories with product counts (hierarchical)
        $categories = \App\Models\Category::whereNull('parent_id')
            ->where('status', 1)
            ->with(['children' => function($query) {
                $query->where('status', 1)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('front.productPageV2', compact('products', 'brands', 'categories'));
    }

    /**
     * Display single product detail
     */
    public function show($slug)
    {
        try {
            $product = Product::where('slug', $slug)
                ->published()
                ->firstOrFail();

            // Increment view count
            $product->increment('view_count');

            // Get related products (same brand) - optimized query
            $relatedProducts = Product::published()
                ->where('brand', $product->brand)
                ->where('id', '!=', $product->id)
                ->select('id', 'name', 'slug', 'brand', 'image_url', 'price', 'sale_price', 'stock_status')
                ->limit(4)
                ->get();

            return view('front.product-detail', compact('product', 'relatedProducts'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Product not found - return 404
            abort(404, 'Sản phẩm không tìm thấy');
        } catch (\Exception $e) {
            // Log error and show user-friendly message
            Log::error('Error loading product: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi tải sản phẩm. Vui lòng thử lại.');
        }
    }

    /**
     * Display products by brand
     */
    public function byBrand($brand)
    {
        $products = Product::published()
            ->byBrand($brand)
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get all active brands for the filter sidebar
        $brands = \App\Models\Brand::where('status', 1)
            ->orderBy('name')
            ->get();

        // Get all active categories with product counts (hierarchical)
        $categories = \App\Models\Category::whereNull('parent_id')
            ->where('status', 1)
            ->with(['children' => function($query) {
                $query->where('status', 1)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('front.productPageV2', compact('products', 'brand', 'brands', 'categories'));
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        // Validate input
        $request->validate([
            'q' => 'required|string|max:255'
        ]);

        $keyword = $request->input('q');

        // Sanitize keyword
        if ($keyword) {
            $keyword = trim($keyword);
            $keyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);
        }

        $products = Product::published()
            ->where(function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%")
                      ->orWhere('short_description', 'LIKE', "%{$keyword}%")
                      ->orWhere('tags', 'LIKE', "%{$keyword}%")
                      ->orWhere('brand', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('view_count', 'desc')
            ->paginate(12);

        // Get all active brands for the filter sidebar
        $brands = \App\Models\Brand::where('status', 1)
            ->orderBy('name')
            ->get();

        // Get all active categories with product counts (hierarchical)
        $categories = \App\Models\Category::whereNull('parent_id')
            ->where('status', 1)
            ->with(['children' => function($query) {
                $query->where('status', 1)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('front.productPageV2', compact('products', 'keyword', 'brands', 'categories'));
    }

    /**
     * Autocomplete search API
     */
    public function autocomplete(Request $request)
    {
        // Validate input
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $keyword = $request->input('q');
        
        // Validate input
        if (empty($keyword) || strlen($keyword) < 2) {
            return response()->json([
                'products' => [],
                'total' => 0,
                'hasMore' => false
            ]);
        }

        // Sanitize keyword - remove special SQL characters
        $keyword = trim($keyword);
        $keyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);

        $products = Product::published()
            ->where(function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('sku', 'LIKE', "%{$keyword}%")
                    ->orWhere('brand', 'LIKE', "%{$keyword}%");
            })
            ->select('id', 'name', 'sku', 'slug', 'brand', 'image_url', 'price')
            ->limit(7)
            ->get()
            ->map(function ($product) {
                $product->url = route(current_locale() . '.product.show', $product->slug);
                return $product;
            });




        $total = Product::published()
            ->where(function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('sku', 'LIKE', "%{$keyword}%")
                      ->orWhere('brand', 'LIKE', "%{$keyword}%");
            })
            ->distinct()
            ->count('id');

            
            $products = $products->map(function ($product) {
            return [
                'id'        => $product->id,
                'name'      => $product->name,
                'sku'       => $product->sku,
                'brand'     => $product->brand,
                'image_url' => $product->image_url
                    ? (str_starts_with($product->image_url, 'http')
                        ? $product->image_url
                        : asset($product->image_url))
                    : asset('assets/img/no-image.png'),
                'price'     => $product->price,
                'url'       => route(app()->getLocale() . '.product.show', $product->slug),
            ];
        });

        return response()->json([
            'products' => $products,
            'total' => $total,
            'hasMore' => $total > 7
        ]);

    }
}
