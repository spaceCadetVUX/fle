@extends('front.layouts.frontend', [
    'seo' => [
        'title'       => __('homev2.seo.title'),
        'description' => __('homev2.seo.description'),
        'keywords'    => __('homev2.seo.keywords'),
        'image'       => asset('images/tempSpace/fas (32).jpg'),
        'type'        => 'website',
        'hreflangs'   => [
            'en' => switch_locale_url('en'),
            'vi' => switch_locale_url('vi'),
        ]
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
                <img src="{{ asset('images/tempSpace/fas (32).jpg') }}" alt="{{ __('homev2.hero.item0.title') }}">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">{!! __('homev2.hero.item0.label') !!}</p>
                    <h1 class="hero-title">{!! __('homev2.hero.item0.title') !!}</h1>
                    <p class="hero-subtitle">{{ __('homev2.hero.item0.subtitle') }}</p>
                    <a href="#deals" class="btn-dark-custom">{{ __('homev2.hero.item0.btn') }}</a>
                </div>
            </div>

            <!-- Item 1: starts RIGHT -->
            <div class="hero-item pos-right" data-idx="1">
                <img src="{{ asset('images/tempSpace/fas (33).jpg') }}" alt="{{ __('homev2.hero.item1.title') }}">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">{!! __('homev2.hero.item1.label') !!}</p>
                    <h2 class="hero-title">{!! __('homev2.hero.item1.title') !!}</h2>
                    <p class="hero-subtitle">{{ __('homev2.hero.item1.subtitle') }}</p>
                    <a href="#products" class="btn-dark-custom">{{ __('homev2.hero.item1.btn') }}</a>
                </div>
            </div>

            <!-- Item 2: starts HIDDEN RIGHT -->
            <div class="hero-item pos-hidden-right" data-idx="2">
                <img src="{{ asset('images/tempSpace/fas (45).jpg') }}" alt="{{ __('homev2.hero.item2.title') }}">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">{!! __('homev2.hero.item2.label') !!}</p>
                    <h2 class="hero-title">{!! __('homev2.hero.item2.title') !!}</h2>
                    <p class="hero-subtitle">{{ __('homev2.hero.item2.subtitle') }}</p>
                    <a href="#products" class="btn-dark-custom">{{ __('homev2.hero.item2.btn') }}</a>
                </div>
            </div>

            <!-- Item 3: starts HIDDEN LEFT -->
            <div class="hero-item pos-hidden-left" data-idx="3">
                <img src="{{ asset('images/tempSpace/fas (35).jpg') }}" alt="{{ __('homev2.hero.item3.title') }}">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">{!! __('homev2.hero.item3.label') !!}</p>
                    <h2 class="hero-title">{!! __('homev2.hero.item3.title') !!}</h2>
                    <p class="hero-subtitle">{{ __('homev2.hero.item3.subtitle') }}</p>
                    <a href="#products" class="btn-dark-custom">{{ __('homev2.hero.item3.btn') }}</a>
                </div>
            </div>

            <!-- Item 4: starts LEFT -->
            <div class="hero-item pos-left" data-idx="4">
                <img src="{{ asset('images/tempSpace/fas (50).jpg') }}" alt="{{ __('homev2.hero.item4.title') }}">
                <div class="hero-item-overlay"></div>
                <div class="hero-item-content">
                    <p class="hero-label">{!! __('homev2.hero.item4.label') !!}</p>
                    <h2 class="hero-title">{!! __('homev2.hero.item4.title') !!}</h2>
                    <p class="hero-subtitle">{{ __('homev2.hero.item4.subtitle') }}</p>
                    <a href="#products" class="btn-dark-custom">{{ __('homev2.hero.item4.btn') }}</a>
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
                <span class="brand-name large">{{ __('homev2.brands.b1') }}</span>
                <span class="brand-name">{{ __('homev2.brands.b2') }}</span>
                <span class="brand-name large">{{ __('homev2.brands.b3') }}</span>
                <span class="brand-name">{{ __('homev2.brands.b4') }}</span>
                <span class="brand-name large">{{ __('homev2.brands.b5') }}</span>
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
                        <h2>{!! __('homev2.deals.title') !!}</h2>
                        <p>
                            {{ __('homev2.deals.desc') }}<br>
                            <small class="text-muted d-block mt-2">{{ __('homev2.deals.deal_of_the_month_description') }}</small>
                        </p>
                        <div class="mt-4">
                            <a href="#products" class="btn-dark-custom">{{ __('homev2.deals.btn') }}</a>
                        </div>
                        <!-- Countdown -->
                        <div class="countdown-wrap">
                            <p class="countdown-label">{{ __('homev2.deals.countdown_label') }}</p>
                            <div class="countdown">
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-days">02</span>
                                    <span class="countdown-text">{{ __('homev2.deals.days') }}</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-hours">06</span>
                                    <span class="countdown-text">{{ __('homev2.deals.hours') }}</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-mins">05</span>
                                    <span class="countdown-text">{{ __('homev2.deals.mins') }}</span>
                                </div>
                                <span class="countdown-sep">:</span>
                                <div class="countdown-unit">
                                    <span class="countdown-num" id="cd-secs">30</span>
                                    <span class="countdown-text">{{ __('homev2.deals.secs') }}</span>
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
                                <a href="#products" class="btn-white-custom deals-cta-btn">{{ __('homev2.deals.slide1_btn') }}</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (28).jpg') }}" alt="Deals 2">
                            <div class="deals-badge"><span>50%</span>{{ __('homev2.deals.slide2_badge') }}</div>
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">{{ __('homev2.deals.slide2_btn') }}</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (45).jpg') }}" alt="Deals 3">
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">{{ __('homev2.deals.slide3_btn') }}</a>
                            </div>
                        </div>

                        <div class="deals-slide">
                            <img src="{{ asset('images/tempSpace/fas (56).jpg') }}" alt="Deals 4">
                            <div class="deals-cta-overlay">
                                <a href="#products" class="btn-white-custom deals-cta-btn">{{ __('homev2.deals.slide4_btn') }}</a>
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
            <p class="calibre-eyebrow">{{ __('homev2.featured.eyebrow') }}</p>
            <h2 class="calibre-title">{{ __('homev2.featured.title') }}</h2>
            <p class="calibre-subtitle">{{ __('homev2.featured.subtitle') }}</p>
        </div>

        <div class="calibre-blog-grid">

            <article class="cblog cblog-1 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (6).jpg') }}" alt="Calibre — The Modern Gentleman">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog1.title') !!}</h3>
                    <p class="cblog-desc">{{ __('homev2.featured.cblog1.desc') }}</p>
                    <a href="#products" class="cblog-cta btn-white-custom">{{ __('homev2.featured.cblog1.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-2 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (21).jpg') }}" alt="Calibre — Layer Up">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog2.title') !!}</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">{{ __('homev2.featured.cblog2.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-3 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (54).jpg') }}" alt="Calibre — Sharp Tailoring">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--center">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog3.title') !!}</h3>
                    <a href="#products" class="cblog-cta btn-outline-custom cblog-cta--light">{{ __('homev2.featured.cblog3.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-4 cblog--editorial fade-up">
                <div class="cblog-editorial-inner">
                    <span class="cblog-tag cblog-tag--dark">{{ __('homev2.featured.cblog4.tag') }}</span>
                    <p class="cblog-editorial-quote">{!! __('homev2.featured.cblog4.quote') !!}</p>
                    <h3 class="cblog-heading cblog-heading--dark">{!! __('homev2.featured.cblog4.title') !!}</h3>
                    <p class="cblog-desc cblog-desc--dark">{{ __('homev2.featured.cblog4.desc') }}</p>
                    <a href="#products" class="cblog-cta btn-dark-custom">{{ __('homev2.featured.cblog4.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-5 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (39).jpg') }}" alt="Calibre — Street Refined">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog5.title') !!}</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">{{ __('homev2.featured.cblog5.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-6 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (47).jpg') }}" alt="Calibre — Cold Weather Edit">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog6.title') !!}</h3>
                    <p class="cblog-desc">{{ __('homev2.featured.cblog6.desc') }}</p>
                    <a href="#products" class="cblog-cta btn-white-custom">{{ __('homev2.featured.cblog6.btn') }}</a>
                </div>
            </article>

            <article class="cblog cblog-7 fade-up">
                <div class="cblog-img-wrap">
                    <img src="{{ asset('images/tempSpace/fas (51).jpg') }}" alt="Calibre — Calibre Man">
                    <div class="cblog-overlay"></div>
                </div>
                <div class="cblog-content cblog-content--bottom-right">
                    <h3 class="cblog-heading">{!! __('homev2.featured.cblog7.title') !!}</h3>
                    <a href="#products" class="cblog-cta btn-white-custom">{{ __('homev2.featured.cblog7.btn') }}</a>
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
                <h2 class="products-title">{{ __('homev2.products.women_title') }}</h2>
                <div class="products-nav">
                    <a href="#" class="view-all-link">{{ __('homev2.products.get_all') }}</a>
                    <button class="products-arrow" data-prev aria-label="Previous" id="womenPrev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="products-arrow" data-next aria-label="Next" id="womenNext">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3" id="womenSliderWrap" data-slider>

            </div>

            <div class="products-divider"></div>

            <!-- For Men -->
            <div class="products-header fade-up">
                <h2 class="products-title">{{ __('homev2.products.men_title') }}</h2>
                <div class="products-nav">
                    <a href="#" class="view-all-link">{{ __('homev2.products.get_all') }}</a>
                    <button class="products-arrow" data-prev aria-label="Previous" id="menPrev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="products-arrow" data-next aria-label="Next" id="menNext">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3" id="menSliderWrap" data-slider>

            </div>

        </div>
    </section>

@endsection
