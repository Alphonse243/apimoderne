# API puissant et securiser 

## Description
API connecter a une base de donneés Sql en MvC avec php 8 et
## Dependace principage
- 
- illuminate/database
- illuminate/events
- fakerphp/faker
## API moderne (PHP 8) — documentation

Ce dépôt contient une API PHP « vanilla » organisée en MVC avec Eloquent (Illuminate Database). La configuration supporte MySQL (par défaut) et SQLite (facile à activer).

Principales dépendances

- PHP 8+
- illuminate/database, illuminate/events (Eloquent)
- jenssegers/blade (templating)
- vlucas/phpdotenv (variables d'environnement)
- fakerphp/faker, nesbot/carbon, phpmailer/phpmailer, guzzlehttp/guzzle

## Prérequis

- PHP 8.x avec PDO et pdo_sqlite et pdo_mysql selon la base utilisée
- Composer
- Un serveur web local (Apache/XAMPP) ou PHP built-in pour le développement

## Installation

1. Cloner le dépôt

```bash
git clone <repo-url> mon-projet
cd mon-projet
```

2. Installer les dépendances

```bash
composer install
```

3. Créer un fichier `.env` à la racine (voir `.env.example` fourni)

```bash
cp .env.example .env
```

4. Ajuster les variables d'environnement (MySQL ou SQLite)

## Configuration de la base de données

Le projet lit la configuration via `bootstrap/app.php` et `config/database.php`.
Vous pouvez choisir le driver via la variable d'environnement `DB_DRIVER`.

- MySQL (par défaut)
	- Variables : `DB_DRIVER=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

- SQLite
	- Variables : `DB_DRIVER=sqlite`, `DB_DATABASE` = chemin du fichier sqlite (ex : `database/database.sqlite`)
	- Si `DB_DATABASE` est relatif, il est résolu par rapport à la racine du projet.
	- Le fichier sqlite sera créé automatiquement si le dossier est inscriptible par PHP.

Exemple minimal pour SQLite dans `.env` :

```
DB_DRIVER=sqlite
DB_DATABASE=database/database.sqlite
```

Exemple minimal pour MySQL dans `.env` :

```
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_DATABASE=api-global
DB_USERNAME=root
DB_PASSWORD=
```

## Lancer l'application localement

- Avec PHP built-in (pour dev rapide) :

```bash
php -S localhost:8000 -t public
```

- Ou configurez Apache/NGINX pour pointer vers le dossier du projet.

## Tests de connexion SQLite (script fourni)

Un script de test rapide est inclus : `test_sqlite.php`. Il force `DB_DRIVER=sqlite`, crée le fichier sqlite (si nécessaire), crée une table de test, insère et lit une ligne.

Exécuter :

```bash
php test_sqlite.php
```

Sortie attendue si tout est OK :

```
OK: inserted id=1 name=sqlite-test
```

## Migrations et seeders

Le dossier `database/migrations` et `database/seeders` contient des scripts d'initialisation (si présents). Vous pouvez adapter votre propre runner/commande pour exécuter ces scripts.

Un fichier `seed.php` est également présent pour insérer des données d'exemple dans la base — adaptez-le selon votre environnement.

## Permissions

Pour SQLite, assurez-vous que le répertoire `database/` est inscriptible par le processus PHP (par exemple `www-data` ou `apache`). Sinon créez manuellement le fichier sqlite et donnez-lui les droits nécessaires :

```bash
mkdir -p database
touch database/database.sqlite
chown -R www-data:www-data database
chmod 664 database/database.sqlite
```

## Dépannage rapide

- Erreur Dotenv sur `.env` manquant : le bootstrap utilise `Dotenv::safeLoad()` — si `.env` est absent, l'application ne doit pas planter.
- Erreur de création du fichier SQLite : vérifier les permissions du dossier `database/`.
- Vérifiez que les extensions PDO nécessaires sont activées : `php -m | grep -E "pdo|pdo_sqlite|pdo_mysql"`

## Prochaines améliorations possibles

- Ajouter des migrations automatisées et une commande `artisan`-like pour gestion DB
- Ajouter des tests unitaires (phpunit) pour valider la couche modèle
- Fournir un `.env.example` (fourni ici) et un script d'init

## Contact

Si vous voulez que j'ajoute les migrations/seeders ou un `.env.example` personnalisé, dites-le et je l'implémente.


