<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\ProjectController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;




Route::get('/', function () {
    return redirect()->to(url('vi'), 301);
})->name('home');

// ==============================
// VIETNAMESE ROUTES (/vi)
// ==============================
Route::prefix('vi')->name('vi.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('/homev2', [PageController::class, 'homev2'])->name('homev2');
    
    // FRONT PAGES
    Route::controller(PageController::class)->group(function () {
        Route::get('/dieu-khien-chieu-sang', 'lightingControl')->name('solution.lighting');
        Route::get('/rem-tu-dong', 'shade')->name('solution.shade');
        Route::get('/dieu-khien-hvac', 'hvacControl')->name('solution.hvac');
        Route::get('/he-thong-an-ninh', 'security')->name('solution.security');
        Route::get('/bms', 'bms')->name('solution.bms');
        Route::get('/dieu-khien-phong-khach-san', 'hotelControl')->name('solution.hotel');
        Route::get('/contact', 'contact')->name('contact');

        // PARTNERS PAGES (VIETNAMESE)
        Route::get('/abb', 'abb')->name('abb');
        Route::get('/legrand', 'legrand')->name('legrand');
        Route::get('/vantage', 'vantage')->name('vantage');
        Route::get('/cp-electronics', 'cpElectronics')->name('cpElectronics');
    });

    // BLOG
    Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/category/{category}', 'byCategory')->name('category');
        Route::get('/search', 'search')->name('search');
        Route::get('/{slug}', 'show')->name('show');
    });

    // PROJECTS
    Route::controller(ProjectController::class)->prefix('du-an')->name('projects.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/danh-muc/{category}', 'byCategory')->name('category');
        Route::get('/{slug}', 'show')->name('show');
    });

    // PRODUCTS
    Route::controller(ProductController::class)
    ->prefix('san-pham')
    ->name('product.')
    ->group(function () {
        Route::get('/', 'index')->name('shop');

        Route::get('/search', 'search')->name('search');
        Route::get('/autocomplete', 'autocomplete')->name('autocomplete');
        Route::get('/brand/{brand}', 'byBrand')->name('brand');

        Route::get('/{slug}', 'show')->name('show');
    });



});

// ==============================
// ENGLISH ROUTES (/en)
// ==============================
Route::prefix('en')->name('en.')->group(function () {
     Route::get('/', [PageController::class, 'index'])->name('index');
     Route::get('homev2', [PageController::class, 'homev2'])->name('homev2');
    // FRONT PAGES
    Route::controller(PageController::class)->group(function () {
    Route::get('/lighting-control-solutions', 'lightingControl')
        ->name('solution.lighting');
    Route::get('/automatic-shading-solutions', 'shade')
        ->name('solution.shade');
    Route::get('/hvac-control-solutions', 'hvacControl')
        ->name('solution.hvac');
    Route::get('/security-systems', 'security')
        ->name('solution.security');
    Route::get('/building-management-system', 'bms')
        ->name('solution.bms');
    Route::get('/hotel-room-management-system', 'hotelControl')
        ->name('solution.hotel');
    Route::get('/contact', 'contact')->name('contact');

    // Partners pages (English)
    Route::get('/abb', 'abb')->name('abb');
    Route::get('/legrand', 'legrand')->name('legrand');
    Route::get('/vantage', 'vantage')->name('vantage');
    Route::get('/cp-electronics', 'cpElectronics')->name('cpElectronics');
});


    // BLOG
    Route::controller(BlogController::class)->prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/category/{category}', 'byCategory')->name('category');
        Route::get('/search', 'search')->name('search');
        Route::get('/{slug}', 'show')->name('show');
    });

    // PROJECTS
    Route::controller(ProjectController::class)->prefix('projects')->name('projects.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/category/{category}', 'byCategory')->name('category');
        Route::get('/{slug}', 'show')->name('show');
    });

    // PRODUCTS
    Route::controller(ProductController::class)
    ->prefix('products')
    ->name('product.')
    ->group(function () {
        Route::get('/', 'index')->name('shop');

        Route::get('/search', 'search')->name('search');
        Route::get('/autocomplete', 'autocomplete')->name('autocomplete');
        Route::get('/brand/{brand}', 'byBrand')->name('brand');

        Route::get('/{slug}', 'show')->name('show');
    });




});


// ----------------------------
// PROFILE PAGES (protected by auth)
// ----------------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/card', function () {
        return view('front.card');
    })->name('user.card');
    
    // Redirect standard dashboard request to the card since the RouteServiceProvider goes to /dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.card');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/verify-recovery-email', [ProfileController::class, 'verifyRecoveryEmail'])->name('profile.verify-recovery-email');
});

// ----------------------------
// ADMIN LOGIN (for guests)
// ----------------------------
Route::middleware(['guest'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return view('auth.admin-login');
    })->name('login');
});

// ----------------------------
// ADMIN PAGES (protected by auth and admin middleware)
// ----------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manage pages
    Route::get('/pages', [DashboardController::class, 'pages'])->name('pages');
    Route::post('/pages/update', [DashboardController::class, 'update'])->name('pages.update');

    // Manage users
    Route::get('/users', [DashboardController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-status', [DashboardController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // Manage products
    Route::get('/products', [DashboardController::class, 'products'])->name('products');
    Route::get('/products/create', [DashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [DashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [DashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [DashboardController::class, 'deleteProduct'])->name('products.delete');
    Route::post('/products/{product}/toggle-status', [DashboardController::class, 'toggleProductStatus'])->name('products.toggle-status');

    // Manage brands
    Route::get('/brands', [DashboardController::class, 'brands'])->name('brands');
    Route::get('/brands/create', [DashboardController::class, 'createBrand'])->name('brands.create');
    Route::post('/brands', [DashboardController::class, 'storeBrand'])->name('brands.store');
    Route::get('/brands/{brand}/edit', [DashboardController::class, 'editBrand'])->name('brands.edit');
    Route::put('/brands/{brand}', [DashboardController::class, 'updateBrand'])->name('brands.update');
    Route::delete('/brands/{brand}', [DashboardController::class, 'deleteBrand'])->name('brands.delete');

    // Manage categories
    Route::get('/categories', [DashboardController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [DashboardController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [DashboardController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [DashboardController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [DashboardController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [DashboardController::class, 'deleteCategory'])->name('categories.delete');

    // Manage blog categories
    Route::get('/blog-categories', [DashboardController::class, 'blogCategories'])->name('blog-categories');
    Route::get('/blog-categories/create', [DashboardController::class, 'createBlogCategory'])->name('blog-categories.create');
    Route::post('/blog-categories', [DashboardController::class, 'storeBlogCategory'])->name('blog-categories.store');
    Route::get('/blog-categories/{blogCategory}/edit', [DashboardController::class, 'editBlogCategory'])->name('blog-categories.edit');
    Route::put('/blog-categories/{blogCategory}', [DashboardController::class, 'updateBlogCategory'])->name('blog-categories.update');
    Route::delete('/blog-categories/{blogCategory}', [DashboardController::class, 'deleteBlogCategory'])->name('blog-categories.delete');

    // Manage blogs
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
    Route::post('/blogs/upload-image', [\App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('blogs.upload-image');

    // Manage project categories
    Route::get('/project-categories', [DashboardController::class, 'projectCategories'])->name('project-categories');
    Route::get('/project-categories/create', [DashboardController::class, 'createProjectCategory'])->name('project-categories.create');
    Route::post('/project-categories', [DashboardController::class, 'storeProjectCategory'])->name('project-categories.store');
    Route::get('/project-categories/{projectCategory}/edit', [DashboardController::class, 'editProjectCategory'])->name('project-categories.edit');
    Route::put('/project-categories/{projectCategory}', [DashboardController::class, 'updateProjectCategory'])->name('project-categories.update');
    Route::delete('/project-categories/{projectCategory}', [DashboardController::class, 'deleteProjectCategory'])->name('project-categories.delete');

    // Manage projects
    Route::get('/projects', [DashboardController::class, 'projects'])->name('projects');
    Route::get('/projects/create', [DashboardController::class, 'createProject'])->name('projects.create');
    Route::post('/projects', [DashboardController::class, 'storeProject'])->name('projects.store');
    Route::get('/projects/{project}/edit', [DashboardController::class, 'editProject'])->name('projects.edit');
    Route::put('/projects/{project}', [DashboardController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{project}', [DashboardController::class, 'deleteProject'])->name('projects.delete');
    // Remove a single image from a project (banner/thumbnail/gallery/og)
    Route::post('/projects/{project}/delete-image', [DashboardController::class, 'deleteProjectImage'])->name('projects.delete-image');
    // Remove a single image from a product (main image or gallery item by index)
    Route::post('/products/{product}/delete-image', [DashboardController::class, 'deleteProductImage'])->name('products.delete-image');

    // Upload image
    Route::post('/upload-image', [DashboardController::class, 'uploadImage'])->name('upload.image');
});

require __DIR__.'/auth.php';
