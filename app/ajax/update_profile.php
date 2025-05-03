<?php
session_start();
require '../../vendor/autoload.php';
require '../../config/database.php';
require '../Controllers/UserController.php';

use App\Controllers\UserController;

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$userController = new UserController();
$data = [
    'name' => $_POST['name'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'profession' => $_POST['profession'] ?? '',
    'location' => $_POST['location'] ?? ''
];

try {
    $result = $userController->updateUserProfile($_SESSION['user_id'], $data);
    echo json_encode([
        'success' => true,
        'message' => 'Informations mises à jour avec succès'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
    ]);
}
