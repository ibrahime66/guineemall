<!DOCTYPE html>
<html>
<head>
    <title>Test Simple Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-image { max-width: 300px; height: auto; border: 2px solid #ddd; margin: 10px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🔍 TEST SIMPLE IMAGES</h1>
    
    <h2>Test direct des fichiers existants:</h2>
    
    <?php
    $files = [
        'storage/products/Maillot-Match-PSG-Domicile-2025-2026-2.jpg',
        'storage/products/OHs60GUyEvWiYsd91sIGrrJCNKtvBRvS0v8R0T15.png',
        'storage/products/ZDYPG5yrtPDz0Wp7yIMxBxMeHcqAYEAsds0uIzbs.jpg',
        'storage/products/images.jfif'
    ];
    
    foreach ($files as $file) {
        echo "<div style='margin: 20px 0;'>";
        echo "<h3>📄 $file</h3>";
        echo "<p>URL: <a href='/$file' target='_blank'>/$file</a></p>";
        echo "<p>Fichier existe: " . (file_exists(__DIR__ . '/' . $file) ? '✅ YES' : '❌ NO') . "</p>";
        echo "<img src='/$file' class='test-image' onerror=\"this.style.border='3px solid red';\">";
        echo "</div>";
    }
    ?>
    
    <h2>Test avec modèle Laravel:</h2>
    
    <?php
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $products = \App\Models\Product::whereNotNull('image')->take(3)->get();
    
    foreach ($products as $product) {
        echo "<div style='margin: 20px 0;'>";
        echo "<h3>📦 {$product->name}</h3>";
        echo "<p>Champ image: {$product->image}</p>";
        echo "<p>URL générée: <a href='{$product->image_url}' target='_blank'>{$product->image_url}</a></p>";
        echo "<img src='{$product->image_url}' class='test-image' onerror=\"this.style.border='3px solid red';\">";
        echo "</div>";
    }
    ?>
    
    <h2>Test fallback:</h2>
    <img src="<?php echo asset('images/default-product.jpg'); ?>" class='test-image' onerror="this.style.border='3px solid red';">
    <p>Fallback: <?php echo asset('images/default-product.jpg'); ?></p>
    
</body>
</html>
