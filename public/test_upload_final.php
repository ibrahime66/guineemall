<!DOCTYPE html>
<html>
<head>
    <title>Test Final Upload Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-image { max-width: 300px; height: auto; border: 2px solid #ddd; margin: 10px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #10b981; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .test-section { border: 1px solid #ddd; padding: 20px; margin: 20px 0; }
    </style>
</head>
<body>
    <h1>🎯 TEST FINAL UPLOAD IMAGES</h1>
    
    <div class="test-section">
        <h2>📊 État actuel du système</h2>
        
        <?php
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        echo '<h3>✅ Configuration vérifiée</h3>';
        echo '<p><strong>Storage link:</strong> ' . (is_link(public_path('storage')) ? '✅ OK' : '❌ MANQUANT') . '</p>';
        echo '<p><strong>Storage target:</strong> ' . (is_link(public_path('storage')) ? readlink(public_path('storage')) : 'N/A') . '</p>';
        
        echo '<h3>📁 Fichiers dans storage/app/public/products:</h3>';
        $storageFiles = glob(storage_path('app/public/products/*'));
        foreach ($storageFiles as $file) {
            $filename = basename($file);
            $size = filesize($file);
            echo "<p>📄 $filename ($size bytes)</p>";
        }
        
        echo '<h3>📁 Fichiers dans public/storage/products:</h3>';
        $publicFiles = glob(public_path('storage/products/*'));
        foreach ($publicFiles as $file) {
            $filename = basename($file);
            $size = filesize($file);
            echo "<p>📄 $filename ($size bytes)</p>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🖼️ Test affichage images vendeur</h2>
        
        <?php
        $products = \App\Models\Product::whereNotNull('image')->take(3)->get();
        
        foreach ($products as $product) {
            echo '<div style="border: 1px solid #ddd; padding: 15px; margin: 15px 0;">';
            echo "<h3>📦 {$product->name}</h3>";
            echo "<p><strong>ID:</strong> {$product->id}</p>";
            echo "<p><strong>Champ image:</strong> {$product->image}</p>";
            
            // Test URL correcte
            $correctUrl = asset('storage/' . $product->image);
            echo "<h4>✅ Test URL correcte: asset('storage/' . \$product->image)</h4>";
            echo "<p>URL: <a href='$correctUrl' target='_blank'>$correctUrl</a></p>";
            echo "<img src='$correctUrl' class='test-image' onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ ÉCHEC'; this.nextElementSibling.style.display='block';\">";
            echo "<p class='error' style='display:none;'>❌ ÉCHEC</p>";
            
            // Test fallback
            echo "<h4>🔄 Test fallback:</h4>";
            $fallbackUrl = asset('images/default-product.jpg');
            echo "<img src='$fallbackUrl' class='test-image' onerror=\"this.style.border='3px solid red';\">";
            
            echo '</div>';
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🔗 Test URLs directes</h2>
        
        <?php
        foreach ($products as $product) {
            $directUrl = '/storage/' . $product->image;
            echo "<div style='margin: 10px 0;'>";
            echo "<p><strong>URL directe:</strong> <a href='$directUrl' target='_blank'>$directUrl</a></p>";
            echo "<img src='$directUrl' class='test-image' style='max-width: 200px;' onerror=\"this.style.border='3px solid red';\">";
            echo "</div>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🎯 Conclusion</h2>
        <p class="success">✅ Si toutes les images s'affichent = UPLOAD CORRIGÉ !</p>
        <p class="error">❌ Si images ont bordures rouges = PROBLÈME PERSISTANT</p>
        
        <h3>📋 Actions à tester:</h3>
        <ol>
            <li><a href="/guineemall/vendeur/products" target="_blank">Interface vendeur - Liste produits</a></li>
            <li><a href="/guineemall/vendeur/products/create" target="_blank">Interface vendeur - Ajouter produit</a></li>
            <li><a href="/guineemall/client/catalog/show/2" target="_blank">Interface client - Détail produit</a></li>
        </ol>
    </div>
    
</body>
</html>
