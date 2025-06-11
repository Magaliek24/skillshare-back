<?php

declare(strict_types=1);

namespace App\controller;

use App\core\attributes\Route;
use App\model\User;
use App\repository\UserRepository;
use App\services\FileUploadService;
use DateTime;
use Exception;

class UserController
{
    #[Route('/api/upload-avatar', 'POST')]
    public function upload_avatar()
    {
        if (!isset($_FILES['avatar'])) throw new Exception('Aucun fichier uploadé!');

        try {
            $filename = FileUploadService::handle_avatar_upload($_FILES['avatar'], __DIR__ . '/../../public/uploads/avatar/');
            // if ($user->getAvatar() !== 'mon_avatar_par_defaut.png') {
            //     FileUploadService::delete_old_avatar($user->getAvatar());
            // }
            echo json_encode([
                'success' => true,
                'message' => 'Avatar mis à jour avec succès',
                'filename' => $filename

            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'upload : " . $e->getMessage());
        }
    }


    #[Route('/api/register', 'POST')]
    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) throw new Exception('Json invalide');

        $user_data = [
            'username' => $data['username'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => password_hash($data['password'], PASSWORD_BCRYPT) ?? '',
            'role' => $data['role'] ?? '',
            // 'avatar' => $data['avatar'] ?? '', 
        ];

        // création user
        $user = new User($user_data);
        $user->setCreatedAt((new DateTime())->format('Y-m-d H:1:s'));
        $user_repository = new UserRepository();
        $saved = $user_repository->save($user);

        if (!$saved) throw new Exception('Erreur lors de la sauvegarde');


        echo json_encode([
            'success' => true,
            'message' => 'Inscription réussie ! Veuillez vérifier vos email.' . json_encode($data)
        ]);
    }
}
