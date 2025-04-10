<?php
// Vérification des permissions admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Modifier l'utilisateur</h1>
        
        <form action="index.php?action=updateUser&id=<?= $user['id'] ?>" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nom d'utilisateur</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" 
                       class="w-full p-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                       class="w-full p-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Rôle</label>
                <select name="role_id" class="w-full p-2 border rounded">
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full p-2 border rounded">
                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Mettre à jour
            </button>
            <a href="index.php?action=adminDashboard" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2">
                Annuler
            </a>
        </form>
    </div>
</body>
</html>