<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../../config/database.php';

// Assurez-vous que session_start() n'est appelé qu'une seule fois
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function showRegisterForm() {
        require '../app/Views/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et nettoyer les données du formulaire
            $username = trim(htmlspecialchars($_POST['username']));
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validation des données
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Tous les champs sont requis";
                header("Location: index.php?action=register");
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Format d'email invalide";
                header("Location: index.php?action=register");
                exit();
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
                header("Location: index.php?action=register");
                exit();
            }

            if ($this->user->emailExists($email)) {
                $_SESSION['error'] = "Cet email est déjà utilisé";
                header("Location: index.php?action=register");
                exit();
            }

            // Préparer les données pour l'insertion
            $this->user->username = $username;
            $this->user->email = $email;
            $this->user->password = $password;
            $this->user->role_id = 2; // Role utilisateur par défaut
            $this->user->status = 'active';

            // Tenter de créer l'utilisateur
            if ($this->user->create()) {
                $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                header("Location: index.php?action=login");
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription. Veuillez réessayer.";
                header("Location: index.php?action=register");
                exit();
            }
        }
    }

    public function showLoginForm() {
        require '../app/Views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer et nettoyer les données du formulaire
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            // Validation des données
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Tous les champs sont requis";
                header("Location: index.php?action=login");
                exit();
            }

            // Tenter de connecter l'utilisateur
            $loginResult = $this->user->login($email, $password);

            if ($loginResult == "success") {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['role_id'] = $this->user->role_id;
                
                // Rediriger en fonction du rôle
                if ($_SESSION['role_id'] == 1) { // Admin
                    header("Location: index.php?action=adminDashboard");
                } else { // Utilisateur normal
                    header("Location: index.php?action=userDashboard");
                }
                exit();
            } elseif ($loginResult == "inactive") {
                $_SESSION['error'] = "Compte inactif. Contactez l'administration.";
            } elseif ($loginResult == "incorrect_password") {
                $_SESSION['error'] = "Mot de passe incorrect";
            } else {
                $_SESSION['error'] = "Utilisateur non trouvé";
            }

            header("Location: index.php?action=login");
            exit();
        }
    }

    public function logout() {
        // Détruire la session
        session_unset();
        session_destroy();
        
        // Rediriger vers la page de connexion
        header("Location: index.php?action=login");
        exit();
    }
}
?>