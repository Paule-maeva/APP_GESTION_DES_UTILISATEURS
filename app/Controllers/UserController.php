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

    /**
     * Redirection avec message flash
     */
    private function redirect($route, $message = null, $type = 'success') {
        if ($message) {
            $_SESSION['flash'] = [
                'message' => $message,
                'type' => $type
            ];
        }
        header("Location: index.php?action=$route");
        exit();
    }

    /**
     * Vérifie si l'utilisateur est authentifié
     */
    private function checkAuthentication() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect("login", "Veuillez vous connecter", "error");
        }
    }

    /**
     * Vérifie si l'utilisateur est administrateur
     */
    private function checkAdminAccess() {
        $this->checkAuthentication();
        if ($_SESSION['role_id'] != 1) {
            $this->redirect("userDashboard", "Accès refusé : permissions insuffisantes", "error");
        }
    }

    /***************************************
     * METHODES ESPACE UTILISATEUR
     ***************************************/

    /**
     * Tableau de bord utilisateur
     */
    public function userDashboard() {
        $this->checkAuthentication();

        // Rediriger les admins vers le dashboard admin
        if ($_SESSION['role_id'] == 1) {
            $this->redirect("adminDashboard");
        }

        try {
            $user = $this->userModel->getById($_SESSION['user_id']);
            if (!$user) {
                throw new Exception("Utilisateur non trouvé");
            }

            $loginHistory = $this->sessionModel->getByUserId($_SESSION['user_id'], 10);
            
            require_once __DIR__ . '/../Views/userdashboard.php';
        } catch (Exception $e) {
            $this->redirect("login", $e->getMessage(), "error");
        }
    }

    /**Afficher le formulaire d'édition de profil
     */
    public function editProfile() {
        $this->checkAuthentication();
        
        try {
            $user = $this->userModel->getById($_SESSION['user_id']);
            if (!$user) {
                throw new Exception("Utilisateur non trouvé");
            }
            
            require_once __DIR__ . '/../Views/user/editprofile.php';
        } catch (Exception $e) {
            $this->redirect("userDashboard", $e->getMessage(), "error");
        }
    }

    /**
     * Traiter la mise à jour du profil
     */
    public function updateProfile() {
        $this->checkAuthentication();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("userDashboard", "Méthode non autorisée", "error");
        }

        try {
            $id = $_SESSION['user_id'];
            $username = trim(htmlspecialchars($_POST['username'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $phone = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');

            // Validation
            if (empty($username) || empty($email)) {
                throw new Exception("Tous les champs obligatoires doivent être remplis");
            }

            if (!$email) {
                throw new Exception("Adresse email invalide");
            }

            // Vérifier si l'email existe déjà pour un autre utilisateur
            $existingUser = $this->userModel->getByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception("Cet email est déjà utilisé par un autre compte");
            }

            $data = [
                'id' => $id,
                'username' => $username,
                'email' => $email,
                'phone' => $phone
            ];

            if ($this->userModel->updateProfile($data)) {
                // Mettre à jour les données de session si nécessaire
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                
                $this->redirect("userDashboard", "Profil mis à jour avec succès");
            } else {
                throw new Exception("Échec de la mise à jour du profil");
            }
        } catch (Exception $e) {
            $this->redirect("editProfile", $e->getMessage(), "error");
        }
    }

    /**
     * Traiter la mise à jour du mot de passe
     */
    public function updatePassword() {
        $this->checkAuthentication();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("userDashboard", "Méthode non autorisée", "error");
        }

        try {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($current) || empty($new) || empty($confirm)) {
                throw new Exception("Tous les champs doivent être remplis");
            }

            if (strlen($new) < 8 || !preg_match('/[A-Z]/', $new) || !preg_match('/[0-9]/', $new)) {
                throw new Exception("Le mot de passe doit contenir 8 caractères dont une majuscule et un chiffre");
            }

            if ($new !== $confirm) {
                throw new Exception("Les nouveaux mots de passe ne correspondent pas");
            }

            if ($this->userModel->updatePassword($_SESSION['user_id'], $current, $new)) {
                $this->redirect("userDashboard", "Mot de passe mis à jour avec succès");
            } else {
                throw new Exception("Mot de passe actuel incorrect");
            }
        } catch (Exception $e) {
            $this->redirect("userDashboard", $e->getMessage(), "error");
        }
    }

    /***************************************
     * METHODES ESPACE ADMINISTRATEUR
     ***************************************/

    /**
     * Tableau de bord administrateur
     */
    public function adminDashboard() {
        $this->checkAdminAccess();

        try {
            // Pagination
            $page = max(1, intval($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;

            // Récupération des données
            $users = $this->userModel->getAllWithRoles($limit, $offset);
            $totalUsers = $this->userModel->countAll();
            $totalPages = ceil($totalUsers / $limit);
            $roles = $this->roleModel->getAll();
            $recentLogins = $this->sessionModel->getRecentLogins(5);

            require_once __DIR__ . '/../Views/admindashboard.php';
        } catch (Exception $e) {
            $this->redirect("login", $e->getMessage(), "error");
        }
    }

    /**
     * Afficher le formulaire d'édition d'un utilisateur
     */
    public function editUser($userId) {
        $this->checkAdminAccess();

        try {
            $userId = intval($userId);
            $user = $this->userModel->getById($userId);
            
            if (!$user) {
                throw new Exception("Utilisateur non trouvé");
            }

            // Empêcher la modification du super admin
            if ($user['id'] == 1 && $_SESSION['user_id'] != 1) {
                throw new Exception("Vous ne pouvez pas modifier le super administrateur");
            }

            $roles = $this->roleModel->getAll();
            require_once __DIR__ . '/../Views/admin/edit_user.php';
        } catch (Exception $e) {
            $this->redirect("adminDashboard", $e->getMessage(), "error");
        }
    }

    /**
     * Traiter la mise à jour d'un utilisateur
     */
    public function updateUser($userId) {
        $this->checkAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("adminDashboard", "Méthode non autorisée", "error");
        }

        try {
            $userId = intval($userId);
            $username = trim(htmlspecialchars($_POST['username'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
            $role_id = intval($_POST['role_id'] ?? 2);
            $status = in_array($_POST['status'] ?? 'active', ['active', 'inactive']) ? $_POST['status'] : 'active';

            // Validation
            if (empty($username) || empty($email)) {
                throw new Exception("Tous les champs obligatoires doivent être remplis");
            }

            if (!$email) {
                throw new Exception("Adresse email invalide");
            }

            // Vérifier si l'email existe déjà pour un autre utilisateur
            $existingUser = $this->userModel->getByEmail($email);
            if ($existingUser && $existingUser['id'] != $userId) {
                throw new Exception("Cet email est déjà utilisé par un autre compte");
            }

            // Protection du super admin
            if ($userId == 1) {
                $role_id = 1; // Forcer le rôle admin
                $status = 'active'; // Forcer le statut actif
            }

            $data = [
                'id' => $userId,
                'username' => $username,
                'email' => $email,
                'role_id' => $role_id,
                'status' => $status
            ];

            if ($this->userModel->update($data)) {
                $this->redirect("adminDashboard", "Utilisateur mis à jour avec succès");
            } else {
                throw new Exception("Échec de la mise à jour de l'utilisateur");
            }
        } catch (Exception $e) {
            $this->redirect("editUser&id=$userId", $e->getMessage(), "error");
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($userId) {
        $this->checkAdminAccess();

        try {
            $userId = intval($userId);
            
            // Empêcher l'auto-suppression
            if ($userId == $_SESSION['user_id']) {
                throw new Exception("Vous ne pouvez pas supprimer votre propre compte");
            }

            // Protection du super admin
            if ($userId == 1) {
                throw new Exception("Impossible de supprimer le super administrateur");
            }

            if ($this->userModel->delete($userId)) {
                $this->redirect("adminDashboard", "Utilisateur supprimé avec succès");
            } else {
                throw new Exception("Échec de la suppression de l'utilisateur");
            }
        } catch (Exception $e) {
            $this->redirect("adminDashboard", $e->getMessage(), "error");
        }
    }
}