<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Test des images
echo "=== TEST DES IMAGES ===\n\n";

// 1. Vérifier les produits avec images
$products = \App\Models\Product::whereNotNull('image')->get();
echo "Produits avec images: " . $products->count() . "\n";

foreach ($products as $product) {
    echo "- Produit: {$product->name}\n";
    echo "  Image path: {$product->image}\n";
    echo "  Image URL: {$product->image_url}\n";
    echo "  File exists: " . (file_exists(storage_path('app/public/' . $product->image)) ? 'YES' : 'NO') . "\n";
    echo "  Storage exists: " . (\Storage::disk('public')->exists($product->image) ? 'YES' : 'NO') . "\n";
    echo "\n";
}

// 2. Vérifier les vendeurs avec logos
$vendors = \App\Models\Vendor::whereNotNull('logo')->get();
echo "\nVendeurs avec logos: " . $vendors->count() . "\n";

foreach ($vendors as $vendor) {
    echo "- Vendeur: {$vendor->shop_name}\n";
    echo "  Logo path: {$vendor->logo}\n";
    echo "  Logo URL: {$vendor->logo_url}\n";
    echo "  File exists: " . (file_exists(storage_path('app/public/' . $vendor->logo)) ? 'YES' : 'NO') . "\n";
    echo "  Storage exists: " . (\Storage::disk('public')->exists($vendor->logo) ? 'YES' : 'NO') . "\n";
    echo "\n";
}

// 3. Vérifier les fichiers dans storage
echo "\n=== FICHIERS DANS STORAGE ===\n";
$files = \Storage::disk('public')->allFiles();
foreach ($files as $file) {
    echo "- $file\n";
}

echo "\n=== LIEN SYMBOLIQUE ===\n";
$linkExists = is_link(public_path('storage'));
echo "Storage link exists: " . ($linkExists ? 'YES' : 'NO') . "\n";

if ($linkExists) {
    $target = readlink(public_path('storage'));
    echo "Link target: $target\n";
}

echo "\n=== TEST TERMINÉ ===\n";
