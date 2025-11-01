# Configuration de la base de données

Ce guide détaille la configuration et la gestion des bases de données (MySQL et SQLite) dans l'API Moderne.

## Configurations supportées

L'API supporte deux types de base de données :
- MySQL (par défaut)
- SQLite (excellent pour développement/tests)

Le choix se fait via la variable d'environnement `DB_DRIVER`.

## Configuration MySQL

### Variables d'environnement

```ini
DB_DRIVER=mysql
DB_HOST=localhost
DB_DATABASE=api-global
DB_USERNAME=root
DB_PASSWORD=
```

### Options avancées

Dans `config/database.php` :
```php
'mysql' => [
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    // autres options PDO si nécessaire
]
```

### Création de la base

```sql
CREATE DATABASE `api-global` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
```

## Configuration SQLite

### Variables d'environnement

```ini
DB_DRIVER=sqlite
DB_DATABASE=database/database.sqlite
```

Le chemin peut être :
- Relatif au projet (`database/database.sqlite`)
- Absolu (`/var/db/api.sqlite`)

### Création automatique

Le fichier SQLite est créé automatiquement si :
- Le dossier parent existe
- PHP a les droits d'écriture

### Permissions

```bash
# Créer le dossier et le fichier
mkdir -p database
touch database/database.sqlite

# Définir les permissions (exemple pour Apache)
sudo chown -R www-data:www-data database
chmod 664 database/database.sqlite
chmod 775 database
```

## Migrations

Les migrations sont dans `database/migrations/`.

### Structure

```php
// database/migrations/create_tables.php
class CreateTables {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // ...
        });
    }
}
```

### Exécution

```bash
# Lancer toutes les migrations
php database/migrations/create_tables.php

# Ou via le seeder qui inclut les migrations
php database/seed.php
```

## Seeders

Les seeders sont dans `database/seeders/`.

### Structure

```php
// database/seeders/DatabaseSeeder.php
class DatabaseSeeder {
    public function run() {
        // Création données exemple
        \App\Models\User::create([
            'name' => 'Admin',
            // ...
        ]);
    }
}
```

### Exécution

```bash
# Lancer tous les seeders
php database/seed.php
```

## Maintenance

### Sauvegarde

**MySQL** :
```bash
mysqldump -u root api-global > backup.sql
```

**SQLite** :
```bash
# Simple copie du fichier
cp database/database.sqlite backup.sqlite

# Ou avec sqlite3
sqlite3 database/database.sqlite ".backup 'backup.sqlite'"
```

### Restauration

**MySQL** :
```bash
mysql -u root api-global < backup.sql
```

**SQLite** :
```bash
cp backup.sqlite database/database.sqlite
```

## Tests et validation

### Test rapide SQLite

```bash
php test_sqlite.php
```

Sortie attendue :
```
OK: inserted id=1 name=sqlite-test
```

### Validation structure

```php
// Vérifier les tables
php artisan db:show

// Ou via PHP
echo \Schema::hasTable('users') ? "OK" : "Missing!";
```

## Dépannage

### Problèmes courants

1. **Erreur SQLite "unable to open database file"**
   - Vérifier les permissions
   - Vérifier le chemin (absolu/relatif)

2. **Erreur MySQL "Access denied"**
   - Vérifier les credentials dans `.env`
   - Vérifier que l'utilisateur a les droits

3. **PDO non disponible**
   - Vérifier les extensions PHP
   - Revoir le `php.ini`

### Logs et debug

```php
// Dans config/database.php
'options' => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_STRINGIFY_FETCHES => false,
]
```

## Migration entre drivers

### MySQL vers SQLite

1. Exporter structure :
```bash
mysqldump -u root --no-data api-global > structure.sql
```

2. Convertir pour SQLite (outils en ligne disponibles)

3. Importer :
```bash
sqlite3 database/database.sqlite < structure_sqlite.sql
```

### SQLite vers MySQL

Processus similaire avec les outils appropriés de conversion.

## Ressources

- [Documentation PDO](https://www.php.net/manual/fr/book.pdo.php)
- [Documentation SQLite](https://www.sqlite.org/docs.html)
- [Documentation MySQL](https://dev.mysql.com/doc/)