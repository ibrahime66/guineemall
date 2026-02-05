<!DOCTYPE html>
<html>
<head>
    <title>Test Simple Upload</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .form-container { background: #f8f9fa; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #007bff; }
        h1 { color: #10b981; text-align: center; }
        .file-input { width: 100%; padding: 15px; border: 2px dashed #ddd; border-radius: 8px; margin: 20px 0; }
        .submit-btn { background: #10b981; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; }
        .result { margin: 20px 0; padding: 20px; border-radius: 8px; }
        .result.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .result.error { background: #f8d7da; border: 1px solid #f5c6cb; }
        .preview { max-width: 300px; max-height: 300px; border: 1px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>🚀 TEST UPLOAD IMAGES</h1>
    
    <div class="form-container">
        <h2>Testez l'upload depuis n'importe quel dossier de votre PC</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            echo '<div class="result ' . ($_FILES['image']['error'] === UPLOAD_ERR_OK ? 'success' : 'error') . '">';
            
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Vérifications basiques
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                $fileType = $_FILES['image']['type'];
                $fileSize = $_FILES['image']['size'];
                
                if (!in_array($fileType, $allowedTypes)) {
                    echo '<p class="error">❌ Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.</p>';
                } elseif ($fileSize > 2 * 1024 * 1024) {
                    echo '<p class="error">❌ Fichier trop volumineux. Maximum 2MB.</p>';
                } else {
                    // Simulation du stockage Laravel
                    $uploadDir = storage_path('app/public/products/');
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $filename = 'upload_' . time() . '_' . basename($_FILES['image']['name']);
                    $filepath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                        echo '<h3 class="success">✅ UPLOAD RÉUSSI !</h3>';
                        echo '<p><strong>Fichier original:</strong> ' . htmlspecialchars($_FILES['image']['name']) . '</p>';
                        echo '<p><strong>Nouveau nom:</strong> ' . $filename . '</p>';
                        echo '<p><strong>Taille:</strong> ' . number_format($fileSize / 1024, 2) . ' KB</p>';
                        echo '<p><strong>Type:</strong> ' . $fileType . '</p>';
                        echo '<p><strong>Chemin serveur:</strong> ' . $filepath . '</p>';
                        echo '<p><strong>URL publique:</strong> <a href="/storage/products/' . $filename . '" target="_blank">/storage/products/' . $filename . '</a></p>';
                        
                        // Affichage de l'image uploadée
                        echo '<h4>Image uploadée:</h4>';
                        echo '<img src="/storage/products/' . $filename . '" class="preview" alt="Image uploadée">';
                        
                        // Test d'accès
                        if (file_exists(public_path('storage/products/' . $filename))) {
                            echo '<p class="success">✅ Fichier accessible publiquement</p>';
                        } else {
                            echo '<p class="error">❌ Fichier non accessible publiquement (problème de lien symbolique)</p>';
                        }
                    } else {
                        echo '<p class="error">❌ Erreur lors du déplacement du fichier</p>';
                        echo '<p>Vérifiez les permissions du dossier storage/app/public/products</p>';
                    }
                }
            } else {
                echo '<p class="error">❌ Erreur upload: ' . $_FILES['image']['error'] . '</p>';
                echo '<p>Codes erreur: 1=Fichier trop grand, 4=Aucun fichier, 6=Dossier temporaire manquant</p>';
            }
            
            echo '</div>';
        }
        ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="file-input">
                <label for="image" style="display: block; margin-bottom: 10px; font-weight: bold;">
                    📁 Sélectionnez une image depuis N'IMPORTE QUEL dossier de votre ordinateur :
                </label>
                <input type="file" 
                       name="image" 
                       id="image" 
                       accept="image/*" 
                       required 
                       style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <small style="color: #666; display: block; margin-top: 10px;">
                    Formats acceptés: JPG, PNG, GIF | Taille max: 2MB
                </small>
            </div>
            
            <button type="submit" class="submit-btn">
                🚀 LANCER L'UPLOAD
            </button>
        </form>
        
        <div style="margin-top: 30px; padding: 20px; background: #e3f2fd; border-radius: 8px;">
            <h3>📋 Étapes de test :</h3>
            <ol>
                <li><strong>Cliquez sur "Choisir un fichier"</strong></li>
                <li><strong>Naviguez</strong> vers Bureau, Téléchargements, Documents, ou autre dossier</li>
                <li><strong>Sélectionnez</strong> une image (.jpg, .png, .gif)</li>
                <li><strong>Cliquez</strong> sur "LANCER L'UPLOAD"</li>
                <li><strong>Vérifiez</strong> que l'image s'affiche ci-dessus</li>
            </ol>
            
            <p style="margin-top: 15px; color: #d32f2f; font-weight: bold;">
                ⚠️ Si ce test fonctionne, l'upload fonctionne correctement. Le problème est alors dans le formulaire vendeur.
            </p>
        </div>
    </div>
    
    <?php
    // Afficher les images déjà uploadées
    $existingFiles = glob(storage_path('app/public/products/upload_*'));
    if (!empty($existingFiles)) {
        echo '<div style="margin-top: 40px; padding: 20px; background: #f5f5f5; border-radius: 8px;">';
        echo '<h3>📁 Images uploadées précédemment:</h3>';
        
        foreach (array_slice($existingFiles, -5) as $file) {
            $filename = basename($file);
            $url = '/storage/products/' . $filename;
            echo '<div style="display: inline-block; margin: 10px; text-align: center;">';
            echo '<img src="' . $url . '" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">';
            echo '<p style="font-size: 12px; margin: 5px 0;">' . substr($filename, 0, 20) . '...</p>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    ?>
    
</body>
</html>
