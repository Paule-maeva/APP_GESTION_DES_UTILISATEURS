<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
if ($_SESSION['role_id'] != 1) {
    header("Location: index.php?action=userDashboard");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Tableau de bord Administrateur</h1>
            <p>Connecté en tant que <?= htmlspecialchars($_SESSION['username']) ?> (<?= $_SESSION['role_name'] ?? 'Rôle non défini' ?>)</p>
            <a href="index.php?action=logout">Déconnexion</a>
        </header>

        <section>
            <h2>Liste des utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['role_name'] ?? 'N/A' ?></td>
                            <td><?= $user['status'] ?? 'N/A' ?></td>
                            <td>
                                <a href="index.php?action=editUser&id=<?= $user['id'] ?>">Modifier</a>
                                <a href="index.php?action=deleteUser&id=<?= $user['id'] ?>" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Aucun utilisateur trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>