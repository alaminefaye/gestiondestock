#!/bin/bash

echo "=========================================="
echo "  Vérification du projet Laravel"
echo "  Système de Gestion de Stock"
echo "=========================================="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Veuillez exécuter ce script depuis la racine du projet Laravel"
    exit 1
fi

echo "✅ Projet Laravel détecté"
echo ""

# Vérifier les fichiers importants
echo "Vérification des fichiers principaux..."
echo ""

files=(
    "resources/views/layouts/app.blade.php"
    "resources/views/layouts/partials/sidebar.blade.php"
    "resources/views/layouts/partials/header.blade.php"
    "resources/views/layouts/partials/footer.blade.php"
    "resources/views/dashboard/index.blade.php"
    "resources/views/sample-page.blade.php"
    "app/Http/Controllers/DashboardController.php"
    "routes/web.php"
    "public/assets/css/style.css"
    "public/assets/js/script.js"
)

missing=0
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file"
    else
        echo "❌ $file (manquant)"
        ((missing++))
    fi
done

echo ""

# Vérifier les assets
if [ -d "public/assets" ]; then
    asset_count=$(find public/assets -type f | wc -l)
    echo "✅ Assets copiés: $asset_count fichiers"
else
    echo "❌ Dossier public/assets manquant"
    ((missing++))
fi

echo ""

# Vérifier la configuration
if [ -f ".env" ]; then
    echo "✅ Fichier .env configuré"
else
    echo "⚠️  Fichier .env manquant - Exécutez: cp .env.example .env && php artisan key:generate"
fi

echo ""

# Résumé
echo "=========================================="
if [ $missing -eq 0 ]; then
    echo "✅ TOUT EST PRÊT!"
    echo ""
    echo "Pour démarrer le serveur:"
    echo "  php artisan serve --port=8001"
    echo ""
    echo "Puis ouvrez: http://127.0.0.1:8001"
else
    echo "⚠️  $missing fichier(s) manquant(s)"
    echo "Veuillez vérifier l'installation"
fi
echo "=========================================="
