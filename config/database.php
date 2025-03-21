<?php
  class Database {
      private $host = "localhost";
      private $db_name = "gestion_utilisateurs";
      private $username = "root";
      private $password = "";
      private $conn;
  
      // Méthode pour établir la connexion
      public function getConnection() {
          $this->conn = null;
          
          try {
              // Créer une nouvelle connexion PDO
              $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", 
                                    $this->username, 
                                    $this->password);
  
              // Définir le mode d'erreur sur Exception
              $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
          } catch (PDOException $e) {
              // En cas d'erreur, afficher un message
            //   echo "Connexion échouée : " . $e->getMessage();
          }
  
          return $this->conn;
      }
  }
  ?>
  

