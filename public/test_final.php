<!DOCTYPE html>
<html>
<head>
    <title>Test Final Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .test-item { margin: 20px 0; padding: 15px; border: 1px solid #ccc; background: white; }
        .test-image { max-width: 200px; height: auto; border: 1px solid #ddd; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #10b981; }
        h2 { color: #059669; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <h1>🎯 TEST FINAL DES IMAGES</h1>
    
    <div class="test-item">
        <h2>📊 Résultats attendus</h2>
        <p class="success">✅ Toutes les images devraient s'afficher correctement</p>
        <p class="info">🔍 Si vous voyez des bordures rouges = problème d'accès</p>
    </div>
    
    <?php
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo '<div class="grid">';
    
    // Test des produits
    $products = \App\Models\Product::whereNotNull('image')->get();
    
    foreach ($products as $index => $product) {
        echo '<div class="test-item">';
        echo "<h3>📦 Produit " . ($index + 1) . ": {$product->name}</h3>";
        echo "<p><strong>Chemin:</strong> {$product->image}</p>";
        echo "<p><strong>URL générée:</strong> <a href='{$product->image_url}' target='_blank'>{$product->image_url}</a></p>";
        
        // Test avec l'URL générée par le modèle
        echo "<img src='{$product->image_url}' class='test-image' 
              onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ IMAGE NON ACCESSIBLE'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Image non accessible</p>";
        
        echo "<p class='info'>📂 Fichier existe: " . (Storage::disk('public')->exists($product->image) ? '✅ YES' : '❌ NO') . "</p>";
        echo '</div>';
    }
    
    // Test des vendeurs
    $vendors = \App\Models\Vendor::whereNotNull('logo')->get();
    
    foreach ($vendors as $index => $vendor) {
        echo '<div class="test-item">';
        echo "<h3>🏪 Vendeur " . ($index + 1) . ": {$vendor->shop_name}</h3>";
        echo "<p><strong>Chemin:</strong> {$vendor->logo}</p>";
        echo "<p><strong>URL générée:</strong> <a href='{$vendor->logo_url}' target='_blank'>{$vendor->logo_url}</a></p>";
        
        echo "<img src='{$vendor->logo_url}' class='test-image' 
              onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ LOGO NON ACCESSIBLE'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Logo non accessible</p>";
        
        echo "<p class='info'>📂 Fichier existe: " . (Storage::disk('public')->exists($vendor->logo) ? '✅ YES' : '❌ NO') . "</p>";
        echo '</div>';
    }
    
    echo '</div>';
    ?>
    
    <div class="test-item">
        <h2>🔧 Tests directs des URLs</h2>
        
        <h3>Test 1: URLs relatives</h3>
        <img src="storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg" class="test-image" 
             onerror="this.style.border='3px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Relative URL 1 failed</p>
        
        <img src="storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg" class="test-image" 
             onerror="this.style.border='3px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Relative URL 2 failed</p>
        
        <h3>Test 2: URLs absolues</h3>
        <img src="/guineemall/storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg" class="test-image" 
             onerror="this.style.border='3px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Absolute URL 1 failed</p>
        
        <img src="/guineemall/storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg" class="test-image" 
             onerror="this.style.border='3px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Absolute URL 2 failed</p>
    </div>
    
    <div class="test-item">
        <h2>🌐 Informations système</h2>
        <p><strong>URL de base:</strong> <?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>Racine document:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
        <p><strong>Script actuel:</strong> <?php echo __FILE__; ?></p>
        
        <h3>📁 Vérification des répertoires</h3>
        <?php
        $paths = [
            'storage/products/',
            'storage/vendor-logos/',
            '../storage/app/public/products/',
            '../storage/app/public/vendor-logos/'
        ];
        
        foreach ($paths as $path) {
            $fullPath = __DIR__ . '/' . $path;
            echo "<p><strong>$path:</strong> " . (is_dir($fullPath) ? '✅ DIR EXISTS' : '❌ DIR NOT FOUND') . "</p>";
        }
        ?>
    </div>
    
    <div class="test-item">
        <h2>🎯 Conclusion</h2>
        <p class="success">✅ Si toutes les images s'affichent = PROBLÈME RÉSOLU !</p>
        <p class="error">❌ Si des images ont des bordures rouges = PROBLÈME PERSISTANT</p>
        <p class="info">📞 Contactez l'administrateur système si les images ne s'affichent pas</p>
    </div>
    
</body>
</html>
