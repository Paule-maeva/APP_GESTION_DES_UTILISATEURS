<?php
class Session {
    private $conn;
    private $table_name = "sessions";

    public function __construct($db) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $db;
    }

    // Enregistre une nouvelle session de connexion
    public function create($user_id) {
        $query = "INSERT INTO " . $this->table_name . " (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }
    // Vérifie si l'utilisateur est connecté
    public function isAuthenticated() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    // Vérifie si l'utilisateur est un administrateur
    public function isAdmin() {
        return $this->isAuthenticated() && ($_SESSION['user']['role_id'] ?? 0) == 1;
    }

    // Démarre une session utilisateur
    public function startUserSession($user_data) {
        $_SESSION['user'] = [
            'id' => $user_data['id'],
            'username' => $user_data['username'],
            'email' => $user_data['email'],
            'role_id' => $user_data['role_id']
        ];
    }

    // Détruit la session
    public function destroy() {
        session_unset();
        session_destroy();
    }

    // Récupère les sessions par utilisateur
    public function getByUserId($user_id, $limit = null) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE user_id = :user_id 
                 ORDER BY login_time DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère les connexions récentes
    public function getRecentLogins($limit = 5) {
        $query = "SELECT s.*, u.username, u.email 
                 FROM " . $this->table_name . " s
                 JOIN users u ON s.user_id = u.id
                 ORDER BY s.login_time DESC 
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>