@extends('front.layouts.frontend', [
'seo' => [
'title' => __('homev2.seo.title'),
'description' => __('homev2.seo.description'),
'keywords' => __('homev2.seo.keywords'),
'image' => asset('images/tempSpace/fas (32).jpg'),
'type' => 'website',
'hreflangs' => [
'en' => switch_locale_url('en'),
'vi' => switch_locale_url('vi'),
]
]
])


@section('content')

@push('head')
@if(!empty($canonicalUrl))
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endif
@endpush

{{-- ==========================================
         SHOP HERO
    ========================================== --}}

<section class="shop-hero position-relative overflow-hidden">
    <img src="{{ asset('images/tempSpace/fas (30).jpg') }}" alt="Shop Hero Background" class="shop-hero-bg w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 0; filter: brightness(0.6);">
    <div class="container position-relative h-100 d-flex align-items-center" style="z-index: 2;">
        <div class="w-100 d-flex justify-content-between">
            <div>
                <p class="font-xs text-white fw-bold mb-0 letter-wide mt-5 pt-4">SPRING COLLECTION</p>
                <p class="font-xs text-white-50 mb-4">Superb Quality / Made in the City, Authentic Store.</p>
                <h1 class="shop-hero-title text-white fw-black" style="font-size: clamp(3rem, 8vw, 8rem); line-height: 0.9; letter-spacing: 0.05em; text-transform: uppercase;">
                    <span class="d-block text-white" style="font-size: 1.5rem; letter-spacing: 0.15em; font-weight: var(--fw-medium); margin-bottom:-0.5rem">ASPIRING to be</span>
                    AUTHENTIC.
                </h1>
            </div>
        </div>
    </div>
</section>

{{-- ==========================================
         TITLE & BREADCRUMBS
    ========================================== --}}
<div class="shop-header text-center py-5 mt-3">
    <h2 class="fw-bold fs-3 mb-2">Summer collection</h2>
    <p class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.25em;">Home <span class="mx-1">&gt;</span> Shop</p>
</div>

<!-- MAIN SHOP CONTENT -->
<div class="container pb-5 mb-5">
    <div class="row gx-lg-5">
        <!-- SIDEBAR (Offcanvas on Mobile) -->
        <aside class="col-lg-2 shop-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="shopSidebar" aria-labelledby="shopSidebarLabel">
            <div class="offcanvas-header d-lg-none border-bottom mb-3 px-4 pt-4">
                <h5 class="offcanvas-title fw-bold" id="shopSidebarLabel">Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#shopSidebar" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body flex-column px-4 px-lg-0 pb-4">
                <h5 class="fw-bold mb-4 fs-6 d-none d-lg-block">Filters</h5>

                <!-- Size (dynamic from categories) - rendered as checkboxes but styled as size buttons -->
                @php
                    $sizeCategory = null;
                    $selectedSlugs = array_filter(array_map('trim', explode(',', request()->get('category', ''))));
                    if (isset($categories) && $categories instanceof \Illuminate\Support\Collection) {
                        $sizeCategory = $categories->firstWhere('slug', 'size') ?: $categories->firstWhere('name', 'Size');
                    }
                @endphp

                @if($sizeCategory && $sizeCategory->children && $sizeCategory->children->count())
                <div class="filter-group mb-4">
                    <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">{{ $sizeCategory->name }}</h6>
                    <div class="d-flex flex-wrap gap-2 size-filters">
                        @foreach($sizeCategory->children as $child)
                            @php
                                $isChecked = in_array($child->slug, $selectedSlugs);
                            @endphp
                            <div>
                                <input class="category-checkbox visually-hidden" type="checkbox" id="size-{{ $child->id }}" data-slug="{{ $child->slug }}" {{ $isChecked ? 'checked' : '' }}>
                                <label for="size-{{ $child->id }}" class="size-btn{{ $isChecked ? ' active' : '' }}">{{ $child->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Color -->
                <div class="filter-group mb-4">
                    <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Colors</h6>
                    <div class="d-flex flex-wrap gap-2 color-filters">
                        <button class="color-btn" style="background-color: #2e2e2e;"></button>
                        <button class="color-btn" style="background-color: #8b6c4f;"></button>
                        <button class="color-btn" style="background-color: #d4b896;"></button>
                        <button class="color-btn" style="background-color: #e0ddd9;"></button>
                        <button class="color-btn" style="background-color: #ffffff; border: 1px solid #ccc;"></button>
                        <button class="color-btn" style="background-color: #1a1a1a;"></button>
                    </div>
                </div>

                <!-- Categories (render root categories as labels; children as checkboxes) -->
                <div class="filter-group mb-4">
                    <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Categories</h6>
                    @php
                        $selectedSlugs = array_filter(array_map('trim', explode(',', request()->get('category', ''))));
                    @endphp

                    @if(isset($categories) && $categories->count())
                        @foreach($categories as $parent)
                            @if(isset($sizeCategory) && $sizeCategory && $parent->id == $sizeCategory->id)
                                @continue
                            @endif
                            <div class="mb-2">
                                <div class="fw-bold">{{ $parent->name }}</div>
                                @if($parent->children && $parent->children->count())
                                    @foreach($parent->children as $child)
                                        <div class="form-check filter-check">
                                            <input class="form-check-input category-checkbox" type="checkbox" id="cat-{{ $child->id }}" data-slug="{{ $child->slug }}" {{ in_array($child->slug, $selectedSlugs) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat-{{ $child->id }}">{{ $child->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted">No categories found.</div>
                    @endif
                </div>

                <!-- Tags -->
                <div class="filter-group mb-4">
                    <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Tags</h6>
                    <div class="d-flex flex-wrap gap-2 tag-filters">
                        <button class="sidebar-tag">Fashion</button>
                        <button class="sidebar-tag">Casual</button>
                        <button class="sidebar-tag active">Holiday</button>
                        <button class="sidebar-tag">Jackets</button>
                        <button class="sidebar-tag">Dresses</button>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions mt-5 d-flex flex-column gap-2">
                    <button type="button" class="btn-dark-custom w-100 text-center" id="applyFiltersBtn">Apply Filters</button>
                    <button type="button" class="btn-outline-custom w-100 text-center" id="clearFiltersBtn">Clear All</button>
                </div>
            </div>
        </aside>

        <!-- PRODUCT GRID -->
        <main class="col-lg-10">
            <!-- Mobile Filter Toggle Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom d-lg-none">
                <span class="font-xs fw-bold text-uppercase letter-wide text-muted">{{ $products->total() }} Results</span>
                <button class="btn btn-outline-dark btn-sm rounded-0 text-uppercase letter-wide fw-bold font-xs px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopSidebar" aria-controls="shopSidebar">
                    <i class="bi bi-sliders me-2"></i> Filter
                </button>
            </div>

            <div class="row row-cols-2 row-cols-md-3 g-3 g-lg-4 gx-lg-5">
                @forelse($products as $product)
                <div class="col">
                    <a href="{{ route(current_locale() . '.product.show', $product->slug) }}" class="text-decoration-none text-dark d-block shop-product-card">
                        @include('front.components.product-card', [
                        'image' => $product->image_url ? (str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url)) : asset('assets/img/product/default.jpg'),
                        'name' => $product->name,
                        'price' => ($product->sale_price && $product->sale_price < $product->price) ? number_format($product->sale_price, 0, ",", ".") . "đ" : number_format($product->price, 0, ",", ".") . "đ",
                            'oldPrice' => ($product->sale_price && $product->sale_price < $product->price) ? number_format($product->price, 0, ",", ".") . "đ" : null,
                                'tag' => $product->featured ? 'badge-featured' : ($product->sale_price && $product->sale_price < $product->price ? 'badge-sale' : null),
                                    'tagLabel' => $product->featured ? __('shop.labels.badge_featured') : ($product->sale_price && $product->sale_price < $product->price ? __('shop.labels.badge_sale') : null)
                                        ])
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fal fa-box-open fs-1 text-muted mb-3"></i>
                    <p class="text-muted">{{ __('shop.labels.no_products_message') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5 pt-3 mb-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </main>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function buildSelectedSlugs() {
        return Array.from(document.querySelectorAll('.category-checkbox:checked')).map(function (el) { return el.dataset.slug; });
    }

    function applyCategoryFilters() {
        var checked = buildSelectedSlugs();
        var params = new URLSearchParams(window.location.search);
        if (checked.length) {
            params.set('category', checked.join(','));
        } else {
            params.delete('category');
        }
        var newSearch = params.toString();
        var newUrl = window.location.pathname + (newSearch ? ('?' + newSearch) : '');
        window.location.href = newUrl;
    }

    // Apply button: collect checked category slugs and reload with param
    var applyBtn = document.getElementById('applyFiltersBtn');
    if (applyBtn) {
        applyBtn.addEventListener('click', function () {
            applyCategoryFilters();
        });
    }

    // Clear All: uncheck category boxes and remove category param
    var clearBtn = document.getElementById('clearFiltersBtn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            document.querySelectorAll('.category-checkbox').forEach(function (el) { el.checked = false; });
            var params = new URLSearchParams(window.location.search);
            params.delete('category');
            var newSearch = params.toString();
            var newUrl = window.location.pathname + (newSearch ? ('?' + newSearch) : '');
            window.location.href = newUrl;
        });
    }
});
</script>
@endpush

@endsection