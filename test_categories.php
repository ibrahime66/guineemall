<?php

// Script de test et correction complet pour les catégories

echo "🔥 SOLUTION DÉFINITIVE POUR LES CATÉGORIES\n";
echo "==========================================\n\n";

try {
    // Charger Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ Laravel chargé avec succès\n\n";

    // Étape 1: Vérifier et créer la table si nécessaire
    echo "1️⃣ Vérification de la table 'categories'...\n";
    
    try {
        $tableExists = \Schema::hasTable('categories');
        if (!$tableExists) {
            echo "   ❌ Table 'categories' n'existe pas\n";
            echo "   🚀 Création de la table...\n";
            
            \Schema::create('categories', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
            
            echo "   ✅ Table 'categories' créée\n";
        } else {
            echo "   ✅ Table 'categories' existe\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur table: " . $e->getMessage() . "\n";
    }

    // Étape 2: Insérer des catégories de test
    echo "\n2️⃣ Insertion des catégories...\n";
    
    $categories = [
        ['name' => 'Électronique', 'slug' => 'electronique', 'description' => 'Smartphones, ordinateurs, tablettes'],
        ['name' => 'Vêtements', 'slug' => 'vetements', 'description' => 'Mode pour hommes, femmes, enfants'],
        ['name' => 'Alimentation', 'slug' => 'alimentation', 'description' => 'Produits alimentaires et boissons'],
        ['name' => 'Maison', 'slug' => 'maison', 'description' => 'Meubles et décoration'],
        ['name' => 'Beauté', 'slug' => 'beaute', 'description' => 'Cosmétiques et produits de beauté'],
        ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Équipements sportifs'],
    ];

    foreach ($categories as $catData) {
        try {
            $category = \App\Models\Category::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            echo "   ✅ Catégorie '{$category->name}' (ID: {$category->id})\n";
        } catch (Exception $e) {
            echo "   ❌ Erreur insertion: " . $e->getMessage() . "\n";
        }
    }

    // Étape 3: Vérifier les catégories
    echo "\n3️⃣ Vérification finale...\n";
    
    $allCategories = \App\Models\Category::all();
    echo "   📊 Total catégories: " . $allCategories->count() . "\n";
    
    $activeCategories = \App\Models\Category::where('status', 'active')->get();
    echo "   📊 Catégories actives: " . $activeCategories->count() . "\n";
    
    echo "\n   📋 Liste des catégories actives:\n";
    foreach ($activeCategories as $cat) {
        echo "      - ID: {$cat->id} | Nom: {$cat->name} | Slug: {$cat->slug}\n";
    }

    // Étape 4: Tester le ProductService
    echo "\n4️⃣ Test du ProductService...\n";
    
    try {
        $service = new \App\Services\Vendeur\ProductService();
        $serviceCategories = $service->getCategories();
        echo "   📊 Catégories depuis service: " . $serviceCategories->count() . "\n";
        
        if ($serviceCategories->count() > 0) {
            echo "   ✅ ProductService fonctionne!\n";
        } else {
            echo "   ❌ ProductService ne retourne rien\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur ProductService: " . $e->getMessage() . "\n";
    }

    // Étape 5: Créer un test simple
    echo "\n5️⃣ Test du controller...\n";
    
    try {
        // Simuler ce que le controller fait
        $categories = \App\Models\Category::where('status', 'active')
            ->orderBy('name')
            ->get();
            
        echo "   📊 Categories pour la vue: " . $categories->count() . "\n";
        
        if ($categories->count() > 0) {
            echo "   ✅ Controller peut récupérer les catégories!\n";
            
            // Créer un mini-test de la vue
            echo "\n   🧪 Test de la vue (simulation):\n";
            echo "   <select name='category_id'>\n";
            echo "   <option value=''>Sélectionnez une catégorie</option>\n";
            foreach ($categories as $cat) {
                echo "   <option value='{$cat->id}'>{$cat->name}</option>\n";
            }
            echo "   </select>\n";
        } else {
            echo "   ❌ Controller ne trouve pas de catégories\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Erreur controller: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 OPÉRATION TERMINÉE!\n";
    echo "========================\n";
    echo "✅ Les catégories sont maintenant disponibles!\n";
    echo "✅ Le formulaire vendeur devrait fonctionner!\n\n";
    echo "🔗 Prochaines étapes:\n";
    echo "1. Allez sur: http://127.0.0.1:8000/vendeur/products/create\n";
    echo "2. La liste déroulante devrait afficher les catégories\n";
    echo "3. Sélectionnez une catégorie et testez le formulaire\n\n";
    echo "Si le problème persiste, vérifiez:\n";
    echo "- Les logs Laravel: storage/logs/laravel.log\n";
    echo "- La console du navigateur (F12)\n";
    echo "- Les erreurs PHP dans les logs du serveur\n";

} catch (Exception $e) {
    echo "❌ ERREUR CRITIQUE: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
