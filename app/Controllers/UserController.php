<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\RememberToken;
use App\Models\PasswordReset;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;
use Exception;
use App\Services\MailService;

class UserController
{
    protected $db;
    private $mailService;
    private $verificationService;

    public function __construct()
    {
        global $capsule;
        $this->db = $capsule->getConnection()->getPdo();
        $this->mailService = new MailService();
        $this->verificationService = new \App\Services\VerificationService();
    }

    public function verifyPhone($code)
    {
        // Example implementation
        if ($code === '123456') {
            return ['success' => true];
        } else {
            throw new \Exception('Code de vérification invalide.');
        }
    }

    public function createRememberToken($userId, $ipAddress)
    {
        try {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));

            RememberToken::create([
                'user_id' => $userId,
                'token' => $token,
                'ip_address' => $ipAddress,
                'expires_at' => $expiresAt
            ]);

            setcookie('remember_token', $token, time() + (86400 * 30), '/');
            setcookie('user_id', $userId, time() + (86400 * 30), '/');
            
            return true;
        } catch (\Exception $e) {
            error_log("Erreur création token: " . $e->getMessage());
            return false;
        }
    }

    private function validateRememberToken($token, $userId, $ipAddress)
    {
        $tokenRecord = RememberToken::where('token', $token)
            ->where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $tokenRecord !== null;
    }

    public function autoLogin()
    {
        if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
            $token = $_COOKIE['remember_token'];
            $userId = $_COOKIE['user_id'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];

            if ($this->validateRememberToken($token, $userId, $ipAddress)) {
                $user = User::find($userId);
                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->name;
                    $_SESSION['email'] = $user->email;
                    $_SESSION['role'] = $user->role;
                    return true;
                }
            }
        }
        return false;
    }

    public function login($data)
    {
        // Démarrer la session au début
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si déjà connecté
        if (isset($_SESSION['user_id'])) {
            return ['success' => true];
        }

        // Validation des données
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'Please fill in all fields'];
        }

        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email format'];
        }

        // Rechercher l'utilisateur par email
        $user = User::where('email', $data['email'])->first();

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        if ($user && password_verify($data['password'], $user->password)) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->name;
            $_SESSION['email'] = $user->email;
            $_SESSION['role'] = $user->role;
            $_SESSION['last_login'] = time();

            // Gérer l'option "Remember me"
            if (isset($data['remember']) && $data['remember'] == 'on') {
                $this->createRememberToken($user->id, $_SERVER['REMOTE_ADDR']);
            }

            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Invalid credentials'];
    }

    public function register()
    {
        // Logique pour afficher le formulaire d'inscription
        include 'registration.php'; // Assurez-vous que le chemin est correct
    }

    public function store()
    {
        try {
            // Validation des données
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Vérifier si l'email existe déjà
            if (User::where('email', $email)->exists()) {
                return [
                    'success' => false,
                    'error' => 'Cette adresse email est déjà utilisée'
                ];
            }

            // Créer l'utilisateur
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user'
            ]);

            return [
                'success' => true,
                'user_id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'inscription : ' . $e->getMessage()
            ];
        }
    }

    public function logout()
    {
        // Déconnecter l'utilisateur
        session_start();
        session_unset();
        session_destroy();

        // Supprimer les cookies de rappel
        if (isset($_COOKIE['remember_token'])) {
            RememberToken::where('token', $_COOKIE['remember_token'])->delete();
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
        }

        // Rediriger vers la page de connexion
        header('Location: login.php');
        exit();
    }

    // Méthodes supplémentaires basées sur votre modèle User

    public function profile($userId)
    {
        // Récupérer les informations du profil de l'utilisateur
        $user = User::findOrFail($userId); // Récupère l'utilisateur ou lance une exception si non trouvé
        include 'views/profile.php'; // Inclure la vue du profil
    }

    public function editProfile($userId)
    {
        // Récupérer les informations de l'utilisateur pour l'édition du profil
        $user = User::findOrFail($userId);
        include 'views/edit_profile.php'; // Inclure le formulaire d'édition de profil
    }

    public function updateProfile($userId)
    {
        // Logique pour traiter la mise à jour du profil de l'utilisateur
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::findOrFail($userId);

            // Récupérer les données du formulaire
            $user->name = $_POST['name'] ?? $user->name;
            $user->email = $_POST['email'] ?? $user->email;
            $user->bio = $_POST['bio'] ?? $user->bio;
            $user->website = $_POST['website'] ?? $user->website;
            $user->facebook = $_POST['facebook'] ?? $user->facebook;
            $user->twitter = $_POST['twitter'] ?? $user->twitter;
            $user->instagram = $_POST['instagram'] ?? $user->instagram;
            $user->phone = $_POST['phone'] ?? $user->phone;
            $user->address = $_POST['address'] ?? $user->address;

            // Gestion de l'avatar (exemple basique)
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $avatarPath = 'uploads/' . time() . '_' . $_FILES['avatar']['name'];
                move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath);
                $user->avatar = $avatarPath;
            }

            // Validation des données si nécessaire

            $user->save();

            // Rediriger vers le profil de l'utilisateur ou afficher un message de succès
            header('Location: profile.php?id=' . $userId);
            exit();
        } else {
            // Si la méthode n'est pas POST, rediriger vers le formulaire d'édition
            header('Location: edit_profile.php?id=' . $userId);
            exit();
        }
    }

    public function changePassword($userId)
    {
        // Afficher le formulaire de changement de mot de passe
        $user = User::findOrFail($userId);
        include 'views/change_password.php';
    }

    public function updatePassword($userId)
    {
        // Logique pour traiter la mise à jour du mot de passe
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = User::findOrFail($userId);

            $oldPassword = $_POST['old_password'];
            $newPassword = $_POST['new_password'];
            $newPasswordConfirmation = $_POST['new_password_confirmation'];

            // Vérifier l'ancien mot de passe
            if (!password_verify($oldPassword, $user->password)) {
                // Gérer l'erreur : ancien mot de passe incorrect
                return false;
            }

            // Vérifier si les nouveaux mots de passe correspondent
            if ($newPassword !== $newPasswordConfirmation) {
                // Gérer l'erreur : les nouveaux mots de passe ne correspondent pas
                return false;
            }

            // Hacher le nouveau mot de passe
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->password = $hashedPassword;
            $user->save();

            // Rediriger vers le profil ou afficher un message de succès
            header('Location: profile.php?id=' . $userId);
            exit();
        } else {
            // Si la méthode n'est pas POST, rediriger vers le formulaire de changement de mot de passe
            header('Location: change_password.php?id=' . $userId);
            exit();
        }
    }

    public function sendPasswordResetLink($email)
    {
        try {
            $user = User::where('email', $email)->first();
            if (!$user) {
                throw new Exception('Aucun compte associé à cette adresse email.');
            }

            $token = bin2hex(random_bytes(32));
            $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

            PasswordReset::updateOrCreate(
                ['email' => $email],
                ['token' => $token, 'expires_at' => $expiration]
            );

            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/karma-master/reset-password.php?token=" . $token;
            
            // Utiliser le service mail pour envoyer l'email
            $this->mailService->sendPasswordReset($email, $resetLink);

            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'envoi du lien : ' . $e->getMessage());
        }
    }

    public function validateResetToken($token)
    {
        $reset = PasswordReset::where('token', $token)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();

        return $reset !== null;
    }

    public function resetPassword($token, $password)
    {
        try {
            $reset = PasswordReset::where('token', $token)
                ->where('expires_at', '>', date('Y-m-d H:i:s'))
                ->first();

            if (!$reset) {
                throw new Exception('Token invalide ou expiré');
            }

            $user = User::where('email', $reset->email)->first();
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();

            // Supprimer le token
            $reset->delete();

            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la réinitialisation : ' . $e->getMessage());
        }
    }

    public function getUserProfile($userId) {
        return User::find($userId);
    }

    public function getUserStats($userId) {
        $user = User::find($userId);
        return [
            'posts' => $user->posts()->count(),
            'followers' => 0, // À implémenter plus tard si nécessaire
            'following' => 0  // À implémenter plus tard si nécessaire
        ];
    }

    public function getUserActivities($userId, $limit = 5) {
        // Pour l'instant, on retourne les derniers posts de l'utilisateur comme activités
        $user = User::find($userId);
        $activities = $user->posts()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($post) {
                return [
                    'title' => $post->title,
                    'description' => 'A publié un nouveau post',
                    'icon' => 'pencil',
                    'created_at' => $post->created_at
                ];
            });
        
        return $activities->toArray();
    }

    public function updateUserInfo($userId, $data)
    {
        try {
            $user = User::find($userId);
            if (!$user) return false;

            $fillableData = array_intersect_key($data, array_flip([
                'name',
                'website',
                'phone',
                'address',
                'facebook',
                'twitter',
                'instagram'
            ]));
            
            $user->fill($fillableData);
            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateUserBio($userId, $bio)
    {
        try {
            $user = User::find($userId);
            if (!$user) return false;

            $user->fill(['bio' => $bio]);
            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateUserAvatar($userId, $file)
    {
        try {
            $user = User::find($userId);
            if (!$user) return ['success' => false];

            // Vérifier si c'est une URL externe
            if (isset($_POST['avatar_url']) && filter_var($_POST['avatar_url'], FILTER_VALIDATE_URL)) {
                $user->avatar = $_POST['avatar_url'];
                $user->save();
                
                return [
                    'success' => true,
                    'avatar_url' => $user->avatar,
                    'message' => 'Avatar mis à jour avec succès'
                ];
            }

            // Sinon, traiter le fichier uploadé
            // Vérification du type de fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                return ['success' => false, 'message' => 'Type de fichier non autorisé'];
            }

            // Création du dossier si nécessaire
            $uploadDir = 'uploads/avatars/';
            $fullUploadPath = $_SERVER['DOCUMENT_ROOT'] . '/karma-master/' . $uploadDir;
            
            if (!is_dir($fullUploadPath)) {
                mkdir($fullUploadPath, 0777, true);
            }

            // Suppression de l'ancien avatar si existant
            if ($user->avatar && file_exists($fullUploadPath . $user->avatar)) {
                unlink($fullUploadPath . $user->avatar);
            }

            // Génération d'un nom de fichier unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('avatar_') . '.' . $extension;
            $targetPath = $fullUploadPath . $fileName;

            // Déplacement du fichier
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $user->avatar = $fileName;
                $user->save();
                
                return [
                    'success' => true,
                    'avatar_url' => '/karma-master/' . $uploadDir . $fileName
                ];
            }

            return ['success' => false, 'message' => 'Erreur lors du téléchargement'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur serveur'];
        }
    }

    public function getUserPosts($userId) {
        try {
            return Post::with(['images', 'categorie'])
                      ->where('user_id', $userId)
                      ->orderBy('created_at', 'desc')
                      ->get()
                      ->toArray();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getAllUsers()
    {
        $users = User::withCount('posts')
            ->orderBy('posts_count', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function($user) {
                $data = $user->toArray();
                $data['avatar'] = empty($user->avatar) 
                    ? "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($user->name)
                    : (!filter_var($user->avatar, FILTER_VALIDATE_URL) 
                        ? '/karma-master/uploads/avatars/' . $user->avatar 
                        : $user->avatar);
                return $data;
            });
        
        return $users->toArray();
    }

    public function sendEmailVerification($userId)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }

            $token = bin2hex(random_bytes(32));
            $user->email_verification_token = $token;
            $user->save();

            $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . "/karma-master/verify-email.php?token=" . $token;
            
            $mailService = new \App\Services\MailService();
            $result = $mailService->sendEmailVerification($user->email, $verificationLink);

            if ($result) {
                return ['success' => true];
            }
            throw new Exception('Erreur lors de l\'envoi de l\'email');
        } catch (Exception $e) {
            throw new Exception('Erreur de vérification email: ' . $e->getMessage());
        }
    }

    public function verifyEmail($token)
    {
        try {
            $user = User::where('email_verification_token', $token)->first();
            if (!$user) {
                throw new Exception('Token invalide ou expiré');
            }

            $user->is_email_verified = true;
            $user->email_verification_token = null;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception('Erreur de vérification: ' . $e->getMessage());
        }
    }

    public function sendWhatsAppVerification($userId)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }

            if (empty($user->phone)) {
                throw new Exception('Veuillez d\'abord ajouter un numéro de téléphone');
            }

            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $user->phone_verification_token = $code;
            $user->save();

            $verificationService = new \App\Services\VerificationService();
            $result = $verificationService->sendWhatsAppVerification($user->phone, $code);

            if ($result) {
                return ['success' => true];
            }
            throw new Exception('Erreur lors de l\'envoi du code WhatsApp');
        } catch (Exception $e) {
            throw new Exception('Erreur de vérification WhatsApp: ' . $e->getMessage());
        }
    }

    public function verifyWhatsAppCode($userId, $code)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }

            if ($user->phone_verification_token !== $code) {
                throw new Exception('Code de vérification invalide');
            }

            $user->is_phone_verified = true;
            $user->phone_verification_token = null;
            $user->save();

            return ['success' => true];
        } catch (Exception $e) {
            throw new Exception('Erreur de vérification: ' . $e->getMessage());
        }
    }
}
