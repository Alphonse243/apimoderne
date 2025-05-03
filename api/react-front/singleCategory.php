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

    use App\Models\Post;
    use App\Models\Category;
    use Illuminate\Database\Capsule\Manager as Capsule;
    use App\Controllers\CategoryController;

    try {
        // Vérification de la connexion à la base de données
        if (!Capsule::connection()->getPdo()) {
            throw new Exception('Impossible de se connecter à la base de données');
        }

        // recuperation des information de la categorie
        $categorie_id = $_GET['categorie_id'];
        $categoryTable = CategoryController::getAllCategoryById($categorie_id);

        echo json_encode($categoryTable);
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
