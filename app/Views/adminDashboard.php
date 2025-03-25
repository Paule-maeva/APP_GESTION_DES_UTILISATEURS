<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-5 hidden md:block">
            <h2 class="text-2xl font-bold text-center mb-6">Admin Panel</h2>
            <ul>
                <li><a href="#dashboard" class="block py-2 px-4 hover:bg-gray-700 rounded">🏠 Tableau de Bord</a></li>
                <li><a href="#users" class="block py-2 px-4 hover:bg-gray-700 rounded">👥 Gestion Utilisateurs</a></li>
                <li><a href="#roles" class="block py-2 px-4 hover:bg-gray-700 rounded">🔐 Gestion des Rôles</a></li>
                <li><a href="#logs" class="block py-2 px-4 hover:bg-gray-700 rounded">📋 Logs de Connexion</a></li>
                <li><a href="#settings" class="block py-2 px-4 hover:bg-gray-700 rounded">⚙️ Paramètres</a></li>
                <li><a href="logout.php" class="block py-2 px-4 hover:bg-red-600 rounded">🚪 Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Tableau de Bord Administrateur</h1>
                <span class="text-lg font-semibold">Bonjour, Admin</span>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Total Utilisateurs</h3>
                    <p class="text-2xl font-bold text-blue-500">124</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Utilisateurs Actifs</h3>
                    <p class="text-2xl font-bold text-green-500">98</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Nouveaux cette semaine</h3>
                    <p class="text-2xl font-bold text-yellow-500">12</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <h3 class="text-lg">Rôles</h3>
                    <p class="text-2xl font-bold text-purple-500">4</p>
                </div>
            </div>

            <!-- Liste des Utilisateurs -->
            <div class="bg-white p-6 rounded-lg shadow-md overflow-auto">
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
                        <tr class="border-b">
                            <td class="p-3">1</td>
                            <td class="p-3">John Doe</td>
                            <td class="p-3">john@example.com</td>
                            <td class="p-3">Administrateur</td>
                            <td class="p-3 text-green-600">Actif</td>
                            <td class="p-3 flex gap-2">
                                <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded">Modifier</a>
                                <a href="#" class="px-3 py-1 bg-red-500 text-white rounded">Supprimer</a>
                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded">Désactiver</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
