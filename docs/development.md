# Guide de développement

Ce guide couvre les aspects pratiques du développement, des tests et du déploiement de l'API Moderne.

## Guide de contribution

### Workflow Git

1. **Fork et clone**
```bash
git clone https://github.com/VOS-USERNAME/apimoderne.git
cd apimoderne
git remote add upstream https://github.com/Alphonse243/apimoderne.git
```

2. **Créer une branche**
```bash
git checkout -b feature/ma-fonctionnalite
```

3. **Commits**
```bash
git add .
git commit -m "feat: description claire"
```

4. **Pull Request**
- Push vers votre fork
- Créer PR sur GitHub
- Attendre review

### Conventions de code

#### Style
- PSR-12 pour PHP
- Indentation : 4 espaces
- UTF-8
- Fin de ligne : LF

#### Nommage
- Classes : PascalCase
- Méthodes : camelCase
- Variables : camelCase
- Constantes : UPPER_SNAKE_CASE

```php
class UserController {
    private const MAX_ATTEMPTS = 3;
    
    public function getUserProfile($userId) {
        $userProfile = $this->findProfile($userId);
    }
}
```

## Tests

### Tests unitaires

Configuration dans `phpunit.xml` :

```xml
<phpunit>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

Exemple de test :

```php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase {
    public function test_create_user() {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        $this->assertInstanceOf(User::class, $user);
    }
}
```

### Tests API

Collection Postman fournie dans `tests/postman/`.

Variables d'environnement Postman :
```json
{
    "base_url": "http://localhost:8000",
    "api_token": "votre-token-test"
}
```

## Déploiement

### Prérequis serveur

- PHP 8.0+
- Extensions PHP requises
- Composer
- MySQL ou SQLite
- Apache/Nginx

### Étapes de déploiement

1. **Préparation**
```bash
# Sur le serveur
cd /var/www/
git clone https://github.com/Alphonse243/apimoderne.git
cd apimoderne
```

2. **Dépendances**
```bash
composer install --no-dev
```

3. **Configuration**
```bash
cp .env.example .env
nano .env  # Configurer pour production
```

4. **Permissions**
```bash
chown -R www-data:www-data storage
chmod -R 775 storage
```

5. **Base de données**
```bash
php database/migrations/create_tables.php
```

### Configuration Apache

```apache
<VirtualHost *:80>
    ServerName api.example.com
    DocumentRoot /var/www/apimoderne/public
    
    <Directory /var/www/apimoderne/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/api-error.log
    CustomLog ${APACHE_LOG_DIR}/api-access.log combined
</VirtualHost>
```

## Bonnes pratiques

### Sécurité

1. **Validation des entrées**
```php
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if ($email === false) {
    throw new ValidationException('Email invalide');
}
```

2. **Protection XSS**
```php
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
```

3. **Protection CSRF**
```php
if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    throw new SecurityException('Token invalide');
}
```

### Performance

1. **Cache**
```php
$cache->remember('users', 3600, function() {
    return User::all();
});
```

2. **Optimisation requêtes**
```php
// Bon
$users = User::with('posts')->get();

// À éviter (N+1)
$users = User::all();
foreach ($users as $user) {
    $user->posts;
}
```

## Dépannage

### Problèmes courants

1. **Erreur 500**
- Vérifier les logs PHP
- Vérifier les permissions
- Vérifier la config DB

2. **Erreur composer**
```bash
composer dump-autoload
composer clear-cache
```

3. **Problèmes de cache**
```bash
rm -rf storage/cache/*
php artisan cache:clear  # si disponible
```

### Logs

Emplacements des logs :
- `storage/logs/`
- `/var/log/apache2/`
- `php_errors.log`

### Debug

1. **Activation debug**
Dans `.env` :
```
APP_DEBUG=true
```

2. **Dump et die**
```php
dd($variable);  // Arrête l'exécution
var_dump($variable);  // Continue l'exécution
```

## Maintenance

### Tâches quotidiennes

1. **Vérification logs**
```bash
tail -f storage/logs/app.log
```

2. **Nettoyage cache**
```bash
find storage/cache -type f -mtime +7 -delete
```

3. **Backup DB**
```bash
./scripts/backup-db.sh
```

### Monitoring

Metrics à surveiller :
- Temps de réponse
- Utilisation mémoire
- Erreurs 4xx/5xx
- Requêtes/sec

## Ressources

- [PHP Standards (PSR)](https://www.php-fig.org/psr/)
- [Documentation Composer](https://getcomposer.org/doc/)
- [Guide PHPUnit](https://phpunit.de/documentation.html)
- [Sécurité PHP](https://www.php.net/manual/fr/security.php)