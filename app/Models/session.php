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
    public function create($user_id, $ip_address) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (user_id, ip_address, login_time) 
                 VALUES (:user_id, :ip_address, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":ip_address", $ip_address);
        return $stmt->execute();
    }

    public function getLoginHistory($user_id, $limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE user_id = :user_id 
                 ORDER BY login_time DESC 
                 LIMIT " . (int)$limit;
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function startUserSession($user_data) {
        $_SESSION['user'] = [
            'id' => $user_data['id'],
            'username' => $user_data['username'],
            'email' => $user_data['email'],
            'role_id' => $user_data['role_id']
        ];
    }

    public function destroy() {
        session_destroy();
    }

    public function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    public function isAdmin() {
        return $this->isAuthenticated() && $_SESSION['user']['role_id'] == 1;
    }
    public function getByUserId($userId) {
        $query = "SELECT * FROM sessions WHERE user_id = :user_id ORDER BY login_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>