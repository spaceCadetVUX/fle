<nav id="mainNav" class="navbar navbar-expand-lg">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand nav-logo" href="{{ route('home') }}">Flevie</a>

        <!-- Mobile toggler -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navCollapse"
                aria-controls="navCollapse"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links + icons -->
        <div class="collapse navbar-collapse" id="navCollapse">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('nav.home_button') }}</a>
                </li>

                <li class="nav-item custom-dropdown-link">
                    <a class="nav-link">{{ __('nav.Shop_button') }} <i class="bi bi-chevron-down ms-1 nav-dropdown-icon"></i></a>
                    <ul class="custom-dropdown-menu">
                        <li><a href="{{ route(current_locale().'.product.shop') }}">{{ __('nav.all_Products') }}</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    {{-- <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a> --}}
                </li>

                <li class="nav-item custom-dropdown-link">
                    <a class="nav-link">{{ __('nav.page_button') }} <i class="bi bi-chevron-down ms-1 nav-dropdown-icon"></i></a>
                    <ul class="custom-dropdown-menu">
                        {{-- <li><a href="{{ route('products') }}">Shop Dashboard</a></li>
                        <li><a href="{{ route('home') }}#featured">Brands</a></li>
                        <li><a href="{{ route('home') }}#products">Portfolio</a></li> --}}
                    </ul>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <button class="nav-icon-btn" aria-label="Search" id="navSearchBtn">
                    <i class="bi bi-search"></i>
                </button>
                {{-- <a href="{{ route('cart') }}" class="nav-icon-btn" aria-label="Cart">
                    <i class="bi bi-bag" id="cartIcon"></i>
                </a> --}}
                
                <div class="nav-lang-switcher ms-1 d-none d-md-flex">
                    <a href="{{ switch_locale_url('vi') }}" class="nav-lang-link {{ current_locale() === 'vi' ? 'active' : '' }}">VI</a>
                    <span class="nav-lang-divider">/</span>
                    <a href="{{ switch_locale_url('en') }}" class="nav-lang-link {{ current_locale() === 'en' ? 'active' : '' }}">EN</a>
                </div>

                <button class="nav-signup-btn ms-2">Sign Up</button>
            </div>
        </div>

    </div>
</nav>

<!-- Search Modal Area -->
<div class="tp-search-area" id="tpSearchArea">
    <div class="tp-search-close">
        <button class="tp-search-close-btn" id="tpSearchCloseBtn" aria-label="Close Search">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="tp-search-content">
                    <h3 class="tp-search-title mb-40">{{ __('header.search_title') ?? 'Search Flevie' }}</h3>
                    <div class="tp-search-form-wrapper p-relative">
                        <form action="{{ route(current_locale() . '.product.shop') }}" method="GET" id="headerSearchForm">
                            <div class="tp-search-input-box position-relative">
                                <input type="text" name="q" id="header-search-input" class="tp-search-input" placeholder="{{ __('header.search_placeholder') ?? 'What are you looking for?' }}" autocomplete="off" data-autocomplete-url="{{ route(current_locale() . '.product.autocomplete') }}" data-shop-url="{{ route(current_locale() . '.product.shop') }}">
                                <button type="submit" class="tp-search-submit-btn" aria-label="Search">
                                    <i class="bi bi-search"></i>
                                </button>
                                <!-- Autocomplete dropdown container -->
                                <div id="header-autocomplete-dropdown" class="autocomplete-dropdown" style="display:none; position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 100; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-top: 10px;"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/js/header-search.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('navSearchBtn');
    const searchArea = document.getElementById('tpSearchArea');
    const closeBtn = document.getElementById('tpSearchCloseBtn');
    const searchInput = document.getElementById('header-search-input');

    if (searchBtn && searchArea && closeBtn) {
        // Open search modal
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchArea.classList.add('open');
            setTimeout(() => {
                searchInput.focus();
            }, 300); // Wait for transition
        });

        // Close search modal
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchArea.classList.remove('open');
        });

        // Close on clicking outside the content
        searchArea.addEventListener('click', function(e) {
            if (e.target === searchArea || e.target.classList.contains('container') || e.target.classList.contains('row')) {
                searchArea.classList.remove('open');
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchArea.classList.contains('open')) {
                searchArea.classList.remove('open');
            }
        });
    }
});
</script>
@endpush
