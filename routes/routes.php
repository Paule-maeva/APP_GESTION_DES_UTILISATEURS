<?php
session_start();

// Initialisation de la base de données
require_once __DIR__.'/../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Initialisation des modèles
require_once __DIR__.'/../app/Models/User.php';
require_once __DIR__.'/../app/Models/Session.php';
require_once __DIR__.'/../app/Models/Role.php';

$userModel = new User($db);
$sessionModel = new Session($db);
$roleModel = new Role($db);

// Initialisation des contrôleurs
require_once __DIR__.'/../app/Controllers/AuthController.php';
require_once __DIR__.'/../app/Controllers/UserController.php';
require_once __DIR__.'/../app/Controllers/HomeController.php';

$authController = new AuthController($userModel, $sessionModel);
$userController = new UserController($userModel, $roleModel, $sessionModel);
$homeController = new HomeController($sessionModel);

// Récupération de l'action
$action = $_GET['action'] ?? 'home';

// Router
switch ($action) {
    // Pages publiques
    case 'home':
        $homeController->index();
        break;
        
    case 'login':
        $authController->showLogin();
        break;
    
    case 'login_process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            header("Location: index.php?action=login");
            exit();
        }
        break;
        
    case 'register':
        $authController->showRegister();
        break;
    
    case 'register_process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            header("Location: index.php?action=register");
            exit();
        }
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    // Espace utilisateur
    case 'userDashboard':
        $userController->userDashboard();
        break;
        
    case 'editProfile':
        $userController->editProfile();
        break;
        
    case 'updateProfile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController->updateProfile();
        } else {
            header("Location: index.php?action=userDashboard");
            exit();
        }
        break;
        
    // Espace admin
    case 'adminDashboard':
        $userController->adminDashboard();
        break;
        
    case 'editUser':
        $userId = $_GET['id'] ?? 0;
        $userController->editUser($userId);
        break;
        
    case 'updateUser':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['id'] ?? 0;
            $userController->updateUser($userId);
        } else {
            header("Location: index.php?action=adminDashboard");
            exit();
        }
        break;
        
    case 'deleteUser':
        $userId = $_GET['id'] ?? 0;
        $userController->deleteUser($userId);
        break;
        
    // Par défaut   redirection
    default:
        header("Location: index.php?action=home");
        exit();
}
?>