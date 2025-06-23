<?php
require '../../vendor/autoload.php';
require '../../config/database.php';    
use App\Models\Entreprise;

// Vérifie que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On suppose qu'il n'y a qu'une seule entreprise à mettre à jour (id = 1)
    $entreprise = Entreprise::find(1);

    if (!$entreprise) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Entreprise non trouvée']);
        exit;
    }

    // Mettre à jour les champs avec les données reçues
    $entreprise->name = $_POST['name'] ?? $entreprise->name;
    $entreprise->acronym = $_POST['acronym'] ?? $entreprise->acronym;
    $entreprise->courte = $_POST['courte'] ?? $entreprise->courte;
    $entreprise->description = $_POST['description'] ?? $entreprise->description;

    // Sauvegarder les modifications
    $entreprise->save();

    echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
    exit;
}

// Si la requête n'est pas POST
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);