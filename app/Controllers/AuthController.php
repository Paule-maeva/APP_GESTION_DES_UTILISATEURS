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
        $this->createAdminIfNotExists();
    }

    
    
    public function showRegister() {
        require_once __DIR__.'/../Views/register.php'; // Chemin direct
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
            // Dans AuthController->register()
            $this->user->password = password_hash($password, PASSWORD_BCRYPT);
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

    public function showLogin() {
         require_once __DIR__.'/../Views/login.php'; 
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
    
            // Debug complet
            error_log("Tentative de connexion avec: ".$email);
            
            $user = $this->user->getByEmail($email); // Ajoutez cette méthode à User.php
            if (!$user) {
                error_log("Utilisateur non trouvé");
                $_SESSION['error'] = "Identifiants incorrects";
                header("Location: index.php?action=login");
                exit();
            }
    
            error_log("Données utilisateur trouvées: ".print_r($user, true));
            error_log("Comparaison mot de passe: ".password_verify($password, $user['password']) ? 'OK' : 'NOK');
    
            if ($user['status'] != 'active') {
                $_SESSION['error'] = "Compte désactivé";
                header("Location: index.php?action=login");
                exit();
            }
    
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                
                error_log("Connexion réussie, redirection vers dashboard");
                header("Location: index.php?action=".($user['role_id'] == 1 ? 'adminDashboard' : 'userDashboard'));
                exit();
            }
    
            $_SESSION['error'] = "Mot de passe incorrect";
            header("Location: index.php?action=login");
            exit();
        }
    }

    //gestion connexion en tant que admin
    public function createAdminIfNotExists() {
        $adminEmail = 'admin@gmail.com';
        
        // Vérifier existence par email seulement
        if (!$this->user->emailExists($adminEmail)) {
            try {
                // D'abord supprimer tout admin existant avec le même username
                $this->db->query("DELETE FROM users WHERE username = 'admin'");
                
                // Puis créer le nouvel admin
                $hash = password_hash('admin123', PASSWORD_BCRYPT);
                $stmt = $this->db->prepare("INSERT INTO users 
                                         (username, email, password, role_id, status) 
                                         VALUES (?, ?, ?, 1, 'active')");
                $stmt->execute(['admin', $adminEmail, $hash]);
            } catch (PDOException $e) {
                error_log("Admin existe déjà ou autre erreur: ".$e->getMessage());
            }
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