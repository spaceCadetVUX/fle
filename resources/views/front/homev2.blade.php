@extends('front.layouts.frontend', [
    'seo' => [
        'title'       => 'Home - Ultimate Sale & New Collection',
        'description' => 'Discover our Spring 2026 exclusive collections, ultimate sales, and curated fashion picks for men and women.',
        'keywords'    => 'fashion, sale, new collection, clothing, apparel',
        'image'       => asset('images/tempSpace/fas (32).jpg'),
        'type'        => 'website',
    ]
])


@section('content')

    {{-- ==========================================
         HERO SECTION
    ========================================== --}}
    <section id="hero">
        <div class="hero-stage">

            <!-- Item 0: starts CENTER -->
            <div class="hero-item pos-center" data-idx="0">
                <img src="{{ asset('images/tempSpace/fas (32).jpg') }}" alt="Hero 1">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">Au — New Collection</p>
                    <h1 class="hero-title">Ultimate<br>Sale</h1>
                    <p class="hero-subtitle">New Collection</p>
                    <a href="#deals" class="btn-dark-custom">Shop Now</a>
                </div>
            </div>

            <!-- Item 1: starts RIGHT -->
            <div class="hero-item pos-right" data-idx="1">
                <img src="{{ asset('images/tempSpace/fas (33).jpg') }}" alt="Hero 2">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">SS 2026 — Exclusive</p>
                    <h1 class="hero-title">New<br>Drops</h1>
                    <p class="hero-subtitle">Limited Edition</p>
                    <a href="#products" class="btn-dark-custom">Explore</a>
                </div>
            </div>

            <!-- Item 2: starts HIDDEN RIGHT -->
            <div class="hero-item pos-hidden-right" data-idx="2">
                <img src="{{ asset('images/tempSpace/fas (45).jpg') }}" alt="Hero 3">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">Women — Curated Picks</p>
                    <h1 class="hero-title">For<br>Her</h1>
                    <p class="hero-subtitle">Women's Collection</p>
                    <a href="#products" class="btn-dark-custom">Shop Women</a>
                </div>
            </div>

            <!-- Item 3: starts HIDDEN LEFT -->
            <div class="hero-item pos-hidden-left" data-idx="3">
                <img src="{{ asset('images/tempSpace/fas (35).jpg') }}" alt="Hero 4">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">Men — Calibre</p>
                    <h1 class="hero-title">For<br>Him</h1>
                    <p class="hero-subtitle">Men's Collection</p>
                    <a href="#products" class="btn-dark-custom">Shop Men</a>
                </div>
            </div>

            <!-- Item 4: starts LEFT -->
            <div class="hero-item pos-left" data-idx="4">
                <img src="{{ asset('images/tempSpace/fas (50).jpg') }}" alt="Hero 5">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">New Season — 2026</p>
                    <h1 class="hero-title">New<br>Season</h1>
                    <p class="hero-subtitle">Spring Collection</p>
                    <a href="#products" class="btn-dark-custom">Discover</a>
                </div>
            </div>

        </div><!-- /.hero-stage -->

        <!-- Dot indicators -->
        <div class="hero-dots" id="heroDots">
            <button class="hero-dot active" aria-label="Slide 1"></button>
            <button class="hero-dot" aria-label="Slide 2"></button>
            <button class="hero-dot" aria-label="Slide 3"></button>
            <button class="hero-dot" aria-label="Slide 4"></button>
            <button class="hero-dot" aria-label="Slide 5"></button>
        </div>

        <!-- Prev / Next arrows -->
        <div class="hero-nav-arrows">
            <button class="hero-arrow" id="heroPrev" aria-label="Previous slide">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="hero-arrow" id="heroNext" aria-label="Next slide">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </section>

    {{-- ==========================================
         BRAND LOGOS BAR
    ========================================== --}}
    <section id="brands">
        <div class="container">
            <div class="brand-logos">
                <span class="brand-name large">Chanel</span>
                <span class="brand-name">Louis Vuitton</span>
                <span class="brand-name large">Prada</span>
                <span class="brand-name">Calvin Klein</span>
                <span class="brand-name large">Denim</span>
            </div>
        </div>
    </section>

    {{-- ==========================================
         DEALS OF THE MONTH
    ========================================== --}}
    <section id="deals" class="section-pad">
        <div class="container">
            <div class="row align-items-center g-5">

                <!-- Text + Countdown -->
                <div class="col-lg-5 fade-up">
                    <div class="deals-text">
                        <h2>Deals Of<br>The Month</h2>
                        <p>
                            Don't miss our exclusive monthly deals — curated selections from
                            the world's leading fashion houses at unbeatable prices. Limited
                            time, limited stock.
                        </p>
                        <div class="mt-4">
                            <a href="#products" class="btn-dark-custom">Shop Now</a>
                        </div>
                        <!-- Countdown -->
                        <div class="countdown-wrap">
                            <p class="countdown-label">Hurry, Before It's Too Late!</p>
                            <div class="countdown">
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-days">02</span>
                                    <span class="countdown-text">Days</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-hours">06</span>
                                    <span class="countdown-text">Hours</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-mins">05</span>
                                    <span class="countdown-text">Mins</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-secs">30</span>
                                    <span class="countdown-text">Secs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deals Slider -->
                <div class="col-lg-7 fade-up">
                    <div class="deals-slider" id="dealsSlider">

                        <div class="deals-slide active">
                            <img src="{{ asset('images/tempSpace/fas (3).jpg') }}" alt="Deals 1">
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">Shop Casual Luxe</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (28).jpg') }}" alt="Deals 2">
                            <div class="deals-badge"><span>50%</span>OFF</div>
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">Discover Offers</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (45).jpg') }}" alt="Deals 3">
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">Explore Collection</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (56).jpg') }}" alt="Deals 4">
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">Get The Look</a>
                            </div>
                        </div>

                        <button type="button" class="deals-arrow deals-prev" aria-label="Previous Deal">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="deals-arrow deals-next" aria-label="Next Deal">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                    </div>
                    <div class="carousel-dots mt-3" id="dealsDots">
                        <div class="carousel-dot active"></div>
                        <div class="carousel-dot"></div>
                        <div class="carousel-dot"></div>
                        <div class="carousel-dot"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ==========================================
         FEATURED: CALIBRE
    ========================================== --}}
    <section id="featured">
        <div class="calibre-header fade-up">
            <p class="calibre-eyebrow">Featured Brand</p>
            <h2 class="calibre-title">Calibre</h2>
            <p class="calibre-subtitle">Men's Contemporary Collection — 2026</p>
        </div>

        <div class="calibre-blog-grid">

            <article class="cblog cblog-1 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (6).jpg') }}" alt="Calibre — The Modern Gentleman">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content">
                    <h3 class="cblog-heading">The Modern<br>Gentleman</h3>
                    <p class="cblog-desc">Refined silhouettes crafted for the man who commands every room he enters.</p>
                    <a href="#products" class="cblog-cta btn-white-custom">Read Story</a>
                </div>
            </article>

            <article class="cblog cblog-2 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (21).jpg') }}" alt="Calibre — Layer Up">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom">
                    <h3 class="cblog-heading">Layer Up</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">Explore</a>
                </div>
            </article>

            <article class="cblog cblog-3 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (54).jpg') }}" alt="Calibre — Sharp Tailoring">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--center">
                    <h3 class="cblog-heading">Sharp<br>Cuts</h3>
                    <a href="#products" class="cblog-cta btn-outline-custom cblog-cta--light">Shop Now</a>
                </div>
            </article>

            <article class="cblog cblog-4 cblog--editorial fade-up">
                <div class="cblog-editorial-inner">
                    <span class="cblog-tag cblog-tag--dark">SS 2026</span>
                    <p class="cblog-editorial-quote">"Dress well.<br>Live bold."</p>
                    <h3 class="cblog-heading cblog-heading--dark">The Calibre<br>Manifesto</h3>
                    <p class="cblog-desc cblog-desc--dark">Six decades of precision. One season of reinvention.</p>
                    <a href="#products" class="cblog-cta btn-dark-custom">Discover More</a>
                </div>
            </article>

            <article class="cblog cblog-5 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (39).jpg') }}" alt="Calibre — Street Refined">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom">
                    <h3 class="cblog-heading">Street<br>Refined</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">View Look</a>
                </div>
            </article>

            <article class="cblog cblog-6 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (47).jpg') }}" alt="Calibre — Cold Weather Edit">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content">
                    <h3 class="cblog-heading">Cold Weather<br>Edit</h3>
                    <p class="cblog-desc">From puffer jackets to overcoats — stay warm in undeniable style.</p>
                    <a href="#products" class="cblog-cta btn-white-custom">Shop Outerwear</a>
                </div>
            </article>

            <article class="cblog cblog-7 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (51).jpg') }}" alt="Calibre — Calibre Man">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom-right">
                    <h3 class="cblog-heading">Calibre<br>Man</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">See Collection</a>
                </div>
            </article>

        </div><!-- /.calibre-blog-grid -->
    </section>

    {{-- ==========================================
         PRODUCT GRIDS
    ========================================== --}}
    <section id="products" class="section-pad">
        <div class="container">

            <!-- For Women -->
            <div class="products-header fade-up">
                <h2 class="products-title">For Women</h2>
                <div class="products-nav">
                    <a href="#" class="view-all-link">Get All</a>
                    <button class="products-arrow" data-prev aria-label="Previous" id="womenPrev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="products-arrow" data-next aria-label="Next" id="womenNext">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3" id="womenSliderWrap" data-slider>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (17).jpg') }}" alt="Women's Coat"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                        <span class="product-tag new">New</span>
                    </div>
                    <p class="product-name">Women's Coat</p>
                    <p class="product-price">3,999,000 VND</p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (25).jpg') }}" alt="Slim Blazer"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                    </div>
                    <p class="product-name">Slim Blazer</p>
                    <p class="product-price">2,599,000 VND</p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (28).jpg') }}" alt="Casual Knit Set"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                        <span class="product-tag sale">Sale</span>
                    </div>
                    <p class="product-name">Casual Knit Set</p>
                    <p class="product-price">1,899,000 VND <span class="old-price">2,500,000 VND</span></p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (30).jpg') }}" alt="Wide-Leg Trousers"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                    </div>
                    <p class="product-name">Wide-Leg Trousers</p>
                    <p class="product-price">1,499,000 VND</p>
                </div>
            </div>

            <div class="products-divider"></div>

            <!-- For Men -->
            <div class="products-header fade-up">
                <h2 class="products-title">For Men</h2>
                <div class="products-nav">
                    <a href="#" class="view-all-link">Get All</a>
                    <button class="products-arrow" data-prev aria-label="Previous" id="menPrev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="products-arrow" data-next aria-label="Next" id="menNext">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3" id="menSliderWrap" data-slider>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (56).jpg') }}" alt="Leather Jacket"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                        <span class="product-tag new">New</span>
                    </div>
                    <p class="product-name">Leather Jacket</p>
                    <p class="product-price">5,200,000 VND</p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (26).jpg') }}" alt="Fitted Turtleneck"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                    </div>
                    <p class="product-name">Fitted Turtleneck</p>
                    <p class="product-price">1,299,000 VND</p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (27).jpg') }}" alt="Oversized Puffer"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                        <span class="product-tag sale">Sale</span>
                    </div>
                    <p class="product-name">Oversized Puffer</p>
                    <p class="product-price">2,799,000 VND <span class="old-price">3,500,000 VND</span></p>
                </div>
                <div class="col-6 col-md-4 col-lg-3 product-card fade-up">
                    <div class="product-img-wrap">
                        <img src="{{ asset('images/tempSpace/fas (39).jpg') }}" alt="Slim Chino Pants"
                             style="width:100%;aspect-ratio:3/4;object-fit:cover;display:block;">
                        <div class="product-quick-add">Quick Add</div>
                    </div>
                    <p class="product-name">Slim Chino Pants</p>
                    <p class="product-price">1,499,000 VND</p>
                </div>
            </div>

        </div>
    </section>

@endsection
