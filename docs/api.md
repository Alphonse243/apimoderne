# Guide de l'API

Ce document détaille l'architecture, les routes et l'utilisation de l'API Moderne.

## Architecture MVC

L'API suit le pattern MVC (Modèle-Vue-Contrôleur) :

```
app/
├── Controllers/    # Logique de contrôle
├── Models/         # Modèles de données
├── Services/       # Services métier
└── Views/          # Templates Blade
```

### Contrôleurs

Les contrôleurs sont dans `app/Controllers/` :

```php
namespace App\Controllers;

class UserController {
    public function profile($id) {
        $user = User::find($id);
        return response()->json($user);
    }
}
```

### Modèles

Les modèles utilisent Eloquent ORM :

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $fillable = ['name', 'email'];
    
    public function posts() {
        return $this->hasMany(Post::class);
    }
}
```

## Routes disponibles

### Points d'entrée API

Toutes les routes API sont dans le dossier `api/` :

#### Utilisateurs
- `GET /api/profile.php` - Profil utilisateur
- `POST /api/update_profile.php` - Mise à jour profil

#### Posts
- `GET /api/posts.php` - Liste des posts
- `GET /api/singlePost.php` - Détail d'un post
- `GET /api/postByCategory.php` - Posts par catégorie

#### Entreprises
- `GET /api/model-entreprise/getinfoEntreprise.php` - Info entreprise
- `POST /api/model-entreprise/addEntrepriseContactMessage.php` - Contact

#### Commerce
- `GET /api/commerce/getCategory.php` - Catégories
- `GET /api/commerce/produitsApi.php` - Produits

### Format des réponses

L'API retourne du JSON :

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Example"
    }
}
```

En cas d'erreur :
```json
{
    "status": "error",
    "message": "Description de l'erreur"
}
```

## Utilisation

### Authentification

L'API utilise des tokens JWT. Pour accéder aux endpoints protégés :

```bash
curl -H "Authorization: Bearer <token>" \
     https://api.example.com/api/profile.php
```

### Exemples par catégorie

#### Profil utilisateur

```bash
# Obtenir le profil
curl https://api.example.com/api/profile.php?id=1

# Mettre à jour le profil
curl -X POST \
     -H "Content-Type: application/json" \
     -d '{"name":"Nouveau nom"}' \
     https://api.example.com/api/update_profile.php
```

#### Posts et articles

```bash
# Liste des posts
curl https://api.example.com/api/posts.php

# Post par catégorie
curl https://api.example.com/api/postByCategory.php?category_id=1
```

#### Commerce

```bash
# Liste des produits
curl https://api.example.com/api/commerce/produitsApi.php

# Catégories
curl https://api.example.com/api/commerce/getCategory.php
```

## Intégration

### PHP

```php
$response = file_get_contents('http://api.example.com/api/posts.php');
$data = json_decode($response, true);
```

### JavaScript

```javascript
fetch('http://api.example.com/api/posts.php')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Python

```python
import requests
response = requests.get('http://api.example.com/api/posts.php')
data = response.json()
```

## Sécurité

### Bonnes pratiques

1. Toujours valider les entrées
2. Utiliser HTTPS
3. Implémenter des limites de rate
4. Vérifier les tokens JWT
5. Échapper les sorties

### Validation des entrées

```php
// Exemple dans un contrôleur
public function update($request) {
    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['error' => 'Email invalide'], 400);
    }
}
```

## Tests

### Tests unitaires

Les tests sont dans `tests/` :

```php
class PostTest extends TestCase {
    public function testGetPosts() {
        $response = $this->get('/api/posts.php');
        $this->assertEquals(200, $response->status());
    }
}
```

### Tests manuels

Collection Postman fournie dans `tests/postman/`.

## Maintenance

### Logs

Les logs API sont dans :
- `storage/logs/api.log`
- Logs serveur web

### Monitoring

Points à surveiller :
1. Temps de réponse
2. Taux d'erreur
3. Utilisation mémoire/CPU
4. Requêtes par seconde

## Ressources

- [Documentation Eloquent](https://laravel.com/docs/eloquent)
- [Guide JWT](https://jwt.io/)
- [Tests avec PHPUnit](https://phpunit.de/)