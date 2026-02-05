<!DOCTYPE html>
<html>
<head>
    <title>Debug Upload Vendeur</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .debug-section { border: 1px solid #ddd; padding: 20px; margin: 20px 0; background: #f9f9f9; }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f0f0f0; padding: 10px; overflow-x: auto; }
        .form-test { background: white; padding: 20px; border: 2px solid #10b981; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🔍 DEBUG COMPLET UPLOAD VENDEUR</h1>
    
    <div class="debug-section">
        <h2>📋 Configuration PHP et Serveur</h2>
        
        <?php
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        echo '<h3>⚙️ Configuration PHP</h3>';
        echo '<p><strong>upload_max_filesize:</strong> ' . ini_get('upload_max_filesize') . '</p>';
        echo '<p><strong>post_max_size:</strong> ' . ini_get('post_max_size') . '</p>';
        echo '<p><strong>max_execution_time:</strong> ' . ini_get('max_execution_time') . 's</p>';
        echo '<p><strong>memory_limit:</strong> ' . ini_get('memory_limit') . '</p>';
        echo '<p><strong>file_uploads:</strong> ' . (ini_get('file_uploads') ? '✅ ON' : '❌ OFF') . '</p>';
        
        echo '<h3>📁 Permissions dossiers</h3>';
        $paths = [
            'storage/app/public' => storage_path('app/public'),
            'storage/app/public/products' => storage_path('app/public/products'),
            'public/storage' => public_path('storage'),
            'public/storage/products' => public_path('storage/products'),
        ];
        
        foreach ($paths as $name => $path) {
            $exists = is_dir($path);
            $writable = $exists && is_writable($path);
            $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
            
            echo "<p><strong>$name:</strong> ";
            echo $exists ? '✅ Existe' : '❌ Manquant';
            echo " | $writable ? '✅ Écriture' : '❌ Non-écriture'";
            echo " | Permissions: $perms</p>";
        }
        
        echo '<h3>🔗 Lien symbolique</h3>';
        $storageLink = public_path('storage');
        if (is_link($storageLink)) {
            echo '<p class="success">✅ Lien symbolique OK</p>';
            echo '<p>Cible: ' . readlink($storageLink) . '</p>';
        } else {
            echo '<p class="error">❌ Lien symbolique manquant</p>';
        }
        ?>
    </div>
    
    <div class="debug-section">
        <h2>🧪 Test Upload Identique au Formulaire Vendeur</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="debug-section">';
            echo '<h3>📊 Résultats du test</h3>';
            
            // Debug complet des données reçues
            echo '<h4>📋 Données POST reçues:</h4>';
            echo '<pre>' . print_r($_POST, true) . '</pre>';
            
            echo '<h4>📁 Fichiers reçus:</h4>';
            echo '<pre>' . print_r($_FILES, true) . '</pre>';
            
            if (isset($_FILES['image'])) {
                $file = $_FILES['image'];
                echo '<h4>🔍 Analyse du fichier:</h4>';
                echo '<p><strong>Erreur upload:</strong> ' . $file['error'] . ' (' . $this->getUploadErrorMessage($file['error']) . ')</p>';
                echo '<p><strong>Nom original:</strong> ' . htmlspecialchars($file['name']) . '</p>';
                echo '<p><strong>Type MIME:</strong> ' . $file['type'] . '</p>';
                echo '<p><strong>Taille:</strong> ' . number_format($file['size'] / 1024, 2) . ' KB</p>';
                echo '<p><strong>Fichier temporaire:</strong> ' . $file['tmp_name'] . '</p>';
                echo '<p><strong>Fichier temporaire existe:</strong> ' . (file_exists($file['tmp_name']) ? '✅ Oui' : '❌ Non') . '</p>';
                
                if ($file['error'] === UPLOAD_ERR_OK) {
                    // Test validation Laravel
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    
                    echo '<h4>✅ Validation Laravel:</h4>';
                    
                    if (!in_array($file['type'], $allowedTypes)) {
                        echo '<p class="error">❌ Type non autorisé: ' . $file['type'] . '</p>';
                    } else {
                        echo '<p class="success">✅ Type autorisé</p>';
                    }
                    
                    if ($file['size'] > $maxSize) {
                        echo '<p class="error">❌ Fichier trop volumineux: ' . number_format($file['size'] / 1024, 2) . ' KB > 2048 KB</p>';
                    } else {
                        echo '<p class="success">✅ Taille correcte</p>';
                    }
                    
                    // Test stockage
                    echo '<h4>💾 Test stockage:</h4>';
                    $uploadDir = storage_path('app/public/products/');
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                        echo '<p class="warning">⚠️ Dossier créé: ' . $uploadDir . '</p>';
                    }
                    
                    $filename = 'debug_' . time() . '_' . basename($file['name']);
                    $filepath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        echo '<p class="success">✅ Fichier déplacé avec succès</p>';
                        echo '<p><strong>Chemin:</strong> ' . $filepath . '</p>';
                        echo '<p><strong>URL publique:</strong> <a href="/storage/products/' . $filename . '" target="_blank">/storage/products/' . $filename . '</a></p>';
                        
                        // Test accès public
                        $publicPath = public_path('storage/products/' . $filename);
                        if (file_exists($publicPath)) {
                            echo '<p class="success">✅ Fichier accessible publiquement</p>';
                            echo '<img src="/storage/products/' . $filename . '" style="max-width: 200px; border: 1px solid #ddd;">';
                        } else {
                            echo '<p class="error">❌ Fichier non accessible publiquement</p>';
                        }
                    } else {
                        echo '<p class="error">❌ Erreur lors du déplacement du fichier</p>';
                        echo '<p>Vérifiez les permissions du dossier: ' . $uploadDir . '</p>';
                    }
                }
            }
            
            echo '</div>';
        }
        ?>
        
        <div class="form-test">
            <h3>📝 Test Formulaire (Identique au vendeur)</h3>
            <form method="POST" enctype="multipart/form-data">
                <div style="margin: 15px 0;">
                    <label><strong>Nom du produit:</strong></label><br>
                    <input type="text" name="name" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 15px 0;">
                    <label><strong>Description:</strong></label><br>
                    <textarea name="description" required style="width: 100%; padding: 8px; margin: 5px 0; height: 80px;"></textarea>
                </div>
                
                <div style="margin: 15px 0;">
                    <label><strong>Prix:</strong></label><br>
                    <input type="number" name="price" step="0.01" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 15px 0;">
                    <label><strong>Stock:</strong></label><br>
                    <input type="number" name="stock" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <div style="margin: 15px 0;">
                    <label><strong>Image (JPG, PNG, GIF - Max 2MB):</strong></label><br>
                    <input type="file" name="image" accept="image/*" required style="width: 100%; padding: 8px; margin: 5px 0;">
                </div>
                
                <button type="submit" style="background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    🚀 TESTER L'UPLOAD
                </button>
            </form>
        </div>
    </div>
    
    <div class="debug-section">
        <h2>🔧 Actions recommandées</h2>
        
        <h3>1. Test ce formulaire</h3>
        <p>Remplissez le formulaire ci-dessus avec une image depuis n'importe quel dossier de votre PC.</p>
        
        <h3>2. Vérifiez les résultats</h3>
        <ul>
            <li>Si l'upload fonctionne ici = problème dans le formulaire vendeur</li>
            <li>Si l'upload échoue ici = problème serveur/PHP</li>
            <li>Vérifiez les messages d'erreur détaillés ci-dessus</li>
        </ul>
        
        <h3>3. Test formulaire vendeur</h3>
        <p><a href="/guineemall/vendeur/products/create" target="_blank" style="background: #10b981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            📝 Ouvrir le formulaire vendeur
        </a></p>
    </div>
    
</body>
</html>

<?php
function getUploadErrorMessage($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            return "Le fichier dépasse la taille maximale autorisée par PHP";
        case UPLOAD_ERR_FORM_SIZE:
            return "Le fichier dépasse la taille maximale autorisée par le formulaire";
        case UPLOAD_ERR_PARTIAL:
            return "Le fichier n'a été que partiellement uploadé";
        case UPLOAD_ERR_NO_FILE:
            return "Aucun fichier n'a été uploadé";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Dossier temporaire manquant";
        case UPLOAD_ERR_CANT_WRITE:
            return "Échec de l'écriture du fichier sur le disque";
        case UPLOAD_ERR_EXTENSION:
            return "Une extension PHP a arrêté l'upload";
        default:
            return "Erreur inconnue";
    }
}
?>
