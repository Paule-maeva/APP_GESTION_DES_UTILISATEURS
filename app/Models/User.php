<?php

class User {
    private $conn;
    private $table_name = "users";

    // Propriétés de l'utilisateur
    public $id;
    public $username;
    public $email;
    public $password;
    public $role_id;
    public $status;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Vérifier si l'email existe
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Création d'un nouvel utilisateur
    public function create(){
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, role_id, status) VALUES (:username, :email, :password, :role_id, :status)";
        $stmt = $this->conn->prepare($query);

        // Sécuriser les données
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT, ["cost" => 12]);

        // Bind des paramètres
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role_id", $this->role_id);
        $stmt->bindParam(":status", $this->status);

        return $stmt->execute();
    }

    // Vérifier si un utilisateur existe avec ces identifiants
    public function login($email, $inputPassword) {
        $query = "SELECT id, username, password, role_id, status FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['status'] == 'inactive') {
                return "inactive";
            }

            if (password_verify($inputPassword, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role_id = $row['role_id'];
                return "success";
            } else {
                return "incorrect_password";
            }
        }
        
        return "user_not_found";
    }
}

?>
