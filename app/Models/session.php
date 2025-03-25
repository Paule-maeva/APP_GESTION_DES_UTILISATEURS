<?php
class Session {
    private $conn;
    private $table_name = "sessions";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Enregistre une nouvelle session de connexion
    public function create($user_id, $ip_address, $user_agent) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (user_id, ip_address, user_agent) 
                 VALUES (:user_id, :ip_address, :user_agent)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":ip_address", $ip_address);
        $stmt->bindParam(":user_agent", $user_agent);
        
        return $stmt->execute();
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

    // Récupère les connexions récentes (pour le tableau de bord admin)
    public function getRecentLogins($limit = 5) {
        $query = "SELECT s.*, u.username 
                 FROM " . $this->table_name . " s
                 JOIN users u ON s.user_id = u.id
                 ORDER BY s.login_time DESC 
                 LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Autres méthodes utiles...
}
?>