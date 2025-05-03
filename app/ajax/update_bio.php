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
$bio = $_POST['bio'] ?? '';

try {
    $result = $userController->updateUserBio($_SESSION['user_id'], $bio);
    echo json_encode([
        'success' => true,
        'message' => 'Bio mise à jour avec succès'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
    ]);
}
