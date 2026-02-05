<!DOCTYPE html>
<html>
<head>
    <title>Debug Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-item { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .test-image { max-width: 300px; height: auto; border: 2px solid #ddd; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #10b981; }
    </style>
</head>
<body>
    <h1>🔍 DEBUG IMAGES - DIAGNOSTIC COMPLET</h1>
    
    <?php
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo '<div class="test-item">';
    echo '<h2>📊 Produits avec images en base</h2>';
    
    $products = \App\Models\Product::whereNotNull('image')->get();
    
    foreach ($products as $index => $product) {
        echo "<div style='margin: 20px 0; padding: 15px; border: 1px solid #ddd;'>";
        echo "<h3>📦 Produit " . ($index + 1) . ": {$product->name}</h3>";
        echo "<p><strong>ID:</strong> {$product->id}</p>";
        echo "<p><strong>Champ image:</strong> {$product->image}</p>";
        echo "<p><strong>URL générée:</strong> <a href='{$product->image_url}' target='_blank'>{$product->image_url}</a></p>";
        
        // Test si le fichier existe dans storage
        $storagePath = storage_path('app/public/' . $product->image);
        echo "<p><strong>Storage path:</strong> $storagePath</p>";
        echo "<p><strong>Fichier existe (storage):</strong> " . (file_exists($storagePath) ? '✅ YES' : '❌ NO') . "</p>";
        
        // Test si le fichier existe dans public/storage
        $publicPath = public_path('storage/' . $product->image);
        echo "<p><strong>Public path:</strong> $publicPath</p>";
        echo "<p><strong>Fichier existe (public):</strong> " . (file_exists($publicPath) ? '✅ YES' : '❌ NO') . "</p>";
        
        // Test de l'image
        echo "<img src='{$product->image_url}' class='test-image' 
             onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ IMAGE NON ACCESSIBLE'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ Image non accessible</p>";
        
        // Test direct URL
        $directUrl = '/storage/' . $product->image;
        echo "<h4>Test URL direct: <a href='$directUrl' target='_blank'>$directUrl</a></h4>";
        echo "<img src='$directUrl' class='test-image' 
             onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ URL DIRECT NON ACCESSIBLE'; this.nextElementSibling.style.display='block';\">";
        echo "<p class='error' style='display:none;'>❌ URL direct non accessible</p>";
        
        echo "</div>";
    }
    
    echo '</div>';
    
    echo '<div class="test-item">';
    echo '<h2>📁 Fichiers dans les dossiers</h2>';
    
    echo '<h3>Storage/app/public/products:</h3>';
    $storageFiles = glob(storage_path('app/public/products/*'));
    foreach ($storageFiles as $file) {
        $filename = basename($file);
        $size = filesize($file);
        echo "<p>📄 $filename ($size bytes)</p>";
    }
    
    echo '<h3>Public/storage/products:</h3>';
    $publicFiles = glob(public_path('storage/products/*'));
    foreach ($publicFiles as $file) {
        $filename = basename($file);
        $size = filesize($file);
        echo "<p>📄 $filename ($size bytes)</p>";
    }
    
    echo '</div>';
    
    echo '<div class="test-item">';
    echo '<h2>🌐 Configuration serveur</h2>';
    echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
    echo "<p><strong>Current URL:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
    echo "<p><strong>Base URL:</strong> " . asset('') . "</p>";
    echo "<p><strong>Storage link exists:</strong> " . (is_link(public_path('storage')) ? '✅ YES' : '❌ NO') . "</p>";
    
    if (is_link(public_path('storage'))) {
        $target = readlink(public_path('storage'));
        echo "<p><strong>Storage link target:</strong> $target</p>";
    }
    
    echo '</div>';
    ?>
    
    <div class="test-item">
        <h2>🎯 Tests manuels</h2>
        <p>Cliquez sur ces liens pour tester manuellement:</p>
        <?php
        foreach ($products as $product) {
            $url = '/storage/' . $product->image;
            echo "<p><a href='$url' target='_blank'>$url</a></p>";
        }
        ?>
    </div>
    
</body>
</html>
