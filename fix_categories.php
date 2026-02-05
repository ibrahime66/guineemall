<?php

// Script pour corriger le problème des catégories

echo "🔧 CORRECTION DES CATÉGORIES - GuinéeMall\n";
echo "==========================================\n\n";

// Étape 1: Vérifier si les catégories existent
echo "1️⃣ Vérification des catégories existantes...\n";

try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $categories = \App\Models\Category::all();
    echo "   📊 Catégories trouvées: " . $categories->count() . "\n";
    
    if ($categories->count() === 0) {
        echo "   ❌ Aucune catégorie trouvée!\n";
        echo "   🚀 Lancement du seeder de catégories...\n";
        
        // Lancer le seeder
        \Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\CategorySeeder']);
        echo "   ✅ Seeder exécuté: " . \Artisan::output() . "\n";
        
        // Vérifier à nouveau
        $categories = \App\Models\Category::all();
        echo "   📊 Nouveau total de catégories: " . $categories->count() . "\n";
    } else {
        echo "   ✅ Catégories déjà présentes:\n";
        foreach ($categories as $cat) {
            echo "      - {$cat->name} (status: {$cat->status})\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    echo "   🔄 Tentative de création manuelle...\n";
    
    // Création manuelle si le seeder échoue
    try {
        $defaultCategories = [
            ['name' => 'Électronique', 'slug' => 'electronique', 'status' => 'active'],
            ['name' => 'Vêtements & Mode', 'slug' => 'vetements-mode', 'status' => 'active'],
            ['name' => 'Alimentation & Boissons', 'slug' => 'alimentation-boissons', 'status' => 'active'],
            ['name' => 'Maison & Jardin', 'slug' => 'maison-jardin', 'status' => 'active'],
            ['name' => 'Beauté & Santé', 'slug' => 'beaute-sante', 'status' => 'active'],
            ['name' => 'Sports & Loisirs', 'slug' => 'sports-loisirs', 'status' => 'active'],
        ];
        
        foreach ($defaultCategories as $catData) {
            \App\Models\Category::create($catData);
            echo "   ✅ Catégorie '{$catData['name']}' créée\n";
        }
    } catch (Exception $e2) {
        echo "   ❌ Erreur lors de la création manuelle: " . $e2->getMessage() . "\n";
    }
}

echo "\n2️⃣ Test du ProductService...\n";

try {
    $service = new \App\Services\Vendeur\ProductService();
    $categoriesFromService = $service->getCategories();
    echo "   📊 Catégories depuis service: " . $categoriesFromService->count() . "\n";
    
    if ($categoriesFromService->count() > 0) {
        echo "   ✅ Service fonctionne correctement!\n";
        foreach ($categoriesFromService as $cat) {
            echo "      - {$cat->id}: {$cat->name}\n";
        }
    } else {
        echo "   ❌ Le service ne retourne aucune catégorie\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur dans le service: " . $e->getMessage() . "\n";
}

echo "\n3️⃣ Vérification des permissions...\n";

// Vérifier si la table categories existe
try {
    $tables = \DB::select("SHOW TABLES LIKE 'categories'");
    if (count($tables) > 0) {
        echo "   ✅ Table 'categories' existe\n";
        
        // Vérifier la structure
        $columns = \DB::select("DESCRIBE categories");
        echo "   📋 Colonnes: ";
        foreach ($columns as $col) {
            echo $col->Field . " ";
        }
        echo "\n";
    } else {
        echo "   ❌ Table 'categories' n'existe pas!\n";
        echo "   🚀 Lancement des migrations...\n";
        
        \Artisan::call('migrate');
        echo "   ✅ Migrations exécutées\n";
    }
} catch (Exception $e) {
    echo "   ❌ Erreur de vérification: " . $e->getMessage() . "\n";
}

echo "\n🎉 OPÉRATION TERMINÉE!\n";
echo "========================\n";
echo "Maintenant vous devriez pouvoir voir les catégories\n";
echo "dans le formulaire d'ajout de produit vendeur.\n\n";
echo "🔗 Accès rapide:\n";
echo "- Espace vendeur: http://127.0.0.1:8000/vendeur/dashboard\n";
echo "- Ajout produit: http://127.0.0.1:8000/vendeur/products/create\n";
?>
