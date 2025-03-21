<?php
// Inclure le contrôleur d'authentification
include_once '../app/Controllers/AuthController.php';

// Instancier le contrôleur
$authController = new AuthController();

// Obtenir l'action demandée depuis l'URL, avec 'login' comme valeur par défaut
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Router vers la méthode appropriée en fonction de l'action
switch($action) {
    case 'register':
        $authController->showRegisterForm();
        break;
    case 'register_process':
        $authController->register();
        break;
    case 'login':
        $authController->showLoginForm();
        break;
    case 'login_process':
        $authController->login();  
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'userDashboard':
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        include '../app/Views/UserDashboard.php';
        break;
    case 'adminDashboard':
        if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            header("Location: index.php?action=login");
            exit();
        }
        include '../app/Views/admindashboard.php';
        break;
    default:
        $authController->showLoginForm();
        break;
         
        
}
?>