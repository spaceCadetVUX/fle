@extends('layouts.frontend')

@section('content')

    <section class="shop-hero position-relative overflow-hidden">
        <img src="{{ asset('images/tempSpace/fas (21).jpg') }}" alt="Shop Hero Background" class="shop-hero-bg w-100 h-100 position-absolute top-0 start-0 object-fit-cover" style="z-index: 0; filter: brightness(0.6);">
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

      <!-- TITLE & BREADCRUMBS -->
      <div class="shop-header text-center py-5 mt-3">
        <h2 class="fw-bold fs-3 mb-2">Summer collection</h2>
        <p class="text-muted text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 0.25em;">Home <span class="mx-1">&gt;</span> Shop</p>
      </div>

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

               <!-- Size -->
               <div class="filter-group mb-4">
                   <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Size</h6>
                   <div class="d-flex flex-wrap gap-2 size-filters">
                      <button class="size-btn">S</button>
                      <button class="size-btn">M</button>
                      <button class="size-btn">L</button>
                      <button class="size-btn">XL</button>
                      <button class="size-btn d-none">XXL</button>
                   </div>
               </div>

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

               <!-- Price -->
               <div class="filter-group mb-4">
                   <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Prices</h6>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="price1">
                      <label class="form-check-label" for="price1">$0.00 - $15.00</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="price2" checked>
                      <label class="form-check-label" for="price2">$15.00 - $20.00</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="price3">
                      <label class="form-check-label" for="price3">$20.00 - $35.00</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="price4">
                      <label class="form-check-label" for="price4">$35.00 - $50.00</label>
                   </div>
               </div>

               <!-- Brands -->
               <div class="filter-group mb-4">
                   <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Brands</h6>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="brand1">
                      <label class="form-check-label" for="brand1">Mango</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="brand2">
                      <label class="form-check-label" for="brand2">Zara</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="brand3" checked>
                      <label class="form-check-label" for="brand3">H&amp;M</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="brand4">
                      <label class="form-check-label" for="brand4">Pull&amp;Bear</label>
                   </div>
               </div>

               <!-- Categories -->
               <div class="filter-group mb-4">
                   <h6 class="filter-title font-xs fw-bold text-uppercase letter-wide text-muted mb-3">Categories</h6>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="cat1" checked>
                      <label class="form-check-label" for="cat1">Women</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="cat2">
                      <label class="form-check-label" for="cat2">Men</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="cat3">
                      <label class="form-check-label" for="cat3">Accessories</label>
                   </div>
                   <div class="form-check filter-check">
                      <input class="form-check-input" type="checkbox" id="cat4">
                      <label class="form-check-label" for="cat4">Kids</label>
                   </div>
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
                 <span class="font-xs fw-bold text-uppercase letter-wide text-muted">9 Results</span>
                 <button class="btn btn-outline-dark btn-sm rounded-0 text-uppercase letter-wide fw-bold font-xs px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopSidebar" aria-controls="shopSidebar">
                     <i class="bi bi-sliders me-2"></i> Filter
                 </button>
             </div>

             <div class="row row-cols-2 row-cols-md-3 g-3 g-lg-4 gx-lg-5">
                 
                 <!-- Product 1 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (17).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 2 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (25).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                    <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 3 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (30).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 4 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (28).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                   <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 5 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (33).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 6 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (45).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 7 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (50).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                    <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 8 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (56).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                   <p class="product-name">Women's Coat</p>
                    <p class="product-price">3,999,000 VND</p>
                 </div>

                 <!-- Product 9 -->
                 <div class="col shop-product-card">
                   <div class="shop-product-img-wrap mb-3 position-relative overflow-hidden">
                     <img src="{{ asset('images/tempSpace/fas (59).jpg') }}" alt="Product" class="w-100 object-fit-cover" style="aspect-ratio: 3/4;">
                     <div class="product-quick-add">Quick Add</div>
                   </div>
                  <p class="product-name">Women's Coat</p>
                  <p class="product-price">3,999,000 VND</p>
                 </div>

             </div>
             
             <!-- Pagination -->
             <div class="d-flex justify-content-center mt-5 pt-3 mb-4">
                 <div class="shop-pagination d-flex gap-2">
                     <button class="page-btn active">1</button>
                     <button class="page-btn">2</button>
                     <button class="page-btn">3</button>
                     <button class="page-btn"><i class="bi bi-chevron-right"></i></button>
                 </div>
             </div>

          </main>
        </div>
      </div>

@endsection
