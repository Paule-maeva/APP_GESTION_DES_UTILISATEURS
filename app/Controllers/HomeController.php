<?php
class HomeController {
    private $sessionModel;

    public function __construct($sessionModel) {
        $this->sessionModel = $sessionModel;
    }

    public function index() {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if ($this->sessionModel->isAuthenticated()) {
            if ($this->sessionModel->isAdmin()) {
                header("Location: index.php?action=admin/dashboard");
            } else {
                header("Location: index.php?action=user/dashboard");
            }
            exit();
        }
        
        require_once __DIR__.'/../Views/home.php';
    }
}
?>