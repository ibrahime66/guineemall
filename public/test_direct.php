<!DOCTYPE html>
<html>
<head>
    <title>Test Direct Images</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-item { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .test-image { max-width: 200px; height: auto; border: 1px solid #ddd; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Test Direct des Images</h1>
    
    <div class="test-item">
        <h2>Test des images produits avec URL directes</h2>
        
        <!-- Test 1: Storage URL -->
        <h3>Test 1: Storage URL</h3>
        <img src="http://localhost/guineemall/storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Storage URL 1 non accessible</p>
        
        <img src="http://localhost/guineemall/storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Storage URL 2 non accessible</p>
        
        <!-- Test 2: Asset URL -->
        <h3>Test 2: Asset URL</h3>
        <img src="/guineemall/storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Asset URL 1 non accessible</p>
        
        <img src="/guineemall/storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Asset URL 2 non accessible</p>
        
        <!-- Test 3: Relative URL -->
        <h3>Test 3: Relative URL</h3>
        <img src="storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Relative URL 1 non accessible</p>
        
        <img src="storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Relative URL 2 non accessible</p>
        
        <!-- Test 4: Logo vendeur -->
        <h3>Test 4: Logo Vendeur</h3>
        <img src="http://localhost/guineemall/storage/vendor-logos/WPwjRDwsd0zQn8siYUFjsWmScowhqkbrPL9jTuEH.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Logo vendeur non accessible</p>
        
        <img src="/guineemall/storage/vendor-logos/WPwjRDwsd0zQn8siYUFjsWmScowhqkbrPL9jTuEH.jpg" class="test-image" onerror="this.style.border='2px solid red'; this.nextElementSibling.style.display='block';">
        <p class="error" style="display:none;">❌ Logo vendeur asset non accessible</p>
    </div>
    
    <div class="test-item">
        <h2>Informations système</h2>
        <p><strong>Base URL:</strong> <?php echo base_url(); ?></p>
        <p><strong>Current URL:</strong> <?php echo $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
        <p><strong>Script Name:</strong> <?php echo $_SERVER['SCRIPT_NAME']; ?></p>
        
        <h3>Vérification des fichiers</h3>
        <?php
        $files = [
            'storage/products/Q1DcFMVOP9MYAUJqt4ccPUOBlcpLBCkZM2b4VdAq.jpg',
            'storage/products/lobDPKla2PyDaO1GgxcEYLzySncdkdcUO7WCO6SB.jpg',
            'storage/vendor-logos/WPwjRDwsd0zQn8siYUFjsWmScowhqkbrPL9jTuEH.jpg'
        ];
        
        foreach ($files as $file) {
            $fullPath = __DIR__ . '/' . $file;
            echo "<p><strong>$file:</strong> " . (file_exists($fullPath) ? '✅ EXISTS' : '❌ NOT FOUND') . "</p>";
            if (file_exists($fullPath)) {
                echo "<p>Size: " . filesize($fullPath) . " bytes</p>";
            }
        }
        ?>
    </div>
    
</body>
</html>
