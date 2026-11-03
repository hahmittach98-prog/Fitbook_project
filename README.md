# FitBook

FitBook est une application web de réservation de séances de sport.
Elle met en relation des **sportifs**, des **coachs** et des **salles de sport**.

Le projet est composé d'une API en Symfony (backend) et d'une interface en Angular (frontend).

- **Auteure** : Hajar Ahmittach
- **Centre** : Cfitech
- **Projet** : UAA MI08

---

## Ce que fait l'application

- Inscription avec confirmation par email, puis connexion avec un jeton JWT.
- Quatre rôles : sportif, coach, salle et administrateur.
- Gestion des salles, des coachs, des cours et des séances.
- Réservation d'une séance, favoris, avis et notes, calendrier, barre de recherche avec filtres.
- Disponibilités des coachs et des salles.
- Photos pour les salles, les coachs et les utilisateurs.
- Administration : valider un compte, supprimer un utilisateur, modérer les avis, gérer les cours.

### Ce que chaque rôle peut faire

| Rôle | Fonctionnalités |
|---|---|
| Sportif | Profil modifiable avec photo, recherche et filtres, réservation, historique de ses réservations, favoris, avis et notes, calendrier |
| Coach | Profil professionnel modifiable, création et suppression de ses séances, disponibilités, calendrier, sportifs inscrits, avis reçus |
| Salle | Création et modification de ses salles avec photo, cours proposés, séances, disponibilités, réservations et avis |
| Administrateur | Gestion des utilisateurs, modération des avis, gestion des cours, consultation des séances et de leurs inscrits |

---

## Technologies

| Technologie | Utilité |
|---|---|
| Symfony 7.4 et PHP 8.3 | API (backend) |
| MySQL 8 et Doctrine | Base de données et migrations |
| LexikJWTAuthenticationBundle | Connexion par jeton JWT |
| API Platform | Ressource User (réservée à l'admin) |
| VichUploaderBundle | Envoi des photos |
| Mailtrap | Emails de confirmation (boîte de test) |
| NelmioCorsBundle | Autoriser Angular à appeler l'API |
| Postman et Newman | Tests de l'API |
| Angular 21 | Interface (frontend) |
| Vitest | Tests du frontend |

---

## Structure du dépôt

```
Fitbook/
├── backend/      API Symfony
├── frontend/     Interface Angular
│   └── src/app/
│       ├── pages/        Une page par écran
│       ├── components/   Photo, téléchargement, étoile, avis, disponibilités
│       ├── services/     Api (appels à l'API) et favoris
│       └── securite/     Intercepteur du jeton et guard de rôle
├── database/
│   ├── procedures/   Les 3 procédures stockées
│   └── backups/      Sauvegardes de la base
├── postman/      Collection Postman et environnement
├── documents/    Cahier des charges
└── README.md
```

---

## Installer le backend

### Ce qu'il faut avoir

- PHP 8.3 avec l'extension `sodium` activée
- Composer
- MySQL 8 (WAMP, par exemple)
- Symfony CLI
- Node.js et npm (pour Newman et pour le frontend)

### Étapes

1. Aller dans le dossier du backend et installer les dépendances :

```bash
cd backend
composer install
```

2. Créer un fichier `.env.local` dans `backend/` avec tes valeurs :

```
DATABASE_URL="mysql://UTILISATEUR:MOT_DE_PASSE@127.0.0.1:3306/fitbook?serverVersion=8.0&charset=utf8mb4"
MAILER_DSN=null://null
```

Pour recevoir les vrais emails de test, remplace `MAILER_DSN` par l'adresse donnée par Mailtrap.

3. Créer la base de données et les tables :

```bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

4. Générer les clés du JWT :

```bash
symfony console lexik:jwt:generate-keypair
```

5. Ajouter les procédures stockées (depuis la racine du projet) :

```bash
mysql -u UTILISATEUR -p fitbook < database/procedures/first_procedures.sql
mysql -u UTILISATEUR -p fitbook < database/procedures/second_procedures.sql
mysql -u UTILISATEUR -p fitbook < database/procedures/third_procedures.sql
```

6. Lancer le serveur :

```bash
symfony server:start
```

L'API répond sur `https://127.0.0.1:8000`. Le serveur redirige le `http` vers le `https`.

---

## Installer et lancer le frontend

### Ce qu'il faut avoir

- Node.js (version 20 ou plus récente) et npm
- Angular CLI :

```bash
npm install -g @angular/cli
```

### Étapes

1. Le serveur Symfony doit déjà tourner (voir plus haut).

2. Ouvrir une fois `https://127.0.0.1:8000` dans le navigateur et accepter le certificat local. Sans cela, le navigateur bloque les appels à l'API.

3. Installer les dépendances et lancer l'application :

```bash
cd frontend
npm install
ng serve
```

4. Ouvrir **http://localhost:4200** dans le navigateur.

L'adresse de l'API est écrite en haut du fichier `frontend/src/app/services/api.ts` (`https://127.0.0.1:8000/api`). Il suffit de la changer si le backend tourne ailleurs.

### Créer des comptes pour tester

- **Sportif, coach et salle** : avec la page d'inscription. Le compte est utilisable après la confirmation de l'email (à lire dans Mailtrap).
- **Coach** : après la première connexion, la page « Espace coach » demande de compléter le profil (spécialité, description, tarif).
- **Administrateur** : voir la section suivante.

Les photos envoyées sont enregistrées dans `backend/public/images/`. Ce dossier n'est pas versionné.

---

## Créer un administrateur

Il n'y a pas d'inscription pour l'admin. Il faut le créer directement dans la table `users`.

1. Générer le mot de passe haché :

```bash
symfony console security:hash-password
```

2. Dans phpMyAdmin, ajouter une ligne dans `users` avec : l'email, le rôle `["ROLE_ADMIN"]`, le mot de passe haché, `is_verified` à 1, les dates `created_at` et `updated_at`, ainsi que les autres champs obligatoires (nom, prénom...).

---

## Lancer les tests

### Tests du frontend (Vitest)

Depuis le dossier `frontend` :

```bash
ng test
```

Pour un seul passage, sans surveillance des fichiers :

```bash
ng test --watch=false
```

Les fichiers de test se terminent par `.spec.ts`. Ils contiennent :

- des **tests unitaires** : le service `Api` (rôles lus dans le jeton, adresse des images) avec un faux `HttpClient` ;
- des **tests d'intégration de composants** : `Photo`, `Telechargement` et `Disponibilites` sont testés avec leur template (ce qui s'affiche dans la page) ;
- un test de création pour chaque page, avec un faux `HttpClient` : aucun test n'appelle le vrai serveur.

### Tests de l'API avec Newman

La collection Postman contient **18 requêtes**. Elles parcourent un scénario complet : connexion, inscription d'un coach, création d'une salle, d'un cours et d'une séance, erreurs 401 et 422, puis nettoyage.

Pour la lancer, le serveur Symfony et MySQL doivent tourner. Depuis la racine du projet :

```bash
npm install -g newman
newman run postman/FitBook_API.postman_collection.json -e postman/FitBook_Local.postman_environment.json --insecure --env-var "url=https://127.0.0.1:8000" --env-var "adminEmail=EMAIL_ADMIN" --env-var "adminPassword=MOT_DE_PASSE_ADMIN"
```

Résultat attendu : 18 assertions réussies, 0 échec.

### Validation du JSON Schema

La création d'une séance est validée avec le schéma `backend/src/Validator/schemas/seance.schema.json`. Il se teste à la main sur le site de validation JSON Schema en collant le schéma et des données d'exemple.

---

## Routes principales de l'API

| Méthode | Route | Accès |
|---|---|---|
| POST | `/api/register` | Public |
| GET | `/api/confirm-email/{token}` | Public |
| POST | `/api/login` | Public |
| GET, PUT | `/api/profil` | Connecté |
| GET | `/api/salles`, `/api/coachs`, `/api/cours`, `/api/seances` | Public |
| POST, PUT, DELETE | `/api/salles`, `/api/coachs`, `/api/cours`, `/api/seances` | Selon le rôle |
| GET | `/api/seances/{id}/places` | Connecté |
| POST, GET | `/api/reservations` | Sportif |
| GET | `/api/reservations/seance/{id}` | Coach, salle, admin |
| GET, POST, DELETE | `/api/disponibilites` | Coach, salle, admin (écriture) |
| PUT, DELETE | `/api/admin/users/{id}` | Admin |

La liste complète s'obtient avec :

```bash
symfony console debug:router
```

---

## Base de données

Trois procédures stockées sont dans `database/procedures/` :

- `sp_places_restantes` : nombre de places libres d'une séance
- `sp_note_moyenne_salle` : note moyenne d'une salle
- `sp_reservations_utilisateur` : réservations d'un utilisateur

Les sauvegardes sont dans `database/backups/`. Pour en faire une :

```bash
mysqldump -u UTILISATEUR -p --routines --no-tablespaces fitbook > database/backups/backup.sql
```

---

## Organisation du travail

- **GitFlow** : `main` (version stable), `develop` (intégration) et une branche `feature/FB-xx` par tâche.
- Chaque branche porte le numéro de sa carte **Trello**.
- Les commits sont en français, au format `type(portée) : message`.
- Chaque branche est fusionnée dans `develop` par Pull Request.

---

## Pistes d'amélioration

- Galerie de plusieurs photos par salle.
- Modifier une séance depuis l'espace coach.
- Vérifier les disponibilités à la création d'une séance.
- Pages « Mes avis » et « Mon historique » pour le sportif.
- Listes complètes des salles, coachs et réservations pour l'administrateur.

---

## Auteure

Hajar Ahmittach, Cfitech.
