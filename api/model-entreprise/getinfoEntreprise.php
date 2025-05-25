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

    use App\Models\Entreprise;
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
        
        $entreprise = Entreprise::where('id','1')->take(1)->first();

        echo json_encode($entreprise);
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