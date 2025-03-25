<?php
class UserController {
    private $userModel;
    private $roleModel;
    private $sessionModel;

    public function __construct($userModel, $roleModel, $sessionModel) {
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->sessionModel = $sessionModel;
    }

    public function userDashboard() {
        $this->checkAuthentication();
        
        // Vérifier si l'utilisateur est admin
        if ($_SESSION['role_id'] == 1) {
            header("Location: index.php?action=adminDashboard");
            exit();
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        $loginHistory = $this->sessionModel->getByUserId($_SESSION['user_id']);

        require_once __DIR__.'/../Views/userdashboard.php';
    }

    public function editProfile() {
        $this->checkAuthentication();
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        require_once __DIR__.'/../Views/user/edit_profile.php';
    }

    public function updateProfile() {
        $this->checkAuthentication();
        
        $id = $_SESSION['user_id'];
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        // Vérifier le mot de passe actuel
        $user = $this->userModel->getById($id);
        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['error'] = 'Mot de passe actuel incorrect';
            header("Location: index.php?action=user/edit");
            exit();
        }

        // Mettre à jour les informations
        $this->userModel->id = $id;
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        
        if (!empty($newPassword)) {
            $this->userModel->password = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        if ($this->userModel->update()) {
            $_SESSION['success'] = 'Profil mis à jour avec succès';
            header("Location: index.php?action=userDashboard");
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour du profil';
            header("Location: index.php?action=user/edit");
        }
        exit();
    }

    // Méthodes pour l'administration
    public function adminDashboard() {
        $this->checkAdminAccess();
        
        $users = $this->userModel->getAll();
        $roles = $this->roleModel->getAll();
        
        require_once __DIR__.'/../Views/admin/dashboard.php';
    }

    public function editUser($userId) {
        $this->checkAdminAccess();
        
        $user = $this->userModel->getById($userId);
        $roles = $this->roleModel->getAll();
        
        require_once __DIR__.'/../Views/admin/edit_user.php';
    }

    public function updateUser($userId) {
        $this->checkAdminAccess();
        
        $this->userModel->id = $userId;
        $this->userModel->username = $_POST['username'] ?? '';
        $this->userModel->email = $_POST['email'] ?? '';
        $this->userModel->role_id = $_POST['role_id'] ?? 2;
        $this->userModel->status = $_POST['status'] ?? 'active';

        if ($this->userModel->update()) {
            $_SESSION['success'] = 'Utilisateur mis à jour avec succès';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise à jour';
        }
        
        header("Location: index.php?action=adminDashboard");
        exit();
    }

    public function deleteUser($userId) {
        $this->checkAdminAccess();
        
        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'Utilisateur supprimé avec succès';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        header("Location: index.php?action=adminDashboard");
        exit();
    }

    private function checkAuthentication() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    private function checkAdminAccess() {
        $this->checkAuthentication();
        
        if ($_SESSION['role_id'] != 1) {
            header("Location: index.php?action=userDashboard");
            exit();
        }
    }
}
?>