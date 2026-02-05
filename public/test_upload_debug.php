<!DOCTYPE html>
<html>
<head>
    <title>Test Upload Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { border: 1px solid #ddd; padding: 20px; margin: 20px 0; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        h1 { color: #10b981; }
        .form-test { background: #f9f9f9; padding: 20px; border-radius: 8px; }
        .result { background: #f0f8ff; padding: 15px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 DEBUG UPLOAD - TEST COMPLET</h1>
    
    <div class="test-section">
        <h2>📋 Configuration système</h2>
        
        <?php
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        echo '<h3>✅ Vérifications système</h3>';
        echo '<p><strong>PHP Upload Max Filesize:</strong> ' . ini_get('upload_max_filesize') . '</p>';
        echo '<p><strong>PHP Post Max Size:</strong> ' . ini_get('post_max_size') . '</p>';
        echo '<p><strong>Storage Link:</strong> ' . (is_link(public_path('storage')) ? '✅ Symbolic Link OK' : '❌ Link manquant') . '</p>';
        
        if (is_link(public_path('storage'))) {
            echo '<p><strong>Storage Target:</strong> ' . readlink(public_path('storage')) . '</p>';
        }
        
        echo '<h3>📁 Dossiers de stockage</h3>';
        echo '<p><strong>storage/app/public/products:</strong> ' . (is_dir(storage_path('app/public/products')) ? '✅ Existe' : '❌ Manquant') . '</p>';
        echo '<p><strong>public/storage/products:</strong> ' . (is_dir(public_path('storage/products')) ? '✅ Existe' : '❌ Manquant') . '</p>';
        echo '<p><strong>Permissions storage:</strong> ' . substr(sprintf('%o', fileperms(storage_path('app/public'))), -4) . '</p>';
        
        echo '<h3>📄 Fichiers existants</h3>';
        $files = glob(storage_path('app/public/products/*'));
        foreach ($files as $file) {
            $filename = basename($file);
            $size = filesize($file);
            echo "<p>📄 $filename ($size bytes)</p>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🧪 Test d'upload direct</h2>
        
        <div class="form-test">
            <form method="POST" enctype="multipart/form-data" action="">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
                    echo '<div class="result">';
                    
                    if ($_FILES['test_image']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = storage_path('app/public/products/');
                        $filename = 'test_' . time() . '_' . $_FILES['test_image']['name'];
                        $filepath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($_FILES['test_image']['tmp_name'], $filepath)) {
                            echo '<p class="success">✅ Upload réussi !</p>';
                            echo '<p><strong>Fichier:</strong> ' . $filename . '</p>';
                            echo '<p><strong>Taille:</strong> ' . filesize($filepath) . ' bytes</p>';
                            echo '<p><strong>URL publique:</strong> <a href="/storage/products/' . $filename . '" target="_blank">/storage/products/' . $filename . '</a></p>';
                            echo '<img src="/storage/products/' . $filename . '" style="max-width: 200px; border: 1px solid #ddd;">';
                        } else {
                            echo '<p class="error">❌ Erreur lors du déplacement du fichier</p>';
                        }
                    } else {
                        echo '<p class="error">❌ Erreur upload: ' . $_FILES['test_image']['error'] . '</p>';
                    }
                    
                    echo '</div>';
                }
                ?>
                
                <input type="hidden" name="test_upload" value="1">
                <div style="margin: 15px 0;">
                    <label for="test_image" style="display: block; margin-bottom: 10px; font-weight: bold;">
                        Choisissez une image depuis N'IMPORTE QUEL dossier de votre PC :
                    </label>
                    <input type="file" name="test_image" id="test_image" accept="image/*" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="submit" style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    🚀 Tester l'upload
                </button>
            </form>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
            <h4>📝 Instructions de test :</h4>
            <ol>
                <li>Cliquez sur "Choisissez un fichier"</li>
                <li>Naviguez vers N'IMPORTE QUEL dossier (Bureau, Téléchargements, Documents, etc.)</li>
                <li>Sélectionnez une image (.jpg, .png, .gif)</li>
                <li>Cliquez sur "Tester l'upload"</li>
                <li>Vérifiez que l'image s'affiche ci-dessous</li>
            </ol>
        </div>
    </div>
    
    <div class="test-section">
        <h2>🔗 Test URLs existantes</h2>
        
        <?php
        $existingFiles = glob(storage_path('app/public/products/*'));
        foreach ($existingFiles as $file) {
            $filename = basename($file);
            $url = '/storage/products/' . $filename;
            echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>";
            echo "<p><strong>$filename</strong></p>";
            echo "<p>URL: <a href='$url' target='_blank'>$url</a></p>";
            echo "<img src='$url' style='max-width: 150px; border: 1px solid #ddd;' onerror=\"this.style.border='3px solid red'; this.nextElementSibling.innerHTML='❌ NON ACCESSIBLE';\">";
            echo "<p class='error' style='display:none;'></p>";
            echo "</div>";
        }
        ?>
    </div>
    
    <div class="test-section">
        <h2>🎯 Actions recommandées</h2>
        
        <h3>1. Test formulaire vendeur</h3>
        <p><a href="/guineemall/vendeur/products/create" target="_blank" style="background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            📝 Tester le formulaire vendeur
        </a></p>
        
        <h3>2. Test upload complet</h3>
        <p>Utilisez le formulaire ci-dessus pour tester l'upload depuis différents dossiers de votre PC.</p>
        
        <h3>3. Vérification résultats</h3>
        <p>Si l'upload fonctionne ici, le problème est probablement dans le formulaire ou le JavaScript côté vendeur.</p>
    </div>
    
</body>
</html>
