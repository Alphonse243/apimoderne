<?php
use App\Models\Entreprise;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Http\Request;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    http_response_code(405);
   exit;
}

// Récupération et validation des données
$name = trim($_POST['name'] ?? '');
$acronym = trim($_POST['acronym'] ?? '');
$courte = trim($_POST['courte'] ?? '');
$description = trim($_POST['description'] ?? '');

// $name = 'Récupération et validation des données';
//$name = 'Récupération et validation des données';
//$courte = 'Récupération et validation des données';
//$description = 'Récupération et validation des données';
//$acronym = 'Récupération et validation des données';

if ($acronym ==''){
    echo json_encode(['success' => false, 'error' => 'acronym est vide']);
    http_response_code(422);
}
if ($name === '' || $acronym === '' || $courte === '' || $description === '') {
    echo json_encode(['success' => false, 'error' => 'Champs requis manquants']);
    http_response_code(422);
    exit;
}

try {
    $settings = Entreprise::find(1); // Récupérer l'entreprise avec ID 1
    if (!$settings) {
        echo json_encode(['success' => false, 'error' => 'Entreprise non trouvée']);
        http_response_code(404);
        exit;
    }

    $updated = $settings->update([
        'name' => $name,
        'acronym' => $acronym,
        'courte'  => $courte,
        'description' => $description
    ]);
    if (!$updated) {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
        http_response_code(500);
        exit;
    }
    echo json_encode(['success' => true]);
    http_response_code(200);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
    http_response_code(500);
}
exit;
