<?php
class Role {
    private $conn;
    private $table_name = "roles";

    public function __construct($db) {
        $this->conn = $db;
    }
//Récupère tous les rôles de la table @return array Tableau associatif contenant tous les rôles
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Récupère un rôle spécifique par son ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
//Récupère uniquement le nom d'un rôle par son ID
    public function getNameById($id) {
        $role = $this->getById($id);
        return $role ? $role['name'] : null;
    }
}
?>