<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenue sur notre plateforme</h1>
            <p>Gestion sécurisée des utilisateurs</p>
        </header>
        
        <main>
            <div class="cta-buttons">
                <!-- Remplacez les liens actuels par : -->
<!-- Remplacez par ces liens absolus depuis la racine -->
<a href="   ./public/index.php?action=login" class="lien-style">Se connecter</a>
<a href="  ./public/index.php?action=register" class="lien-style">S'inscrire</a>
            </div>
            
            <div class="features">
                <div class="feature">
                    <h3>Pour les administrateurs</h3>
                    <p>Gestion complète des utilisateurs, visualisation des logs, etc.</p>
                </div>
                <div class="feature">
                    <h3>Pour les utilisateurs</h3>
                    <p>Gestion de votre profil, consultation de votre historique, etc.</p>
                </div>
            </div>
        </main>
        
        <footer>
            <p>&copy; <?= date('Y') ?> Gestion des Utilisateurs. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>