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
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item custom-dropdown-link">
                    <a class="nav-link">Shop <i class="bi bi-chevron-down ms-1 nav-dropdown-icon"></i></a>
                    <ul class="custom-dropdown-menu">
                        {{-- <li><a href="{{ route('products') }}">All Products</a></li>
                        <li><a href="{{ route('products') }}">Women's Collection</a></li>
                        <li><a href="{{ route('products') }}">Men's Collection</a></li>
                        <li><a href="{{ route('products') }}">Product Details</a></li>
                        <li><a href="{{ route('home') }}#deals">Deals of the Month</a></li>
                        <li><a href="{{ route('cart') }}">Cart</a></li> --}}
                    </ul>
                </li>

                <li class="nav-item">
                    {{-- <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a> --}}
                </li>

                <li class="nav-item custom-dropdown-link">
                    <a class="nav-link">Pages <i class="bi bi-chevron-down ms-1 nav-dropdown-icon"></i></a>
                    <ul class="custom-dropdown-menu">
                        {{-- <li><a href="{{ route('products') }}">Shop Dashboard</a></li>
                        <li><a href="{{ route('home') }}#featured">Brands</a></li>
                        <li><a href="{{ route('home') }}#products">Portfolio</a></li> --}}
                    </ul>
                </li>

            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <button class="nav-icon-btn" aria-label="Search">
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
