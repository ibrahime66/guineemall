<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Vérifier les catégories
echo "=== VÉRIFICATION DES CATÉGORIES ===\n\n";

$categories = \App\Models\Category::all();

echo "Nombre total de catégories: " . $categories->count() . "\n\n";

if ($categories->count() > 0) {
    echo "Liste des catégories:\n";
    foreach ($categories as $category) {
        echo "- ID: {$category->id} | Nom: {$category->name} | Status: {$category->status}\n";
    }
} else {
    echo "❌ Aucune catégorie trouvée dans la base de données!\n";
    echo "\nCréation de catégories par défaut...\n";
    
    // Créer quelques catégories par défaut
    $defaultCategories = [
        ['name' => 'Électronique', 'slug' => 'electronique', 'status' => 'active'],
        ['name' => 'Vêtements', 'slug' => 'vetements', 'status' => 'active'],
        ['name' => 'Alimentation', 'slug' => 'alimentation', 'status' => 'active'],
        ['name' => 'Maison & Jardin', 'slug' => 'maison-jardin', 'status' => 'active'],
        ['name' => 'Beauté & Santé', 'slug' => 'beaute-sante', 'status' => 'active'],
        ['name' => 'Sports & Loisirs', 'slug' => 'sports-loisirs', 'status' => 'active'],
    ];
    
    foreach ($defaultCategories as $catData) {
        \App\Models\Category::create($catData);
        echo "✅ Catégorie '{$catData['name']}' créée\n";
    }
    
    echo "\n🎉 Catégories par défaut créées avec succès!\n";
}

echo "\n=== VÉRIFICATION DU SERVICE ===\n";

try {
    $service = new \App\Services\Vendeur\ProductService();
    $categoriesFromService = $service->getCategories();
    
    echo "Catégories depuis le service: " . $categoriesFromService->count() . "\n";
    
    foreach ($categoriesFromService as $category) {
        echo "- {$category->id}: {$category->name}\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur dans le service: " . $e->getMessage() . "\n";
}

echo "\n=== TERMINÉ ===\n";
?>
