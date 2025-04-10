<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-purple-600 text-white p-5 hidden md:block">
            <h2 class="text-2xl font-bold text-center mb-6">Admin Panel</h2>
            <ul>
                <li><a href="?action=adminDashboard" class="block py-2 px-4 hover:bg-gray-700 rounded"><i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord</a></li>
                <li><a href="?action=userManagement" class="block py-2 px-4 bg-gray-700 rounded"><i class="fas fa-users mr-2"></i> Gestion Utilisateurs</a></li>
                <li><a href="?action=roleManagement" class="block py-2 px-4 hover:bg-gray-700 rounded"><i class="fas fa-user-shield mr-2"></i> Rôles</a></li>
                <li><a href="?action=logs" class="block py-2 px-4 hover:bg-gray-700 rounded"><i class="fas fa-clipboard-list mr-2"></i> Logs</a></li>
                <li><a href="?action=settings" class="block py-2 px-4 hover:bg-gray-700 rounded"><i class="fas fa-cog mr-2"></i> Paramètres</a></li>
                <li><a href="?action=logout" class="block py-2 px-4 hover:bg-red-600 rounded mt-4"><i class="fas fa-sign-out-alt mr-2"></i> Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Gestion des Utilisateurs</h1>
                <div>
                    <span class="text-lg font-semibold mr-4">Bonjour, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Total Utilisateurs <?php action?></h3>
                    <p class="text-2xl font-bold text-blue-500"> </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Utilisateurs Actifs</h3>
                    <p class="text-2xl font-bold text-green-500"> </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Administrateurs</h3>
                    <p class="text-2xl font-bold text-purple-500"> </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Connexions (7j)</h3>
                    <p class="text-2xl font-bold text-yellow-500"> </p>
                </div>
            </div>
            <a href="?action=addUser" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-user-plus mr-2"></i>Ajouter un utilisateur
                    </a>
            <!-- Liste des Utilisateurs -->
            <div class="bg-white p-6 rounded-lg shadow-md overflow-auto">
                
                <div class="flex justify-between items-center mb-4">
                    
                    <h2 class="text-xl font-semibold">Liste des Utilisateurs</h2>
                    <div class="flex items-center">
                        <input type="text" placeholder="Rechercher..." class="border rounded px-3 py-1 mr-2">
                        <select class="border rounded px-3 py-1">
                            <option>Tous les rôles</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Nom</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Rôle</th>
                            <th class="p-3 text-left">Statut</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3"><?= htmlspecialchars($user['id']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($user['role_name']) ?></td>
                            <td class="p-3">
                                <span class="<?= $user['status'] === 'active' ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $user['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-2">
                                    <a href="?action=editUser&id=<?= $user['id'] ?>" 
                                       class="text-blue-500 hover:text-blue-700" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=toggleUserStatus&id=<?= $user['id'] ?>" 
                                       class="<?= $user['status'] === 'active' ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' ?>" 
                                       title="<?= $user['status'] === 'active' ? 'Désactiver' : 'Activer' ?>">
                                        <i class="fas <?= $user['status'] === 'active' ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                    </a>
                                    <?php if ($user['id'] != 1 && $user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                                    <a href="?action=deleteUser&id=<?= $user['id'] ?>" 
                                       class="text-red-500 hover:text-red-700 confirm-delete" 
                                       title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="mt-4 flex justify-center">
                    <nav class="inline-flex rounded-md shadow">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?action=adminDashboard&page=<?= $i ?>" 
                               class="<?= $i == $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500 hover:bg-gray-50' ?> 
                                      px-4 py-2 border border-gray-300">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </nav>
                </div>
                <?php endif; ?>
            </div>

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

        </div>
        
    </div>
 
    <!-- Confirmation de suppression -->
    <script>
        document.querySelectorAll('.confirm-delete').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>