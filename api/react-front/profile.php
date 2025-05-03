<?php

    require '../../vendor/autoload.php';
    require '../../vendor/autoload.php';
    require '../../config/database.php';
    require '../../app/Controllers/UserController.php';
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
    use Illuminate\Database\Capsule\Manager as Capsule;
    use App\Controllers\UserController;
    
    $userController = new UserController();
    $user = $userController->getUserProfile($_GET['user_id']);
    try {
        echo json_encode($user);
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
