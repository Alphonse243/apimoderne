<?php
   
    require '../../vendor/autoload.php';
    require '../../vendor/autoload.php';
    require '../../config/database.php';
    use Carbon\Carbon;
    Carbon::setlocale('fr');

    // Activer l'affichage des erreurs en développement
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Configuration des headers CORS
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    use App\Models\Produit;
    use App\Models\Category;
    use Illuminate\Database\Capsule\Manager as Capsule;
    use Illuminate\Http\Request;

    try {
        // Vérification de la connexion à la base de données
        if (!Capsule::connection()->getPdo()) {
            throw new Exception('Impossible de se connecter à la base de données');
        }

        // Test de la requête avant d'ajouter les relations
       
        // $posts = Produit::with(['author', 'Categorie'])
        //     ->withCount('comments')
        //     ->where('status', 'active')
        //     ->orderBy('created_at', 'DESC') // S'assurer que les posts sont triés par date
        // ->get();
        if(isset($_GET['category'])){
            $category = $_GET['category'];
        }else{
            $category = 1;
        }
        $posts = Produit::with('author')->where('id', $category)->inRandomOrder()->take(10)->get();
        


        $formattedPosts = $posts->map(function ($post) { 
            return [
                'id' => $post->id,
                'title' => $post->nom,
                'extret' => $post->description,
                'slug' => $post->slug,
                'image_url' => $post->image_url ?? 'https://picsum.photos/400/300',
                'views' => $post->views ?? 0,
                'status' => $post->status,
                'created_at' => Carbon::parse($post->created_at)->diffForHumans(),
                'nature' => $post->nature,
                'prix' => $post->prix,
                'devise' =>  $post->devise,
                'user' => [
                    'id' => $post->author->id,
                    'name' => $post->author->name,
                    'avatar_url' => 'https://ui-avatars.com/api/?name=Test',
                ],
                'category' => [
                    'name' => $post->categorie->name,
                    'slug' => $post->categorie->slug,
                ],
                'tags' => [],
            ];
        });

        echo json_encode($formattedPosts);
    } catch (Exception $e) {
        error_log($e->getMessage() . "\n" . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode([
            'error' => 'Une erreur est survenue lors de la récupération des articles',
            'debug' => [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ]);
    }
    // $posts = Produit::with('author','categorie')->inRandomOrder()->take(10)->get();
    // echo json_encode($posts);