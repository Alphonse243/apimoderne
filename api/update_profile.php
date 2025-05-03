<?php
/**
 * Point d'entrée API pour la mise à jour du profil utilisateur
 * Gère les requêtes AJAX pour mettre à jour les informations et la bio
 */

session_start();
require '../vendor/autoload.php';
require '../config/database.php';
require '../app/Controllers/UserController.php';

use App\Controllers\UserController;

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

$userController = new UserController();
$type = $_POST['type'] ?? '';
$response = ['success' => false, 'message' => ''];

// Traitement des différents types de mises à jour
if ($type === 'info') {
    // Collecte des données du formulaire pour les informations générales
    $data = [
        'name' => $_POST['name'] ?? '',
        'website' => $_POST['website'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'facebook' => $_POST['facebook'] ?? '',
        'twitter' => $_POST['twitter'] ?? '',
        'instagram' => $_POST['instagram'] ?? ''
    ];
    
    // Tentative de mise à jour et réponse
    if ($userController->updateUserInfo($_SESSION['user_id'], $data)) {
        $response = ['success' => true, 'message' => 'Informations mises à jour avec succès'];
    } else {
        $response = ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
    }
} elseif ($type === 'bio') {
    // Mise à jour de la bio
    $bio = $_POST['bio'] ?? '';
    
    if ($userController->updateUserBio($_SESSION['user_id'], $bio)) {
        $response = ['success' => true, 'message' => 'Bio mise à jour avec succès'];
    } else {
        $response = ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
    }
} elseif ($type === 'avatar') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $result = $userController->updateUserAvatar($_SESSION['user_id'], $_FILES['avatar']);
        if ($result['success']) {
            $response = [
                'success' => true,
                'message' => 'Avatar mis à jour avec succès',
                'avatar_url' => $result['avatar_url']
            ];
        } else {
            $response = ['success' => false, 'message' => $result['message'] ?? 'Erreur lors de la mise à jour'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Aucun fichier reçu'];
    }
}

// Envoi de la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
