# Guide d'installation

Ce guide détaille l'installation et la configuration initiale de l'API Moderne.

## Prérequis

### Système
- PHP 8.0 ou supérieur
- Extensions PHP requises :
  - PDO
  - pdo_mysql (pour MySQL)
  - pdo_sqlite (pour SQLite)
  - mbstring
  - json
  - fileinfo
- Composer (gestionnaire de dépendances)
- Serveur web (Apache/Nginx) ou PHP built-in pour le développement

### Vérification des prérequis

```bash
# Vérifier la version de PHP
php -v

# Vérifier les extensions PHP installées
php -m | grep -E "pdo|sqlite|mysql|mbstring|json|fileinfo"
```

## Installation

1. **Cloner le dépôt**

```bash
git clone https://github.com/Alphonse243/apimoderne.git
cd apimoderne
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configuration de l'environnement**

```bash
# Copier le fichier d'exemple
cp .env.example .env

# Éditer le fichier .env selon votre environnement
nano .env
```

4. **Permissions des dossiers**

```bash
# Créer et configurer le dossier storage
mkdir -p storage/cache/views
chmod -R 775 storage
```

Pour SQLite :
```bash
# Créer et configurer le dossier database
mkdir -p database
touch database/database.sqlite
chmod 664 database/database.sqlite
```

## Structure des fichiers

```
apimoderne/
├── api/              # Points d'entrée API
├── app/              # Code source principal
│   ├── Controllers/  # Contrôleurs
│   ├── Models/       # Modèles Eloquent
│   ├── Services/     # Services métier
│   └── Views/        # Vues Blade
├── bootstrap/        # Initialisation
├── config/          # Configuration
├── database/        # Migrations et seeds
├── docs/            # Documentation
├── public/          # Point d'entrée web
├── storage/         # Fichiers générés
└── vendor/          # Dépendances
```

## Configuration

### Base de données

Éditer `.env` selon votre choix :

**MySQL** :
```ini
DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=api-global
DB_USERNAME=root
DB_PASSWORD=
```

**SQLite** :
```ini
DB_DRIVER=sqlite
DB_DATABASE=database/database.sqlite
```

### Serveur web

**Apache** : Le projet inclut un `.htaccess`. Pointez votre vhost vers le dossier `public/`.

**PHP Built-in** (développement) :
```bash
php -S localhost:8000 -t public
```

## Vérification de l'installation

1. **Test de la base de données**
```bash
php test_sqlite.php  # Pour SQLite
```

2. **Test du serveur web**
- Accédez à `http://localhost:8000/`
- Vous devriez voir la page d'accueil

## Dépannage

### Problèmes courants

1. **Erreur Composer**
```bash
composer install --ignore-platform-reqs
```

2. **Erreur de permissions**
```bash
sudo chown -R $USER:www-data .
sudo chmod -R 775 storage database
```

3. **Erreur PDO**
- Vérifiez que les extensions sont activées dans `php.ini`
- Redémarrez PHP-FPM/Apache si nécessaire

### Logs

Les logs sont disponibles dans :
- `storage/logs/`
- Logs Apache/Nginx
- `php_errors.log`

## Prochaines étapes

- [Configuration de la base de données](./database.md)
- [Structure de l'API](./api.md)
- [Guide de développement](./development.md)