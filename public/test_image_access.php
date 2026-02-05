<!DOCTYPE html>
<html>
<head>
    <title>Test Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-item { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .test-image { max-width: 200px; height: auto; border: 1px solid #ddd; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Test d'accès aux images</h1>
    
    <?php
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test des produits
    $products = \App\Models\Product::whereNotNull('image')->get();
    
    foreach ($products as $product) {
        echo '<div class="test-item">';
        echo "<h3>Produit: {$product->name}</h3>";
        echo "<p>Chemin: {$product->image}</p>";
        echo "<p>URL: {$product->image_url}</p>";
        
        // Test direct URL
        $imageUrl = $product->image_url;
        echo "<p>Test URL: <a href='$imageUrl' target='_blank'>$imageUrl</a></p>";
        
        // Test avec img
        echo "<img src='$imageUrl' class='test-image' onerror=\"this.style.border='2px solid red'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Image non accessible</p>";
        
        // Test avec storage URL
        $storageUrl = \Storage::url($product->image);
        echo "<p>Storage URL: <a href='$storageUrl' target='_blank'>$storageUrl</a></p>";
        echo "<img src='$storageUrl' class='test-image' onerror=\"this.style.border='2px solid red'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Storage URL non accessible</p>";
        
        echo '</div>';
    }
    
    // Test des vendeurs
    $vendors = \App\Models\Vendor::whereNotNull('logo')->get();
    
    foreach ($vendors as $vendor) {
        echo '<div class="test-item">';
        echo "<h3>Vendeur: {$vendor->shop_name}</h3>";
        echo "<p>Chemin: {$vendor->logo}</p>";
        echo "<p>URL: {$vendor->logo_url}</p>";
        
        $logoUrl = $vendor->logo_url;
        echo "<p>Test URL: <a href='$logoUrl' target='_blank'>$logoUrl</a></p>";
        echo "<img src='$logoUrl' class='test-image' onerror=\"this.style.border='2px solid red'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Logo non accessible</p>";
        
        echo '</div>';
    }
    ?>
    
    <div class="test-item">
        <h3>Test direct des fichiers</h3>
        <?php
        $files = [
            'storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg',
            'storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg',
            'storage/vendor-logos/WPwjRDwsd0zQn8siYUFjsWmScowhqkbrPL9jTuEH.jpg'
        ];
        
        foreach ($files as $file) {
            echo "<p><a href='$file' target='_blank'>$file</a></p>";
            echo "<img src='$file' class='test-image' onerror=\"this.style.border='2px solid red'; this.nextElementSibling.style.display='block';\">";
            echo "<p class='error' style='display:none;'>❌ Fichier non accessible</p>";
        }
        ?>
    </div>
    
</body>
</html>
