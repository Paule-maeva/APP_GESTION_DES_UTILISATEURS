<?php
// Vérification de l'authentification et du rôle
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
// Récupérer les données de l'utilisateur depuis le contrôleur
$user = $userData ?? [];
$sessions = $sessionsHistory ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard Utilisateur</title>
</head>
<body class="bg-gray-100">
<header class="bg-purple-600 shadow-md w-full fixed top-0 left-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
        <h1 class="text-2xl font-bold text-white">Tableau de Bord</h1>
        <div class="flex items-center space-x-4">
            <span class="text-white"><?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?></span>
            <a href="index.php?action=logout" class="text-white bg-red-500 px-3 py-2 rounded-lg hover:bg-red-600 transition duration-200">Déconnexion</a>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto mt-20 p-6">
    <!-- Section Profil -->
    <section id="profil" class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mon Profil</h2>
         
        <form method="POST" action="index.php?action=updateProfile" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2">Nom d'utilisateur :</label>
                <input type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="w-full p-2 border rounded-lg bg-gray-50" disabled>
            </div>
            
            <div>
                <label class="block text-gray-700 mb-2">Email :</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-purple-300" required>
            </div>
            
             
            
            <button type="submit" name="update_profile" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-200 w-full">
                Mettre à jour
            </button>
        </form>
    </section>

    <!-- Section Historique -->
    <!-- Section Historique -->
<section id="historique" class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Historique des Connexions</h2>
    
    <?php if (!empty($sessions)): ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3 border-b">Date de Connexion</th>
                        <th class="p-3 border-b">Date de Déconnexion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b"><?= date('d/m/Y H:i', strtotime($session['login_time'])) ?></td>
                            <td class="p-3 border-b"><?= $session['logout_time'] ? date('d/m/Y H:i', strtotime($session['logout_time'])) : 'En cours' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Aucun historique de connexion disponible.</p>
    <?php endif; ?>
</section>

    <!-- Section Changement de mot de passe -->
     
</main>

<!-- Notifications -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<script>
    // Script pour faire disparaître les notifications après 5 secondes
    setTimeout(() => {
        const notifications = document.querySelectorAll('[class*="fixed bottom-4"]');
        notifications.forEach(notification => {
            notification.style.display = 'none';
        });
    }, 5000);
</script>
</body>
</html>