<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}
\Illuminate\Support\Facades\Auth::login($user);

$product = \App\Models\Product::first();
if (!$product) {
    echo "No product found\n";
    exit;
}

$request = \Illuminate\Http\Request::create("/admin/products/{$product->id}", 'PUT', [
    'name' => $product->name,
    'brand' => $product->brand,
    'features' => ['f1', 'f2', 'f3', 'f4', 'f5', 'f6'],
    'action' => 'publish'
]);
$request->setRouteResolver(function() use ($request) {
    return (new \Illuminate\Routing\Route('PUT', 'admin/products/{product}', []))->bind($request);
});

$controller = app(\App\Http\Controllers\Admin\DashboardController::class);
try {
    $response = $controller->updateProduct($request, $product);
    $product = $product->fresh();
    echo "Update complete. Resulting features count: " . count($product->features) . "\n";
    print_r($product->features);
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error: \n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
