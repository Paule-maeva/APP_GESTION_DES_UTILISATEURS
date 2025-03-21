<?php
// Assurez-vous que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// Récupérez les données de l'utilisateur (à adapter selon votre structure)
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utilisateur';
$userEmail = isset($_SESSION['email']) ? $_SESSION['email'] : 'email@example.com';
$userPhone = isset($_SESSION['phone']) ? $_SESSION['phone'] : 'Non spécifié';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script src="https://unpkg.com/framer-motion@10.12.1/dist/framer-motion.umd.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
    <title>Dashboard Utilisateur</title>
</head>
<body class="bg-gray-100">

<header class="bg-white shadow-md w-full fixed top-0 left-0 z-10 transition-all duration-300 ease-in-out hover:shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
        <h1 class="text-2xl font-bold text-gray-700 hover:text-blue-500 transition duration-300">Tableau de Bord</h1>
        <div class="flex items-center space-x-4">
            <img src="https://via.placeholder.com/40" alt="User" class="rounded-full w-10 h-10 border-2 border-gray-300 hover:border-blue-500 transition">
            <a href="index.php?action=logout" class="text-red-600 hover:underline transition duration-300">Déconnexion</a>
        </div>
    </div>
</header>

<div class="flex">
    <aside class="w-64 bg-white shadow-md h-screen fixed left-0 top-14 p-6 transition-transform duration-300 hover:scale-105">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">Menu</h2>
        <nav class="mt-6 space-y-2">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition">Mon Profil</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition">Activité Récente</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition">Paramètres</a>
        </nav>
    </aside>

    <main class="flex-1 p-6 ml-64 mt-14">
        <h1 class="text-3xl font-bold text-gray-800">Bienvenue, <span class="text-blue-500"><?php echo htmlspecialchars($userName); ?></span></h1>
        
        <!-- Profil Utilisateur -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition duration-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profil Utilisateur</h2>
            <div class="flex items-center space-x-6">
                <img src="https://via.placeholder.com/100" alt="User Photo" class="rounded-full w-24 h-24 border-4 border-gray-300 hover:border-blue-500 transition">
                <div>
                    <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($userName); ?></h3>
                    <p class="text-gray-600">Email : <?php echo htmlspecialchars($userEmail); ?></p>
                    <p class="text-gray-600">Téléphone : <?php echo htmlspecialchars($userPhone); ?></p>
                </div>
            </div>
        </div>

        <!-- Cards -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md transition transform hover:scale-105">
                <h3 class="text-lg font-semibold text-gray-700">Projets Suivis</h3>
                <p class="text-2xl font-bold text-gray-800">5</p>
                <i class="fa fa-project-diagram text-blue-500 text-3xl mt-4 hover:rotate-12 transition duration-300"></i>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transition transform hover:scale-105">
                <h3 class="text-lg font-semibold text-gray-700">Totaux</h3>
                <p class="text-2xl font-bold text-gray-800">2,500 €</p>
                <i class="fa fa-dollar-sign text-green-500 text-3xl mt-4 hover:rotate-12 transition duration-300"></i>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transition transform hover:scale-105">
                <h3 class="text-lg font-semibold text-gray-700">Messages Non Lus</h3>
                <p class="text-2xl font-bold text-gray-800">3</p>
                <i class="fa fa-envelope text-yellow-500 text-3xl mt-4 hover:rotate-12 transition duration-300"></i>
            </div>
        </div>

        <!-- Activité Récente -->
        <div class="mt-8 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Activité Récente</h2>
            <ul class="space-y-2">
                <li class="border-b border-gray-200 py-2 hover:bg-gray-100 transition">
                    <p class="text-gray-600">Vous avez ajouté un nouveau projet : "Investissement A" (01 mars 2025)</p>
                </li>
                <li class="border-b border-gray-200 py-2 hover:bg-gray-100 transition">
                    <p class="text-gray-600">Votre demande d'investissement a été approuvée pour "Projet B" (25 février 2025)</p>
                </li>
                <li class="border-b border-gray-200 py-2 hover:bg-gray-100 transition">
                    <p class="text-gray-600">Vous avez reçu un message de l'administrateur concernant votre projet (20 février 2025)</p>
                </li>
            </ul>
        </div>
    </main>
</div>

</body>
</html>
