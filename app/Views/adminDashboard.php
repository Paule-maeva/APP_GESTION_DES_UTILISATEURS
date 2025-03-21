<?php
// Assurez-vous que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez que l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: index.php?action=login");
    exit();
}

// Récupérez les données de l'administrateur (à adapter selon votre structure)
$adminName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="../../public/assets/js/style.js"></script>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <title>Dashboard Admin</title>
    <style>
        /* Animations et transitions */
        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        .sidebar-item:hover {
            background-color: #f3f4f6;
            transform: translateX(5px);
            color: #4f46e5;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #6366f1);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #4338ca, #4f46e5);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-danger {
            background: linear-gradient(to right, #ef4444, #f87171);
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: linear-gradient(to right, #dc2626, #ef4444);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background-color: #f3f4f6;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Personnalisation du scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4338ca;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

<header class="bg-white shadow-lg w-full fixed top-0 left-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
        <div class="flex items-center space-x-4">
            <div class="text-indigo-600 text-3xl">
                <i class="fas fa-users-cog"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Admin <span class="text-indigo-600">Panel</span></h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="hidden md:inline text-gray-600"><?php echo htmlspecialchars($adminName); ?></span>
            <div class="relative">
                <img src="https://via.placeholder.com/40" alt="Admin" class="rounded-full w-10 h-10 border-2 border-indigo-500">
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white"></div>
            </div>
            <a href="index.php?action=logout" class="text-red-600 hover:text-red-800 hover:underline flex items-center">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span class="hidden md:inline">Déconnexion</span>
            </a>
        </div>
    </div>
</header>

<div class="flex pt-14">
    <aside class="w-64 bg-white shadow-lg h-screen fixed left-0 top-14 p-6 z-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <span class="text-indigo-600">Menu</span>
        </h2>
        <nav class="mt-6 space-y-2">
            <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-700 bg-indigo-50 text-indigo-700 font-medium">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-700">
                <i class="fas fa-users mr-3"></i>
                Utilisateurs
            </a>
            <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-700">
                <i class="fas fa-chart-pie mr-3"></i>
                Statistiques
            </a>
            <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-700">
                <i class="fas fa-cog mr-3"></i>
                Paramètres
            </a>
            <div class="border-t border-gray-200 my-4"></div>
            <a href="index.php?action=logout" class="sidebar-item flex items-center px-4 py-3 text-red-600 hover:text-red-700">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Déconnexion
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-6 ml-64 mt-4 fade-in">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard <span class="text-indigo-600">Admin</span></h1>
            <div class="text-sm text-gray-500">
                <i class="far fa-calendar-alt mr-2"></i> <?php echo date('d M Y'); ?>
            </div>
        </div>

        <div class="flex justify-between items-center mt-6">
            <p class="text-gray-600">Bienvenue dans votre espace d'administration.</p>
            <button onclick="toggleModal(true)" class="btn-primary flex items-center text-white px-5 py-2 rounded-full shadow-lg hover:shadow-xl">
                <i class="fa fa-plus mr-2"></i> Ajouter un utilisateur
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card bg-white p-6 rounded-xl shadow-md border-t-4 border-indigo-500">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase">Utilisateurs</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">3</p>
                    </div>
                    <div class="rounded-full bg-indigo-100 p-3">
                        <i class="fa fa-users text-indigo-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-green-600 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 
                    <span>12% depuis le mois dernier</span>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-xl shadow-md border-t-4 border-green-500">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase">Ventes</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">1,250 €</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <i class="fa fa-euro-sign text-green-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-green-600 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 
                    <span>8% depuis le mois dernier</span>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-500">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase">Messages</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">5</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3">
                        <i class="fa fa-comments text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-yellow-600 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 
                    <span>3 nouveaux aujourd'hui</span>
                </div>
            </div>
            <div class="stat-card bg-white p-6 rounded-xl shadow-md border-t-4 border-purple-500">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase">Visites</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-1">245</p>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3">
                        <i class="fa fa-eye text-purple-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 text-sm text-purple-600 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 
                    <span>24% depuis hier</span>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 shadow-lg rounded-xl border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Liste des Utilisateurs</h2>
                <div class="relative">
                    <input type="text" placeholder="Rechercher..." class="px-4 py-2 pl-10 pr-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="usersTable">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">Rôle</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider">Dernière Connexion</th>
                            <th class="px-4 py-3 font-medium text-gray-600 text-sm uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersList">
                        <tr class="table-row border-b border-gray-200">
                            <td class="px-4 py-3">1</td>
                            <td class="px-4 py-3 flex items-center">
                                <img src="https://via.placeholder.com/40" alt="Jean Dupont" class="w-8 h-8 rounded-full mr-3">
                                Jean Dupont
                            </td>
                            <td class="px-4 py-3 text-gray-600">jean@example.com</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold">Admin</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">12 mars 2025</td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn-primary px-3 py-1 rounded-md text-white text-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-danger px-3 py-1 rounded-md text-white text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="table-row border-b border-gray-200">
                            <td class="px-4 py-3">2</td>
                            <td class="px-4 py-3 flex items-center">
                                <img src="https://via.placeholder.com/40" alt="Marie Martin" class="w-8 h-8 rounded-full mr-3">
                                Marie Martin
                            </td>
                            <td class="px-4 py-3 text-gray-600">marie@example.com</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Utilisateur</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    Actif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">10 mars 2025</td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn-primary px-3 py-1 rounded-md text-white text-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-danger px-3 py-1 rounded-md text-white text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="table-row">
                            <td class="px-4 py-3">3</td>
                            <td class="px-4 py-3 flex items-center">
                                <img src="https://via.placeholder.com/40" alt="Pierre Durand" class="w-8 h-8 rounded-full mr-3">
                                Pierre Durand
                            </td>
                            <td class="px-4 py-3 text-gray-600">pierre@example.com</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Utilisateur</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="flex items-center">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Inactif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">5 mars 2025</td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn-primary px-3 py-1 rounded-md text-white text-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-danger px-3 py-1 rounded-md text-white text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-600">Affichage de 1 à 3 sur 3 entrées</div>
                <div class="flex space-x-1">
                    <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 disabled:opacity-50" disabled>Précédent</button>
                    <button class="px-3 py-1 bg-indigo-500 text-white rounded-md">1</button>
                    <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 disabled:opacity-50" disabled>Suivant</button>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ajouter un utilisateur -->
<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all fade-in">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Ajouter un utilisateur</h2>
            <button onclick="toggleModal(false)" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form onsubmit="addUser(event)" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet :</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="userName" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="John Doe" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email :</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="userEmail" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="john@example.com" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle :</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-400"></i>
                    </div>
                    <select id="userRole" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 appearance-none">
                        <option value="2">Utilisateur</option>
                        <option value="1">Administrateur</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe :</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="userPassword" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="********" required>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="toggleModal(false)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-white">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('addUserModal');
        modal.classList.toggle('hidden', !show);
        
        // Empêcher le défilement du body quand la modal est ouverte
        document.body.style.overflow = show ? 'hidden' : 'auto';
    }

    function addUser(event) {
        event.preventDefault();
        
        // Récupérer les valeurs du formulaire
        const name = document.getElementById('userName').value;
        const email = document.getElementById('userEmail').value;
        const role = document.getElementById('userRole').value;
        const password = document.getElementById('userPassword').value;
        
        // Ici, vous pourriez ajouter le code pour envoyer ces données au serveur
        // Par exemple avec fetch() ou XMLHttpRequest
        
        // Fermer la modal
        toggleModal(false);
        
        // Rafraîchir la liste des utilisateurs (simulé ici)
        alert(`Utilisateur ajouté: ${name}, ${email}, Rôle: ${role === '1' ? 'Admin' : 'Utilisateur'}`);
    }
    
    // Ajout d'un écouteur d'événement pour fermer la modal quand on clique à l'extérieur
    document.getElementById('addUserModal').addEventListener('click', function(event) {
        if (event.target === this) {
            toggleModal(false);
        }
    });
</script>

</body>
</html>