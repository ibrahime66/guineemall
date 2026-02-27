# Backend Client - GuinéeMall

## 📋 Vue d'ensemble

Ce document décrit l'implémentation du backend client pour la plateforme e-commerce multi-vendeurs GuinéeMall. Le backend client a été intégré dans le projet Laravel existant sans modifier le backend admin déjà en place.

## 🏗️ Architecture

### Structure des dossiers

```
app/
├── Http/Controllers/Client/
│   ├── CatalogController.php      # Gestion du catalogue produits
│   ├── CartController.php         # Gestion du panier
│   ├── OrderController.php        # Gestion des commandes
│   └── ProfileController.php      # Gestion du profil client
├── Http/Requests/Client/
│   ├── AddToCartRequest.php       # Validation ajout panier
│   ├── UpdateCartRequest.php      # Validation mise à jour panier
│   └── ProfileUpdateRequest.php   # Validation mise à jour profil
├── Services/Client/
│   ├── CartService.php            # Logique métier panier
│   └── OrderService.php           # Logique métier commandes
└── Models/
    ├── Cart.php                   # Modèle du panier
    └── User.php                   # Mis à jour avec infos client

resources/views/client/
├── dashboard.blade.php            # Tableau de bord client
├── catalog/
│   ├── index.blade.php            # Liste des produits
│   ├── show.blade.php             # Détails produit
│   └── category.blade.php         # Produits par catégorie
├── cart/
│   └── index.blade.php            # Panier client
├── orders/
│   ├── index.blade.php            # Historique commandes
│   ├── show.blade.php             # Détails commande
│   └── checkout.blade.php         # Validation commande
└── profile/
    ├── index.blade.php            # Profil client
    └── edit.blade.php             # Modification profil

routes/
└── client.php                     # Routes client dédiées
```

## 🔐 Sécurité & Authentification

### Middlewares
- **Client**: Vérifie que l'utilisateur est connecté et a le rôle 'client'
- **Admin**: Protège les routes admin (existant)
- **Vendeur**: Protège les routes vendeur (existant)

### Règles de sécurité
- Un client ne peut accéder qu'à ses propres données
- Séparation stricte entre les rôles admin, client, vendeur
- Validation des entrées avec des Form Requests
- Protection contre les accès non autorisés

## 📦 Modules fonctionnels

### 1. Catalogue Client
- **Routes**: `/client/catalog/*`
- **Fonctionnalités**:
  - Affichage des produits actifs avec stock > 0
  - Filtrage par catégorie
  - Recherche par nom
  - Détails produit avec produits similaires
  - Ajout direct au panier

### 2. Panier Client
- **Routes**: `/client/cart/*`
- **Fonctionnalités**:
  - Ajout/modification/suppression d'articles
  - Vérification automatique des stocks
  - Calcul du total en temps réel
  - Vidage du panier
  - Panier persistant par client

### 3. Gestion des Commandes
- **Routes**: `/client/orders/*`
- **Fonctionnalités**:
  - Validation du panier en commande
  - Historique des commandes client
  - Détails commande complets
  - Annulation (si statut le permet)
  - Suivi des statuts (en_attente, confirmée, livrée)

### 4. Profil Client
- **Routes**: `/client/profile/*`
- **Fonctionnalités**:
  - Consultation des informations personnelles
  - Modification du profil (nom, email, téléphone, adresse)
  - Changement de mot de passe
  - Statistiques personnelles

### 5. Tableau de Bord
- **Route**: `/client/dashboard`
- **Fonctionnalités**:
  - Statistiques des commandes
  - Articles récents dans le panier
  - Commandes récentes
  - Accès rapide aux fonctionnalités

## 🔄 Flux client typique

1. **Consultation**: Parcours du catalogue → Détails produit
2. **Ajout panier**: Ajout d'articles avec vérification stock
3. **Gestion panier**: Modification quantités → Validation
4. **Commande**: Validation avec informations livraison → Création commande
5. **Suivi**: Consultation historique → Suivi statut

## 📊 Base de données

### Nouvelles tables
- **carts**: Panier temporaire par client
- **users**: Ajout des champs `phone` et `delivery_address`

### Relations
- User ↔ Cart (1:N)
- User ↔ Order (1:N)
- Cart ↔ Product (N:1)
- Order ↔ OrderItem (1:N)
- OrderItem ↔ Product (N:1)

## 🛠️ Configuration

### Routes
Les routes client sont définies dans `routes/client.php` et incluses dans `routes/web.php`.

### Middlewares
Enregistrés dans `bootstrap/app.php` avec les alias:
- `admin` → Admin
- `client` → Client
- `vendeur` → Vendeur

## 📝 Notes importantes

### Contraintes respectées
✅ Aucune modification du backend admin existant
✅ Structure de dossiers respectée
✅ Séparation claire des rôles
✅ Code modulaire et réutilisable

### Règles métier implémentées
- Produits inactifs invisibles
- Stock à zéro non commandable
- Panier multi-vendeurs autorisé
- Commande non modifiable après confirmation vendeur
- Accès strict aux données personnelles

## 🚀 Utilisation

### Pour accéder au backend client:
1. Créer un compte avec le rôle `client`
2. Se connecter via `/login`
3. Accéder au tableau de bord `/client/dashboard`

### Pour les développeurs:
- Les routes client sont préfixées par `/client`
- Toutes les routes nécessitent l'authentification et le rôle client
- Les vues utilisent le composant `<x-app-layout>` pour la cohérence UI

---

**Backend client GuinéeMall - Implémentation complète et fonctionnelle**
