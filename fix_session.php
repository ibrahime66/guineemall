<?php

// Script pour corriger les problèmes de session
echo "=== CORRECTION DES PROBLÈMES DE SESSION ===\n\n";

// 1. Vider le cache des routes
echo "1. Vider le cache des routes...\n";
exec('php artisan route:clear 2>&1', $output1);
echo implode("\n", $output1) . "\n\n";

// 2. Vider le cache de configuration
echo "2. Vider le cache de configuration...\n";
exec('php artisan config:clear 2>&1', $output2);
echo implode("\n", $output2) . "\n\n";

// 3. Vider le cache d'application
echo "3. Vider le cache d'application...\n";
exec('php artisan cache:clear 2>&1', $output3);
echo implode("\n", $output3) . "\n\n";

// 4. Vider le cache des vues
echo "4. Vider le cache des vues...\n";
exec('php artisan view:clear 2>&1', $output4);
echo implode("\n", $output4) . "\n\n";

// 5. Optimiser l'application
echo "5. Optimiser l'application...\n";
exec('php artisan optimize 2>&1', $output5);
echo implode("\n", $output5) . "\n\n";

// 6. Recréer la table de sessions si nécessaire
echo "6. Vérifier la table de sessions...\n";
exec('php artisan session:table 2>&1', $output6);
echo implode("\n", $output6) . "\n\n";

echo "=== TERMINÉ ===\n";
echo "Maintenant essayez de vous connecter à nouveau.\n";
echo "Si le problème persiste, vérifiez votre fichier .env pour SESSION_DRIVER\n";
?>
