# Système de Gestion de Stock - Laravel

## 📋 Description

Application web de gestion de stock développée avec Laravel et intégrant le template dashboard Datta Able (Tailwind CSS).

## ✨ Fonctionnalités

### Dashboard Principal
- Vue d'ensemble des statistiques de stock
- Indicateurs de performance (produits, valeur, alertes)
- Historique des mouvements récents
- Alertes de stock faible

### Modules de Gestion
- **Produits** : Gestion complète du catalogue produits
- **Catégories** : Organisation des produits par catégorie
- **Fournisseurs** : Gestion des fournisseurs

### Mouvements de Stock
- **Entrées** : Enregistrement des réceptions de stock
- **Sorties** : Suivi des sorties (ventes, pertes, etc.)

### Rapports
- État détaillé des stocks
- Statistiques et analyses

### Administration
- Gestion des utilisateurs
- Configuration du système

## 🚀 Installation

### Prérequis
- PHP >= 8.2
- Composer
- MySQL ou PostgreSQL

### Étapes d'installation

1. **Cloner ou naviguer vers le projet**
```bash
cd /Users/mouhamadoulaminefaye/Desktop/PROJETS\ DEV/gestion-stock-laravel
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Éditer le fichier `.env` et configurer les paramètres de connexion :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stock
DB_USERNAME=root
DB_PASSWORD=
```

5. **Créer la base de données et exécuter les migrations**
```bash
php artisan migrate
```

6. **Démarrer le serveur de développement**
```bash
php artisan serve --port=8001
```

L'application sera accessible à : `http://127.0.0.1:8001`

## 📁 Structure du Projet

```
gestion-stock-laravel/
├── app/
│   └── Http/
│       └── Controllers/
│           └── DashboardController.php
├── public/
│   └── assets/                    # Assets du template (CSS, JS, images)
│       ├── css/
│       ├── js/
│       ├── fonts/
│       └── images/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php      # Layout principal
│       │   └── partials/
│       │       ├── sidebar.blade.php
│       │       ├── header.blade.php
│       │       └── footer.blade.php
│       ├── dashboard/
│       │   └── index.blade.php    # Page dashboard
│       └── sample-page.blade.php  # Template de page
└── routes/
    └── web.php                    # Routes web
```

## 🎨 Template Dashboard

Le projet utilise le template **Datta Able** (Tailwind CSS) qui offre :
- Design moderne et responsive
- Mode sombre/clair
- Composants UI riches
- Icons Feather, Font Awesome, Material
- Optimisé pour mobile

## 🛣️ Routes Disponibles

| Route | Description |
|-------|-------------|
| `/` | Dashboard principal |
| `/gestion/produits` | Gestion des produits |
| `/gestion/categories` | Gestion des catégories |
| `/gestion/fournisseurs` | Gestion des fournisseurs |
| `/mouvements/entrees` | Entrées de stock |
| `/mouvements/sorties` | Sorties de stock |
| `/rapports` | État des stocks |
| `/utilisateurs` | Gestion des utilisateurs |

## 🔧 Configuration

### Assets
Les assets (CSS, JS, images) sont situés dans `public/assets/` et sont directement accessibles.

### Vues Blade
- **Layout principal** : `resources/views/layouts/app.blade.php`
- **Partials** : `resources/views/layouts/partials/`
- **Pages** : `resources/views/dashboard/` et autres dossiers

## 📝 Développement

### Ajouter une nouvelle page

1. Créer la vue Blade dans `resources/views/`
2. Ajouter la route dans `routes/web.php`
3. (Optionnel) Créer un contrôleur avec `php artisan make:controller`

### Exemple :
```php
// routes/web.php
Route::get('/nouvelle-page', function () {
    return view('nouvelle-page', ['title' => 'Ma Page']);
})->name('nouvelle.page');
```

## 🎯 Prochaines Étapes

- [ ] Créer les migrations pour les tables (produits, catégories, etc.)
- [ ] Implémenter les contrôleurs CRUD
- [ ] Ajouter l'authentification (Laravel Breeze ou Jetstream)
- [ ] Développer les API pour les opérations CRUD
- [ ] Implémenter la logique métier de gestion de stock
- [ ] Ajouter les validations de formulaires
- [ ] Créer les seeders pour les données de test

## 📄 Licence

Ce projet utilise le template Datta Able distribué par ThemeWagon sous licence MIT.

## 👨‍💻 Développement

Projet développé pour la gestion de stock avec Laravel.
