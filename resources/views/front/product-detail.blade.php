@extends('front.layouts.frontend', [
    'seo' => [
        'title' => $product->meta_title ?? $product->name . ' - AIControl Vietnam',
        'description' => $product->meta_description ?? Str::limit($product->short_description,160),
        'keywords' => $product->meta_keywords ?? $product->brand . ', ' . $product->name,
        'image' => $product->image_url 
            ? (str_starts_with($product->image_url,'http') ? $product->image_url : asset($product->image_url))
            : asset('assets/img/default-product.jpg'),
        'type' => 'product',
        'hreflangs' => [
            'en' => switch_locale_url('en'),
            'vi' => switch_locale_url('vi'),
        ]
    ]
])

{{-- Product Schema --}}
@push('head')
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": [
    "{{ $product->image_url ? (str_starts_with($product->image_url,'http') ? $product->image_url : asset($product->image_url)) : '' }}"
],
"description": "{{ $product->meta_description ?? $product->short_description }}",
"sku": "{{ $product->sku }}",
"brand": {
"@type": "Brand",
"name": "{{ $product->brand }}"
},
"offers": {
"@type": "Offer",
"url": "{{ route(app()->getLocale().'.product.show',$product->slug) }}",
"priceCurrency": "{{ $product->currency }}",
"price": "{{ $product->sale_price ?? $product->price }}",
"availability": "https://schema.org/{{ $product->stock_status == 'in_stock' ? 'InStock' : 'OutOfStock' }}"
}
}
</script>
@endpush


@section('content')

    <!-- ==========================================
        BREADCRUMB
        ========================================== -->
    <div class="pd-breadcrumb">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="pd-breadcrumb-list">
                <li><a href="index.html">Home</a></li>
                <li><i class="bi bi-chevron-right"></i></li>
                <li><a href="products.html">Shop</a></li>
                <li><i class="bi bi-chevron-right"></i></li>
                <li class="active">Leather Jacket</li>
            </ol>
        </nav>
    </div>
    </div>


    <!-- ==========================================
        PRODUCT DETAIL SECTION
        ========================================== -->
    <section id="productDetail" class="pd-section section-pad">
    <div class="container">
        <div class="row g-5 g-lg-6">

        <!-- ── LEFT: Image Gallery ── -->
        <div class="col-lg-7 fade-up">
            <div class="pd-gallery">
            <div class="pd-thumbs" id="pdThumbs">
                @if($product->image_url)
                    <button class="pd-thumb active" data-img="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" aria-label="View image 1">
                        <img src="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" alt="{{ $product->image_alt ?? $product->name }}" />
                    </button>
                @endif
                @if($product->gallery_images)
                    @foreach($product->gallery_images as $image)
                        @php
                            $imageUrl = is_array($image) ? ($image['url'] ?? '') : $image;
                            $imageAlt = is_array($image) ? ($image['alt'] ?? $product->image_alt ?? $product->name) : ($product->image_alt ?? $product->name);
                        @endphp
                        @if(!empty($imageUrl))
                            <button class="pd-thumb" data-img="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl) }}" aria-label="View image {{ $loop->iteration + 1 }}">
                                <img src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl) }}" alt="{{ $imageAlt }}" />
                            </button>
                        @endif
                    @endforeach
                @endif
            </div>

            <!-- Main Image -->
                <div class="pd-main-img-wrap @if(!$product->gallery_images || count($product->gallery_images) == 0) w-100 @else flex-grow-1 @endif">
                    @if($product->image_url)
                        <img src="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" 
                            alt="{{ $product->image_alt ?? $product->name }}" 
                            id="mainImage">
                    @else
                        <div class="placeholder">
                            <div>
                                <i class="fa fa-image"></i>
                                <p class="mt-3">the img not avilable</p>
                            </div>
                        </div>
                    @endif
                    <!-- Badge -->
                    <span class="pd-img-badge">New</span>
                    <!-- Zoom hint -->
                    <div class="pd-zoom-hint"><i class="bi bi-zoom-in"></i></div>
                </div>
            </div>
        </div>

        <!-- ── RIGHT: Product Info ── -->
        <div class="col-lg-5 fade-up">
            <div class="pd-info">

            <!-- Brand + Wishlist -->
            <div class="pd-info-top">
                <span class="pd-brand">{{ $product->brand }}</span>
                <button class="pd-wishlist-btn" aria-label="Add to wishlist" id="pdWishlistBtn">
                <i class="bi bi-heart"></i>
                </button>
            </div>

            <!-- Product name -->
            <h1 class="pd-title">{{ $product->name }}</h1>

            <!-- Rating -->
            <div class="pd-rating">
                @php
                $status = [
                    'in_stock' => [
                        'label' => 'In Stock',
                        'icon' => 'bi-check-circle-fill',
                        'class' => 'stock-in'
                    ],
                    'on_backorder' => [
                        'label' => 'On Backorder',
                        'icon' => 'bi-clock-fill',
                        'class' => 'stock-backorder'
                    ],
                    'out_of_stock' => [
                        'label' => 'Out of Stock',
                        'icon' => 'bi-x-circle-fill',
                        'class' => 'stock-out'
                    ],
                ][$product->stock_status] ?? [
                    'label' => 'Unknown',
                    'icon' => 'bi-question-circle-fill',
                    'class' => 'stock-unknown'
                ];
                @endphp
                <span class="pd-review-count">(128 Reviews)</span>
                <span class="pd-stock {{ $status['class'] }}">
                    <i class="bi {{ $status['icon'] }}"></i>
                    {{ $status['label'] }}
                </span>
            </div>

            <!-- Price -->
            <div class="pd-price-row">
                <span class="pd-price">5,200,000 VND</span>
                <span class="pd-old-price">6,500,000 VND</span>
                <span class="pd-discount-badge">–20%</span>
            </div>

            <!-- Flash Sale Countdown -->
            <div class="pd-flash-sale">
                <span class="pd-flash-label"><i class="bi bi-lightning-charge-fill"></i> Flash Sale ends in:</span>
                <div class="pd-flash-countdown">
                <div class="pd-cd-unit">
                    <span class="pd-cd-num" id="pd-cd-hours">00</span>
                    <span class="pd-cd-text">Hrs</span>
                </div>
                <span class="pd-cd-sep">:</span>
                <div class="pd-cd-unit">
                    <span class="pd-cd-num" id="pd-cd-mins">05</span>
                    <span class="pd-cd-text">Min</span>
                </div>
                <span class="pd-cd-sep">:</span>
                <div class="pd-cd-unit">
                    <span class="pd-cd-num" id="pd-cd-secs">30</span>
                    <span class="pd-cd-text">Sec</span>
                </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="pd-divider"></div>

            <!-- Description -->
            <p class="pd-description">
                A timeless statement piece crafted from premium full-grain leather.
                Slim cut with a refined lapel and polished hardware — engineered for the
                man who commands every room he enters. Pairs effortlessly with dark denim
                or tailored trousers.
            </p>

            <!-- Size Selector -->
            <div class="pd-option-group">
                <div class="pd-option-label">
                Size: <span class="pd-selected-size" id="pdSelectedSize">M</span>
                <a href="#" class="pd-size-guide">Size Guide <i class="bi bi-rulers"></i></a>
                </div>
                <div class="pd-size-grid" id="pdSizeGrid">
                <button class="pd-size-btn">S</button>
                <button class="pd-size-btn active">M</button>
                <button class="pd-size-btn">L</button>
                <button class="pd-size-btn">XL</button>
                <button class="pd-size-btn">XXL</button>
                </div>
            </div>

            <!-- Color Selector -->
            <div class="pd-option-group">
                <div class="pd-option-label">Color: <span class="pd-selected-color" id="pdSelectedColor">Black</span></div>
                <div class="pd-color-grid" id="pdColorGrid">
                <button class="pd-color-swatch active" style="background:#1a1a1a;" data-color="Black"
                    aria-label="Black"></button>
                <button class="pd-color-swatch" style="background:#8b6c4f;" data-color="Camel"
                    aria-label="Camel"></button>
                <button class="pd-color-swatch" style="background:#c8c8c8; border: 1px solid #aaa;" data-color="Silver"
                    aria-label="Silver"></button>
                </div>
            </div>

            <!-- Divider -->
            <div class="pd-divider"></div>

            <!-- Quantity + Add to Cart -->
            <div class="pd-add-row">
                <!-- Qty Stepper -->
                <div class="pd-qty-wrap">
                <button class="pd-qty-btn" id="pdQtyMinus" aria-label="Decrease quantity">
                    <i class="bi bi-dash"></i>
                </button>
                <input type="number" class="pd-qty-input" id="pdQtyInput" value="1" min="1" max="99"
                    aria-label="Quantity" readonly />
                <button class="pd-qty-btn" id="pdQtyPlus" aria-label="Increase quantity">
                    <i class="bi bi-plus"></i>
                </button>
                </div>

                <!-- Add to Cart -->
                <button class="btn-dark-custom pd-add-to-cart" id="pdAddToCart">
                <i class="bi bi-bag-plus me-2"></i>Add to Cart
                </button>

                <!-- Wishlist -->
                <button class="pd-wishlist-icon-btn" id="pdWishlistIcon" aria-label="Add to wishlist">
                <i class="bi bi-heart"></i>
                </button>
            </div>

            <!-- Trust Badges -->
            <div class="pd-trust">
                <div class="pd-trust-item">
                <i class="bi bi-truck"></i>
                <div>
                    <span class="pd-trust-title">Free Shipping</span>
                    <span class="pd-trust-sub">Orders over 2M VND</span>
                </div>
                </div>
                <div class="pd-trust-item">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <span class="pd-trust-title">Free Returns</span>
                    <span class="pd-trust-sub">Within 30 days</span>
                </div>
                </div>
                <div class="pd-trust-item">
                <i class="bi bi-shield-check"></i>
                <div>
                    <span class="pd-trust-title">Secure Payment</span>
                    <span class="pd-trust-sub">100% protected</span>
                </div>
                </div>
            </div>

            <!-- Payment icons -->
            <div class="pd-payments">
                <span class="pd-payments-label">We Accept:</span>
                <i class="bi bi-credit-card-2-front" title="Visa"></i>
                <i class="bi bi-paypal" title="PayPal"></i>
                <i class="bi bi-apple" title="Apple Pay"></i>
                <i class="bi bi-google" title="Google Pay"></i>
            </div>

            </div><!-- /.pd-info -->
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container -->
    </section>

@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const thumbs = document.querySelectorAll(".pd-thumb");
    const mainImage = document.getElementById("mainImage");
    thumbs.forEach(thumb => {
        thumb.addEventListener("click", function () {
            const newImg = this.getAttribute("data-img");
            if (mainImage && newImg) {
                mainImage.src = newImg;
            }
            thumbs.forEach(t => t.classList.remove("active"));
            this.classList.add("active");

        });
    });

});

</script>
@endpush