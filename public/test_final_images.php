<!DOCTYPE html>
<html>
<head>
    <title>Test Final Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-image { max-width: 400px; height: auto; border: 2px solid #ddd; margin: 10px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #10b981; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <h1>🎯 TEST FINAL IMAGES - PAGE DÉTAIL PRODUIT</h1>
    
    <div class="grid">
        <?php
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        $products = \App\Models\Product::whereNotNull('image')->take(3)->get();
        
        foreach ($products as $product) {
            echo '<div style="border: 1px solid #ddd; padding: 20px; margin: 20px 0;">';
            echo "<h2>📦 {$product->name}</h2>";
            echo "<p><strong>ID:</strong> {$product->id}</p>";
            echo "<p><strong>Champ image:</strong> {$product->image}</p>";
            
            // Test 1: asset('storage/' . $product->image)
            $url1 = asset('storage/' . $product->image);
            echo "<h3>✅ Test 1: asset('storage/' . \$product->image)</h3>";
            echo "<p>URL: <a href='$url1' target='_blank'>$url1</a></p>";
            echo "<img src='$url1' class='test-image' onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ ÉCHEC'; this.nextElementSibling.style.display='block';\">";
            echo "<p class='error' style='display:none;'>❌ ÉCHEC</p>";
            
            // Test 2: $product->image_url (modèle)
            $url2 = $product->image_url;
            echo "<h3>🔧 Test 2: \$product->image_url (modèle)</h3>";
            echo "<p>URL: <a href='$url2' target='_blank'>$url2</a></p>";
            echo "<img src='$url2' class='test-image' onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ ÉCHEC'; this.nextElementSibling.style.display='block';\">";
            echo "<p class='error' style='display:none;'>❌ ÉCHEC</p>";
            
            // Test 3: URL directe
            $url3 = '/storage/' . $product->image;
            echo "<h3>🌐 Test 3: URL directe /storage/...</h3>";
            echo "<p>URL: <a href='$url3' target='_blank'>$url3</a></p>";
            echo "<img src='$url3' class='test-image' onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ ÉCHEC'; this.nextElementSibling.style.display='block';\">";
            echo "<p class='error' style='display:none;'>❌ ÉCHEC</p>";
            
            // Vérification fichiers
            echo "<h3>📁 Vérification fichiers</h3>";
            $storagePath = storage_path('app/public/' . $product->image);
            $publicPath = public_path('storage/' . $product->image);
            echo "<p>Storage: " . (file_exists($storagePath) ? '✅' : '❌') . " $storagePath</p>";
            echo "<p>Public: " . (file_exists($publicPath) ? '✅' : '❌') . " $publicPath</p>";
            
            echo '</div>';
        }
        ?>
    </div>
    
    <div style="margin: 40px 0; padding: 20px; border: 1px solid #ddd;">
        <h2>🎯 Conclusion</h2>
        <p class="success">✅ Si les images s'affichent dans Test 1 = PROBLÈME RÉSOLU !</p>
        <p class="error">❌ Si toutes les images ont des bordures rouges = PROBLÈME PERSISTANT</p>
        <p class="info">📞 Testez ensuite: <a href="/guineemall/client/catalog/show/2" target="_blank">Page détail produit</a></p>
    </div>
    
</body>
</html>
