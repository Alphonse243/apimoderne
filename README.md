# API puissant et securiser 

## Description
API connecter a une base de donneés Sql en MvC avec php 8 et
## Dependace principage
- 
- illuminate/database
- illuminate/events
- fakerphp/faker
- nesbot/carbon
- jenssegers/blade
- vlucas/phpdotenv
- phpmailer/phpmailer
- guzzlehttp/guzzle
## Utiliser SQLite

Vous pouvez basculer la connexion vers SQLite sans modifier le code PHP en définissant les variables d'environnement :

- `DB_DRIVER=sqlite`
- `DB_DATABASE=database/app.sqlite` (chemin relatif au projet ou chemin absolu)

Par défaut, si `DB_DATABASE` n'est pas fourni, le projet utilisera `database/database.sqlite`.

Exemples d'utilisation :

- Créez un fichier `.env` à la racine et ajoutez :

```
DB_DRIVER=sqlite
DB_DATABASE=database/database.sqlite
```

- Assurez-vous que le dossier `database/` est inscriptible par le processus PHP. Le fichier SQLite sera créé automatiquement si absent.

Remarque : bootstrap utilise `Dotenv::safeLoad()` pour ne pas échouer si `.env` est absent.
<!-- ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQDWxZwtseXfHQ7firD5rdDC/1bNYlB5nC50KHQEJyxtNHTLUKKOxYMwLChDqU/mBqEw7A1LkilngOhYl5d5GEAgMD3BAxNwFAZQaXZx0Pk9ddNDYH1FMG9tvxapENn7KHf3X2gkVQkY5q/Ss+KvzeaumKnhvsB1TYjIXNz62KUNw/Ijr8IjJjVKcl6jBmabo8hTsECBU/DHUlDP0t7cGBc9vOASFkDZgCEKP9AAl0fyMMD03fAguYmH7nq29nsVJh+cVfFbWznGI21cO31zxf5S0ZKC4zGv2A1ipn2wmmEh17CpM5juaG09hemNxW1sbSuw5R15Lj3xWjbagwqvb4JfjuNfIpGEgBSEB/XajzzaItIe24GF/J6fzyUcl2siRHFS6F11EFCfIP3OBFGbnvzmpsuAMiBOCM0GTBUVoUD2fz5MYt2Mwb1wVv/xUBwbQDhk9ixUqbrQQKX0bXO93dC43cEJDHSivJ++dEJKpKaePfl4jnCgT7i654QAjwaB4HE= u654857617@fr-int-web1423.main-hosting.eu -->


