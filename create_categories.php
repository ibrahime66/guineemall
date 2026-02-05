<?php

// Script CRÉATIF pour résoudre définitivement le problème des catégories

echo "🔥 SOLUTION DÉFINITIVE - CRÉATION DE CATÉGORIES\n";
echo "===============================================\n\n";

try {
    // Charger Laravel
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ Laravel chargé\n";

    // Étape 1: Créer la table si elle n'existe pas
    if (!\Schema::hasTable('categories')) {
        echo "🚀 Création de la table categories...\n";
        \Schema::create('categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
        echo "✅ Table créée\n";
    }

    // Étape 2: Insérer les catégories
    echo "\n📦 Insertion des catégories...\n";
    
    $categories = [
        ['name' => 'Électronique', 'slug' => 'electronique'],
        ['name' => 'Vêtements', 'slug' => 'vetements'],
        ['name' => 'Alimentation', 'slug' => 'alimentation'],
        ['name' => 'Maison', 'slug' => 'maison'],
        ['name' => 'Beauté', 'slug' => 'beaute'],
        ['name' => 'Sports', 'slug' => 'sports'],
    ];

    foreach ($categories as $cat) {
        $category = \App\Models\Category::updateOrCreate(
            ['slug' => $cat['slug']],
            [
                'name' => $cat['name'],
                'description' => "Catégorie {$cat['name']}",
                'status' => 'active',
            ]
        );
        echo "✅ {$category->name} (ID: {$category->id})\n";
    }

    // Étape 3: Vérification finale
    echo "\n🔍 Vérification finale...\n";
    $count = \App\Models\Category::where('status', 'active')->count();
    echo "📊 Total catégories actives: {$count}\n";

    if ($count > 0) {
        echo "\n🎉 SUCCÈS! Les catégories sont maintenant disponibles!\n";
        echo "🔗 Accès au formulaire: http://127.0.0.1:8000/vendeur/products/create\n";
    } else {
        echo "\n❌ ÉCHEC: Aucune catégorie créée\n";
    }

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
}
?>
