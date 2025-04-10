<?php
class User {
    private $conn;
    private $table_name = "users";

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

    public function login($email, $password) {
        $query = "SELECT id, username, password, role_id, status FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row['status'] == 'inactive') {
                return "inactive";
            }
    
            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->role_id = $row['role_id'];
                return "success";
            } else {
                return "incorrect_password";
            }
        }
        
        return "email ieistant";
    }

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (username, email, password, role_id, status) 
                VALUES (:username, :email, :password, :role_id, :status)";
        
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role_id", $this->role_id);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET username = :username, email = :email, 
                     role_id = :role_id, status = :status 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role_id", $this->role_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    public function updateProfile($data) {
        $query = "UPDATE " . $this->table_name . " 
                 SET username = :username, email = :email, phone = :phone 
                 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':id', $data['id']);
        
        return $stmt->execute();
    }

    public function updatePassword($userId, $currentPassword, $newPassword) {
        $user = $this->getById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            return false;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        
        return $stmt->execute();
    }
    public function getByEmail($email) {
        $query = "SELECT * FROM ".$this->table_name." WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithRoles($limit = null, $offset = null) {
        $query = "SELECT u.*, r.name as role_name 
                  FROM " . $this->table_name . " u 
                  JOIN roles r ON u.role_id = r.id
                  ORDER BY u.created_at DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit";
        }
        if ($offset !== null) {
            $query .= " OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
 * Afficher le formulaire d'ajout d'utilisateur
 */
public function addUserForm() {
    $this->checkAdminAccess();
    
    try {
        $roles = $this->roleModel->getAll();
        require_once __DIR__ . '/../Views/admin/add_user.php';
    } catch (Exception $e) {
        $this->redirect("adminDashboard", $e->getMessage(), "error");
    }
}

/**
 * Traiter l'ajout d'un nouvel utilisateur
 */
public function addUser() {
    $this->checkAdminAccess();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect("adminDashboard", "Méthode non autorisée", "error");
    }

    try {
        $username = trim(htmlspecialchars($_POST['username'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $role_id = intval($_POST['role_id'] ?? 2);
        $status = in_array($_POST['status'] ?? 'active', ['active', 'inactive']) ? $_POST['status'] : 'active';

        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        if (!$email) {
            throw new Exception("Adresse email invalide");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Les mots de passe ne correspondent pas");
        }

        if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            throw new Exception("Le mot de passe doit contenir 8 caractères dont une majuscule et un chiffre");
        }

        // Vérifier si l'email existe déjà
        if ($this->userModel->getByEmail($email)) {
            throw new Exception("Cet email est déjà utilisé");
        }

        // Créer l'utilisateur
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role_id' => $role_id,
            'status' => $status
        ];

        if ($this->userModel->create($data)) {
            $this->redirect("adminDashboard", "Utilisateur créé avec succès");
        } else {
            throw new Exception("Échec de la création de l'utilisateur");
        }
    } catch (Exception $e) {
        $this->redirect("addUserForm", $e->getMessage(), "error");
    }
}
}
?>