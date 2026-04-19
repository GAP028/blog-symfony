# 🏰 Chroniques du Royaume — Blog Symfony immersif

> Projet réalisé par **Gomas Alain-Patrick**
> 🎓 Projet académique & Portfolio développeur Symfony

---

## 🧠 Présentation du projet

**Chroniques du Royaume** est un blog web complet inspiré d’un univers médiéval-fantastique (type Game of Thrones), développé avec **Symfony**.

Ce projet a été conçu pour démontrer une **maîtrise complète du framework Symfony**, incluant :

* Authentification sécurisée
* Gestion des rôles (admin / user)
* CRUD complet
* Upload de fichiers
* Sécurisation avancée
* Architecture MVC propre
* Interface utilisateur immersive

---

## 🎯 Objectifs pédagogiques

Ce projet répond aux objectifs suivants :

* Concevoir une application web complète
* Implémenter un système d’authentification sécurisé
* Gérer des relations entre entités (Doctrine ORM)
* Mettre en place des rôles et permissions
* Créer une interface utilisateur professionnelle
* Assurer la sécurité (CSRF, accès restreint)
* Implémenter des fonctionnalités avancées (upload, logs)

---

## 🧰 Stack technique

| Technologie  | Utilisation             |
| ------------ | ----------------------- |
| PHP 8+       | Backend                 |
| Symfony 6/7  | Framework principal     |
| Doctrine ORM | Gestion base de données |
| MySQL        | Base de données         |
| Twig         | Templates               |
| Bootstrap 5  | Design                  |
| Faker        | Génération de données   |
| Composer     | Gestion dépendances     |

---

## ⚙️ Installation du projet

### 1. Cloner le projet

```bash
git clone <URL_DU_REPO>
cd mon_projet
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer la base de données

Modifier le fichier `.env` :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/blog_symfony"
```

### 4. Créer la base

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

### 5. Charger les données

```bash
symfony console doctrine:fixtures:load
```

### 6. Lancer le projet

```bash
symfony server:start
```

---

## 🔐 Authentification & Sécurité

### Fonctionnalités :

* Inscription utilisateur
* Connexion sécurisée
* Déconnexion
* Mot de passe hashé (bcrypt)
* Protection CSRF
* Gestion des sessions

### Sécurité avancée :

* Protection des routes (`security.yaml`)
* Vérification des rôles côté contrôleur (`IsGranted`)
* Vérification côté Twig (`is_granted`)
* Protection contre accès direct via URL

---

## 👥 Gestion des rôles

### 👤 Utilisateur (`ROLE_USER`)

* Consulter les articles
* Consulter les catégories
* Commenter les articles
* Accéder aux fonctionnalités de base

### 👑 Administrateur (`ROLE_ADMIN`)

* CRUD complet sur :

  * Articles
  * Catégories
  * Utilisateurs
* Gestion des rôles
* Activation / désactivation comptes
* Modération des commentaires
* Accès aux logs d’actions

---

## 🧱 Architecture du projet

```bash
src/
├── Controller/        → Logique métier
├── Entity/            → Modèles (User, Post, Category, Comment)
├── Form/              → Formulaires Symfony
├── Repository/        → Accès base de données
├── Security/          → Authentification
├── Service/           → Services (Upload, Logs)

templates/
├── base.html.twig
├── post/
├── category/
├── admin/
├── registration/
├── security/
```

---

## 🗄️ Base de données

### Entités principales :

* **User**
* **Post**
* **Category**
* **Comment**
* **AdminActionLog**

### Relations :

* User → Post (1:N)
* User → Comment (1:N)
* Post → Comment (1:N)
* Post → Category (N:1)

---

## 📝 Fonctionnalités détaillées

### 📰 Articles

* Création (admin)
* Modification (admin)
* Suppression (admin)
* Affichage public
* Auteur automatique
* Image :

  * URL
  * Upload

---

### 🏷️ Catégories

* Création / modification / suppression (admin)
* Association aux articles

---

### 💬 Commentaires

* Ajout par utilisateur connecté
* Statut :

  * validé
  * refusé
* Modération admin

---

### 👤 Utilisateurs

* Inscription
* Connexion
* Modification admin
* Gestion des rôles
* Activation / désactivation
* Photo de profil :

  * URL
  * Upload

---

## 🖼️ Upload de fichiers

### Fonctionnement :

* Service dédié : `FileUploader`
* Stockage dans `/public/uploads`
* Génération nom sécurisé
* Validation type et taille

### Types acceptés :

* jpg
* png
* webp
* gif

---

## 📊 Journal des actions admin

### Objectif :

Tracer toutes les actions sensibles

### Exemples :

* Création article
* Suppression article
* Modification utilisateur
* Désactivation admin

### Données enregistrées :

* Type cible
* Identifiant cible
* Action
* Admin responsable
* Date
* Détails

---

## 🎨 Interface utilisateur

* Design immersif (style fantasy)
* Bootstrap 5
* Responsive
* UX moderne
* Navigation sécurisée

---

## 🔒 Gestion des accès

### Protection :

* Routes sécurisées
* Accès contrôlé par rôle
* Vérification backend + frontend

### Exemple :

```php
#[IsGranted('ROLE_ADMIN')]
```

---

## ⚠️ Gestion des erreurs

* Messages utilisateur propres
* Blocage accès non autorisé
* Validation des formulaires
* Gestion erreurs serveur

---

## 🧪 Comptes de démonstration

### 👑 Admin

Email : `admin@gomas-portfolio.com`
Mot de passe : `Admin1234`

---

## 🧪 Données de test

Générées avec :

```bash
fakerphp/faker
doctrine fixtures
```

---

## 📌 Points forts du projet

✔ Architecture propre
✔ Sécurité complète
✔ Upload fichiers
✔ Gestion des rôles
✔ Journal des actions
✔ Interface professionnelle
✔ Code structuré

---

## 🚀 Améliorations possibles

* API REST
* Pagination
* Likes / favoris
* Notifications
* Dashboard admin avancé
* Upload multiple

---

## 👨‍💻 Auteur

**Gomas Alain-Patrick**
Développeur Symfony

📌 Projet portfolio
📌 Démonstration recruteur
📌 Niveau professionnel junior confirmé

---

## 📜 Licence

Projet pédagogique — libre d’utilisation
