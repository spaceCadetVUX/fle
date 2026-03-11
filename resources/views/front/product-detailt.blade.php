<!doctype html>
<html class="no-js" lang="{{ current_locale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO Meta Tags --}}
    <title>{{ $product->meta_title ?? $product->name . ' - AIControl Vietnam' }}</title>
    <meta name="description" content="{{ $product->meta_description ?? Str::limit($product->short_description, 160) }}">
    <meta name="keywords" content="{{ $product->meta_keywords ?? $product->brand . ', ' . $product->name }}">
    
    
    {{-- Robots Meta Tag - Controls Search Engine Indexing --}}
    @if(!$product->indexable)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $product->canonical_url ?? route('product.show', $product->slug) }}">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $product->og_title ?? $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->og_description ?? $product->meta_description ?? $product->short_description }}">
    <meta property="og:image" content="{{ $product->image_url ? (str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url)) : asset('assets/img/default-product.jpg') }}">
    <meta property="og:url" content="{{ route(app()->getLocale() . '.product.show', $product->slug) }}">
    <meta property="og:site_name" content="AIControl Vietnam">
    <meta property="product:price:amount" content="{{ $product->sale_price ?? $product->price }}">
    <meta property="product:price:currency" content="{{ $product->currency }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta name="twitter:description" content="{{ $product->meta_description ?? $product->short_description }}">
    <meta name="twitter:image" content="{{ $product->image_url ? (str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url)) : asset('assets/img/default-product.jpg') }}">
    
    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $product->name }}",
      "image": [
        "{{ $product->image_url ? (str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url)) : '' }}"
        @if($product->gallery_images)
        @foreach($product->gallery_images as $image)
        @php
            $imageUrl = is_array($image) ? ($image['url'] ?? '') : $image;
        @endphp
        @if(!empty($imageUrl))
        ,"{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl) }}"
        @endif
        @endforeach
        @endif
      ],
      "description": "{{ $product->meta_description ?? $product->short_description }}",
      "sku": "{{ $product->sku }}",
      "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand }}"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ route(app()->getLocale() . '.product.show', $product->slug) }}",
        "priceCurrency": "{{ $product->currency }}",
        "price": "{{ $product->sale_price ?? $product->price }}",
        "availability": "https://schema.org/{{ $product->stock_status == 'in_stock' ? 'InStock' : 'OutOfStock' }}",
        "seller": {
          "@type": "Organization",
          "name": "AIControl Vietnam"
        }
      }
      @if($product->rating)
      ,"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->rating }}",
        "bestRating": "5",
        "worstRating": "1",
        "ratingCount": "{{ $product->review_count ?? 1 }}"
      }
      @endif
    }
    </script>
    {{-- Breadcrumb Schema --}}
    

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "{{ __('Trang chủ') }}",
      "item": "{{ route(current_locale() . '.index') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ __('Sản phẩm') }}",
      "item": "{{ route(current_locale() . '.product.shop') }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $product->brand }}",
      "item": "{{ route(current_locale() . '.product.brand', $product->brand) }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ $product->name }}"
    }
  ]
}
</script>



    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/AIcontrol_imgs/small_logo.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">



</head>

<body class="" data-bg-color="#fff">

    <!-- Magic cursor -->
    <div id="magic-cursor" class="cursor-bg-red">
        <div id="ball"></div>
    </div>

    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Back to top -->
    <div class="back-to-top-wrapper">
        <button id="back_to_top" type="button" class="back-to-top-btn">
            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
    </div>

    <!-- Offcanvas & Header -->
    @include('front.includes.offcanvas')
    @include('front.includes.header')
    @include('front.includes.popup')



    <!-- contact_modal -->
    <div id="callPopup" class="popup-overlay">
        <div class="popup-content">
            <h3>Chọn phương thức liên hệ</h3>
            <button id="callOption">Gọi 0918918755</button>
            <button id="zaloOption">Liên hệ qua Zalo</button>
            <span class="popup-close">&times;</span>
        </div>
    </div>

    <!-- search area start -->
    <div class="tp-search-area p-relative">
        <div class="tp-search-close">
            <button class="tp-search-close-btn">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="path-1" d="M11 1L1 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path class="path-2" d="M1 1L11 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="container container-1230">
            <div class="row">
                <div class="tp-search-wrapper">
                    <div class="col-lg-8">
                        <div class="tp-search-content">
                            <div class="search p-relative">
                                <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm...">
                                <button class="tp-search-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M18.0508 18.05L23.0009 23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.8004 10.9C20.8004 5.43237 16.3679 1 10.9002 1C5.43246 1 1 5.43237 1 10.9C1 16.3676 5.43246 20.7999 10.9002 20.7999C16.3679 20.7999 20.8004 16.3676 20.8004 10.9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- search area end -->

 

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                
                <!-- Breadcrumb -->shop-sidebar
                <section class="breadcrumb__area pt-120 pb-20" style="background: #ffffff;">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route(current_locale() . '.index') }}">Trang chủ</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route(current_locale() . '.product.shop') }}">Sản phẩm</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route(current_locale() . '.product.brand', $product->brand) }}">{{ $product->brand }}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Product Detail -->
                <section class="product-detail">
                    <div class="container">
                        <div class="row">
                            
                            <!-- Product Images -->
                            <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                                <div class="product-images">
                                    <!-- Main Image -->
                                    <div class="main-image-container @if(!$product->gallery_images || count($product->gallery_images) == 0) w-100 @else flex-grow-1 @endif">
                                        @if($product->image_url)
                                            <img src="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" 
                                                 alt="{{ $product->image_alt ?? $product->name }}" 
                                                 id="mainImage">
                                        @else
                                            <div class="placeholder">
                                                <div>
                                                    <i class="fa fa-image"></i>
                                                    <p class="mt-3">Chưa có hình ảnh</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Gallery Thumbnails (Right Side) - Only show if gallery images exist -->
                                    @if($product->gallery_images && count($product->gallery_images) > 0)
                                    <div class="gallery-thumbnails">
                                        <!-- Main image as first thumbnail -->
                                        @if($product->image_url)
                                        <div class="gallery-item active">
                                            <img src="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url) }}" 
                                                 alt="{{ $product->image_alt ?? $product->name }}" 
                                                 onclick="changeMainImage(this)">
                                        </div>
                                        @endif
                                        
                                        <!-- Other gallery images -->
                                        @foreach($product->gallery_images as $image)
                                        @php
                                            $imageUrl = is_array($image) ? ($image['url'] ?? '') : $image;
                                            $imageAlt = is_array($image) ? ($image['alt'] ?? $product->image_alt ?? $product->name) : ($product->image_alt ?? $product->name);
                                        @endphp
                                        @if(!empty($imageUrl))
                                        <div class="gallery-item">
                                            <img src="{{ str_starts_with($imageUrl, 'http') ? $imageUrl : asset($imageUrl) }}" 
                                                 alt="{{ $imageAlt }}" 
                                                 onclick="changeMainImage(this)">
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="col-lg-6 col-12">
                                <div class="product-info">
                                    
                                    <!-- Brand Badge -->
                                    <div class="mb-3">
                                        <span class="badge bg-primary">{{ $product->brand }}</span>
                                        @if($product->featured)
                                            <span class="badge bg-danger ms-2">Nổi bật</span>
                                        @endif
                                        @if($product->is_new)
                                            <span class="badge bg-success ms-2">Mới</span>
                                        @endif
                                        @if($product->is_bestseller)
                                            <span class="badge bg-warning ms-2">Bán chạy</span>
                                        @endif
                                    </div>

                                    <!-- Product Name -->
                                    <h1 class="product-title">
                                        {{ $product->name }}
                                    </h1>

                                    <!-- SKU -->
                                    <p class="text-muted mb-3">SKU: {{ $product->sku }}</p>

                                    <!-- Rating -->
                                    @if($product->rating)
                                    <div class="rating mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $product->rating)
                                                <i class="fa fa-star text-warning"></i>
                                            @else
                                                <i class="fa fa-star-o text-muted"></i>
                                            @endif
                                        @endfor
                                        {{-- <span class="ms-2">({{ $product->review_count ?? 0 }} đánh giá)</span> --}}
                                    </div>
                                    @endif

                                    <!-- Price -->
                                    <div class="product-price">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="old-price">
                                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                                </span>
                                                <span class="new-price">
                                                    {{ number_format($product->sale_price, 0, ',', '.') }}đ
                                                </span>
                                                <span class="badge bg-danger">
                                                    Giảm {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                                </span>
                                            </div>
                                        @else
                                            <span class="price">
                                                {{ number_format($product->price, 0, ',', '.') }}đ
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Short Description -->
                                    @if($product->short_description)
                                    <div class="short-description mb-4">
                                        <p>
                                            {{ $product->short_description }}
                                        </p>
                                    </div>
                                    @endif

                                    <!-- Stock Status -->
                                    <div class="stock-status">
                                        <strong>Tình trạng: </strong>
                                        @if($product->stock_status == 'in_stock')
                                            <span class="text-success"><i class="fa fa-check-circle"></i> Còn hàng</span>
                                        @elseif($product->stock_status == 'out_of_stock')
                                            <span class="text-danger"><i class="fa fa-times-circle"></i> Hết hàng</span>
                                        @else
                                            <span class="text-warning"><i class="fa fa-clock"></i> Đặt trước</span>
                                        @endif
                                    </div>

                                    <!-- Product Attributes -->
                                    <div class="product-attributes mb-4">
                                        @if($product->function_category)
                                        <p><strong>Danh mục chức năng:</strong> {{ $product->function_category }}</p>
                                        @endif
                                        @if($product->catalog)
                                        <p><strong>Danh mục:</strong> {{ $product->catalog }}</p>
                                        @endif
                                        @if($product->warranty_period)
                                        <p><strong>Bảo hành:</strong> {{ $product->warranty_period }}</p>
                                        @endif
                                        @if($product->manufacturer_country)
                                        <p><strong>Nước sản xuất:</strong> {{ $product->manufacturer_country }}</p>
                                        @endif
                                        @if($product->origin)
                                        <p><strong>Xuất xứ:</strong> {{ $product->origin }}</p>
                                        @endif
                                        @if($product->color)
                                        <p><strong>Màu sắc:</strong> {{ $product->color }}</p>
                                        @endif
                                        @if($product->material)
                                        <p><strong>Chất liệu:</strong> {{ $product->material }}</p>
                                        @endif
                                        @if($product->weight)
                                        <p><strong>Trọng lượng:</strong> {{ $product->weight }}</p>
                                        @endif
                                        @if($product->dimensions)
                                        <p><strong>Kích thước:</strong> {{ $product->dimensions }}</p>
                                        @endif
                                        @if($product->min_order_quantity && $product->min_order_quantity > 1)
                                        <p><strong>Số lượng đặt tối thiểu:</strong> {{ $product->min_order_quantity }}</p>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="action-buttons">
                                        <a href="tel:0918918755" class="btn">
                                            <i class="fa fa-phone"></i> <span class="d-none d-sm-inline">Gọi ngay: </span>0918918755
                                        </a>
                                        {{-- <a href="{{ route(current_locale() . '.contact') }}" class="btn btn-outline-primary">
                                            <i class="fa fa-envelope"></i> Liên hệ tư vấn
                                        </a> --}}
                                    </div>

                                    <!-- Categories & Tags -->
                                    <div class="product-meta">
                                        @if($product->categories)
                                        <p>
                                            <strong>Danh mục:</strong> 
                                            @foreach($product->categories as $category)
                                                <span class="badge bg-secondary">{{ $category }}</span>
                                            @endforeach
                                        </p>
                                        @endif
                                        
                                        @if($product->tags)
                                        <p>
                                            <strong>Tags:</strong> 
                                            @foreach($product->tags as $tag)
                                                <span class="badge bg-light text-dark">#{{ $tag }}</span>
                                            @endforeach
                                        </p>
                                        @endif
                                    </div>

                                    <!-- View Count -->
                                    {{-- <p class="text-muted"><i class="fa fa-eye"></i> {{ $product->view_count }} lượt xem</p> --}}

                                </div>
                            </div>

                        </div>

                        <!-- Related Products -->
                        @if($relatedProducts->count() > 0)
                        <div class="row mt-5">
                            <div class="col-12">
                                <h3 class="mb-4">Sản phẩm liên quan</h3>
                                <div class="row g-3">
                                    @foreach($relatedProducts as $relatedProduct)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="product-card">
                                            <div class="product-image">
                                                @if($relatedProduct->image_url)
                                                    <img src="{{ str_starts_with($relatedProduct->image_url, 'http') ? $relatedProduct->image_url : asset($relatedProduct->image_url) }}" 
                                                         alt="{{ $relatedProduct->name }}">
                                                @else
                                                    <i class="fa fa-image"></i>
                                                @endif
                                            </div>
                                            <h5>{{ Str::limit($relatedProduct->name, 50) }}</h5>
                                            <p class="text-primary fw-bold">{{ number_format($relatedProduct->sale_price ?? $relatedProduct->price, 0, ',', '.') }}đ</p>
                                            <a href="{{ route(current_locale() . '.product.show', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-primary w-100">Xem chi tiết</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Product Description & Specifications -->
                        <div class="row mt-5 pt-30">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                                            Mô tả chi tiết
                                        </button>
                                    </li>
                                    @if($product->features)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button">
                                            Tính năng nổi bật
                                        </button>
                                    </li>
                                    @endif
                                    @if($product->specifications)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button">
                                            Thông số kỹ thuật
                                        </button>
                                    </li>
                                    @endif
                                    @if($product->manual_url || $product->datasheet_url || $product->video_url)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="downloads-tab" data-bs-toggle="tab" data-bs-target="#downloads" type="button">
                                            Tài liệu & Video
                                        </button>
                                    </li>
                                    @endif
                                </ul>

                                <div class="tab-content" id="productTabContent">
                                    <!-- Description -->
                                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                                        <div class="description-content">
                                            {!! $product->description ?? '<p>Đang cập nhật thông tin chi tiết...</p>' !!}
                                        </div>
                                    </div>

                                    <!-- Features -->
                                    @if($product->features)
                                    <div class="tab-pane fade" id="features" role="tabpanel">
                                        <div class="features-content">
                                            <h4 class="mb-3">Tính năng nổi bật</h4>
                                            <ul class="list-group list-group-flush">
                                                @foreach($product->features as $feature)
                                                <li class="list-group-item">
                                                    <i class="fa fa-check-circle text-success me-2"></i>{{ $feature }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Specifications -->
                                    @if($product->specifications)
                                    <div class="tab-pane fade" id="specs" role="tabpanel">
                                        <div class="specifications-content">
                                            <h4>Thông số kỹ thuật</h4>
                                            <div class="spec-list">
                                                @foreach($product->specifications as $key => $value)
                                                <div class="spec-item">
                                                    <span class="spec-label">{{ $key }}</span>
                                                    <span class="spec-value">
                                                        @if(filter_var($value, FILTER_VALIDATE_URL))
                                                            <a href="{{ $value }}" target="_blank" rel="noopener noreferrer">
                                                                {{ Str::limit($value, 50) }} ↗
                                                            </a>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Downloads & Video -->
                                    @if($product->manual_url || $product->datasheet_url || $product->video_url)
                                    <div class="tab-pane fade" id="downloads" role="tabpanel">
                                        <div class="downloads-content">
                                            @if($product->video_url)
                                            <div class="mb-4">
                                                <h4 class="mb-3">Video sản phẩm</h4>
                                                <div class="ratio ratio-16x9">
                                                    @if(str_contains($product->video_url, 'youtube.com') || str_contains($product->video_url, 'youtu.be'))
                                                        @php
                                                            // Extract YouTube video ID
                                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $product->video_url, $matches);
                                                            $videoId = $matches[1] ?? null;
                                                            // Build external watch URL
                                                            $watchUrl = $videoId ? 'https://www.youtube.com/watch?v=' . $videoId : $product->video_url;
                                                            // Use product main image as clickable thumbnail (banner). If none, try YouTube thumbnail.
                                                            $thumb = '';
                                                            if($product->image_url) {
                                                                $thumb = str_starts_with($product->image_url, 'http') ? $product->image_url : asset($product->image_url);
                                                            } elseif ($videoId) {
                                                                $thumb = 'https://img.youtube.com/vi/' . $videoId . '/maxresdefault.jpg';
                                                            }
                                                        @endphp

                                                        @if($videoId)
                                                            {{-- Clickable banner that opens the YouTube watch page in a new tab to avoid embed restrictions --}}
                                                            <a href="{{ $watchUrl }}" target="_blank" rel="noopener noreferrer" class="d-block" style="text-decoration:none;color:inherit;">
                                                                <div style="position:relative;width:100%;height:100%;min-height:360px;background:#000;display:flex;align-items:center;justify-content:center;">
                                                                    @if($thumb)
                                                                        <img src="{{ $thumb }}" alt="Video thumbnail" style="width:100%;height:100%;object-fit:cover;display:block;opacity:0.95;">
                                                                    @else
                                                                        <div style="width:100%;height:100%;background:#111;" aria-hidden="true"></div>
                                                                    @endif
                                                                    <div style="position:absolute;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                                                                        <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <circle cx="48" cy="48" r="46" stroke="rgba(255,255,255,0.9)" stroke-width="4" fill="rgba(0,0,0,0.35)" />
                                                                            <path d="M40 34L64 48L40 62V34Z" fill="white"/>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @else
                                                            {{-- Fallback: if no videoId but URL contains youtube, open link directly --}}
                                                            <p><a href="{{ $product->video_url }}" target="_blank" rel="noopener noreferrer">Open video</a></p>
                                                        @endif
                                                    @else
                                                        {{-- Non-YouTube video: keep inline player --}}
                                                        <video controls style="width: 100%;">
                                                            <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                                                            Trình duyệt của bạn không hỗ trợ video.
                                                        </video>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($product->manual_url || $product->datasheet_url)
                                            <div>
                                                <h4 class="mb-3">Tài liệu tải về</h4>
                                                @if($product->manual_url)
                                                <p><a href="{{ asset('storage/' . $product->manual_url) }}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fa fa-download"></i> Tải hướng dẫn sử dụng
                                                </a></p>
                                                @endif
                                                @if($product->datasheet_url)
                                                <p><a href="{{ asset('storage/' . $product->datasheet_url) }}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fa fa-download"></i> Tải tài liệu kỹ thuật (Datasheet)
                                                </a></p>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

            </main>

            <!-- Footer -->
            @include('front.includes.footer')
        </div>
    </div>

    <!-- JS -->
<script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-bundle.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.js') }}"></script>
<script src="{{ asset('assets/js/plugin.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>
<script src="{{ asset('assets/js/magnific-popup.js') }}"></script>
<script src="{{ asset('assets/js/nice-select.js') }}"></script>
<script src="{{ asset('assets/js/ajax-form.js') }}"></script>
<script src="{{ asset('assets/js/productDetails.js') }}"></script>
<script src="{{ asset('assets/js/three.js') }}"></script>
<script src="{{ asset('assets/js/scroll-magic.js') }}"></script>
<script src="{{ asset('assets/js/hover-effect.umd.js') }}"></script>
<script src="{{ asset('assets/js/parallax-slider.js') }}"></script>
<script src="{{ asset('assets/js/purecounter.js') }}"></script>
<script src="{{ asset('assets/js/isotope-pkgd.js') }}"></script>
<script src="{{ asset('assets/js/imagesloaded-pkgd.js') }}"></script>
<script src="{{ asset('assets/js/Observer.min.js') }}"></script>
<script src="{{ asset('assets/js/splitting.min.js') }}"></script>
<script src="{{ asset('assets/js/webgl.js') }}"></script>
<script src="{{ asset('assets/js/parallax-scroll.js') }}"></script>
<script src="{{ asset('assets/js/atropos.js') }}"></script>
<script src="{{ asset('assets/js/slider-active.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/popup.js') }}"></script>
<script src="{{ asset('assets/js/header-search.js') }}"></script>
<script src="{{ asset('assets/js/tp-cursor.js') }}"></script>
<script src="{{ asset('assets/js/portfolio-slider-1.js') }}"></script>
<script type="module" src="{{ asset('assets/js/distortion-img.js') }}"></script>
<script type="module" src="{{ asset('assets/js/skew-slider/index.js') }}"></script>
<script type="module" src="{{ asset('assets/js/img-revel/index.js') }}"></script>


</body>
</html>