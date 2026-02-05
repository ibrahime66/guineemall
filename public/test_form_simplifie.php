<!DOCTYPE html>
<html>
<head>
    <title>Test Formulaire Simplifié</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .form-container { background: #f8f9fa; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        h1 { color: #10b981; text-align: center; }
        .file-input { width: 100%; padding: 15px; border: 2px dashed #ddd; border-radius: 8px; margin: 20px 0; text-align: center; }
        .submit-btn { background: #10b981; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; }
        .result { margin: 20px 0; padding: 20px; border-radius: 8px; }
        .result.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .result.error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .preview { max-width: 300px; max-height: 300px; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0; }
        .debug-info { background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <h1>🧪 TEST FORMULAIRE SIMPLIFIÉ</h1>
    
    <div class="form-container">
        <h2>Test d'upload SANS JavaScript - PURE HTML/PHP</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="result">';
            
            // Debug complet
            echo '<h3>📊 Debug Upload</h3>';
            echo '<div class="debug-info">';
            echo '<strong>$_FILES:</strong><br>';
            echo '<pre>' . print_r($_FILES, true) . '</pre>';
            echo '</div>';
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['image'];
                
                echo '<h3 class="success">✅ FICHIER REÇU</h3>';
                echo '<p><strong>Nom:</strong> ' . htmlspecialchars($file['name']) . '</p>';
                echo '<p><strong>Type:</strong> ' . $file['type'] . '</p>';
                echo '<p><strong>Taille:</strong> ' . number_format($file['size'] / 1024, 2) . ' KB</p>';
                echo '<p><strong>Temporaire:</strong> ' . $file['tmp_name'] . '</p>';
                echo '<p><strong>Existe:</strong> ' . (file_exists($file['tmp_name']) ? '✅ Oui' : '❌ Non') . '</p>';
                
                // Validation
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                $maxSize = 2 * 1024 * 1024;
                
                echo '<h3>🔍 Validation</h3>';
                
                if (!in_array($file['type'], $allowedTypes)) {
                    echo '<p class="error">❌ Type non autorisé: ' . $file['type'] . '</p>';
                } else {
                    echo '<p class="success">✅ Type autorisé</p>';
                }
                
                if ($file['size'] > $maxSize) {
                    echo '<p class="error">❌ Trop volumineux: ' . number_format($file['size'] / 1024, 2) . ' KB > 2048 KB</p>';
                } else {
                    echo '<p class="success">✅ Taille correcte</p>';
                }
                
                // Test stockage
                if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
                    $uploadDir = '../storage/app/public/products/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                        echo '<p class="warning">⚠️ Dossier créé: ' . $uploadDir . '</p>';
                    }
                    
                    $filename = 'test_' . time() . '_' . basename($file['name']);
                    $filepath = $uploadDir . $filename;
                    
                    echo '<h3>💾 Stockage</h3>';
                    echo '<p><strong>Dossier:</strong> ' . $uploadDir . '</p>';
                    echo '<p><strong>Permissions:</strong> ' . substr(sprintf('%o', fileperms($uploadDir)), -4) . '</p>';
                    echo '<p><strong>Writable:</strong> ' . (is_writable($uploadDir) ? '✅ Oui' : '❌ Non') . '</p>';
                    
                    if (move_uploaded_file($file['tmp_name'], $filepath)) {
                        echo '<p class="success">✅ FICHIER STOCKÉ AVEC SUCCÈS !</p>';
                        echo '<p><strong>Chemin:</strong> ' . realpath($filepath) . '</p>';
                        echo '<p><strong>URL:</strong> <a href="/storage/products/' . $filename . '" target="_blank">/storage/products/' . $filename . '</a></p>';
                        
                        // Test accès public
                        $publicPath = '../public/storage/products/' . $filename;
                        if (file_exists($publicPath)) {
                            echo '<p class="success">✅ Accessible publiquement</p>';
                            echo '<img src="/storage/products/' . $filename . '" class="preview" alt="Image uploadée">';
                        } else {
                            echo '<p class="error">❌ Non accessible publiquement (problème lien symbolique)</p>';
                        }
                        
                        echo '<div class="result success">';
                        echo '<h4>🎉 CONCLUSION</h4>';
                        echo '<p>L\'upload fonctionne parfaitement ! Le problème est dans le formulaire vendeur (JavaScript, validation, etc.)</p>';
                        echo '</div>';
                        
                    } else {
                        echo '<p class="error">❌ ERREUR STOCKAGE</p>';
                        echo '<p>move_uploaded_file() a échoué</p>';
                        echo '<p>Vérifiez les permissions du dossier: ' . $uploadDir . '</p>';
                    }
                }
                
            } else {
                echo '<h3 class="error">❌ ERREUR UPLOAD</h3>';
                if (isset($_FILES['image'])) {
                    echo '<p><strong>Code erreur:</strong> ' . $_FILES['image']['error'] . '</p>';
                    $errorMessages = [
                        1 => "Fichier trop gros (upload_max_filesize)",
                        2 => "Fichier trop gros (MAX_FILE_SIZE)",
                        3 => "Upload partiel",
                        4 => "Aucun fichier uploadé",
                        6 => "Dossier temporaire manquant",
                        7 => "Échec écriture disque",
                        8 => "Extension PHP bloquée"
                    ];
                    echo '<p><strong>Message:</strong> ' . ($errorMessages[$_FILES['image']['error']] ?? 'Erreur inconnue') . '</p>';
                } else {
                    echo '<p>Aucun fichier reçu</p>';
                }
            }
            
            echo '</div>';
        }
        ?>
        
        <form method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
            <h3>📝 Formulaire de Test (SANS JavaScript)</h3>
            
            <div class="file-input">
                <label for="image" style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 18px;">
                    📁 CHOISISSEZ UNE IMAGE DEPUIS N'IMPORTE QUEL DOSSIER :
                </label>
                <input type="file" 
                       name="image" 
                       id="image" 
                       accept="image/*" 
                       required 
                       style="font-size: 16px; padding: 10px;">
                <small style="display: block; margin-top: 10px; color: #666;">
                    Formats: JPG, PNG, GIF | Max: 2MB
                </small>
            </div>
            
            <button type="submit" class="submit-btn">
                🚀 TESTER L'UPLOAD MAINTENANT
            </button>
        </form>
        
        <div style="margin-top: 30px; padding: 20px; background: #e3f2fd; border-radius: 8px;">
            <h3>📋 Instructions</h3>
            <ol>
                <li><strong>Cliquez</strong> sur "Choisir un fichier"</li>
                <li><strong>Naviguez</strong> vers Bureau, Téléchargements, Documents, etc.</li>
                <li><strong>Sélectionnez</strong> une image (.jpg, .png, .gif)</li>
                <li><strong>Cliquez</strong> sur "TESTER L'UPLOAD"</li>
                <li><strong>Analysez</strong> les résultats ci-dessus</li>
            </ol>
            
            <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 5px;">
                <h4>🎯 Objectif</h4>
                <p>Ce test élimine tout JavaScript et validation complexe. Si ça fonctionne ici, le problème est dans le formulaire vendeur.</p>
            </div>
        </div>
    </div>
    
</body>
</html>
