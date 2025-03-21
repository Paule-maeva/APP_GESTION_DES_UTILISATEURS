<?php
$server_name = "localhost";  // Nom du serveur MySQL
$username = "root";          // Nom d'utilisateur (par défaut "root" sous XAMPP)
$password = "";              // Mot de passe (vide sous XAMPP par défaut)
$database_name = "gestion_utilisateurs"; // Nom de la base de données

try {
    // Connexion à MySQL avec PDO
    $conn = new PDO("mysql:host=$server_name;dbname=$database_name;charset=utf8", $username, $password);
    
    // Configuration de PDO pour afficher les erreurs sous forme d'exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion réussie à la base de données !";
} catch(PDOException $e) {
    echo "❌ La connexion a échoué : " . $e->getMessage();
}
?>
