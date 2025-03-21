<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Inscription</title>
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">

<div class="grid grid-cols-1 md:grid-cols-2 bg-white shadow-lg rounded-lg max-w-3xl w-full">
    <!-- Bloc Gauche : Image -->
    <div class="hidden md:block">
        <img class="w-full h-full object-cover rounded-l-lg" src="../../public/assets/img/6333204.jpg" alt="Illustration">
    </div>

    <!-- Bloc Droite : Formulaire -->
    <div class="flex flex-col justify-center px-6 py-12">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="text-center text-2xl font-bold text-gray-900">Créez votre compte</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <!-- Affichage des messages d'erreur -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Important: action doit pointer vers l'URL de base -->
            <form class="space-y-6" action="/APP_GESTION_DES_UTILISATEURS/public/index.php?action=register_process" method="POST">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-900">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" required class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900">Adresse email</label>
                    <input type="email" name="email" id="email" required class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900">Mot de passe</label>
                    <input type="password" name="password" id="password" required class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring-indigo-600 focus:border-indigo-600">
                    <p class="mt-1 text-sm text-gray-500">Minimum 6 caractères</p>
                </div>

                <div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-800 text-white font-semibold py-2 rounded-md transition duration-300 ease-in-out">S'inscrire</button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Vous avez déjà un compte ?
                <a href="/APP_GESTION_DES_UTILISATEURS/public/index.php?action=login" class="text-indigo-600 hover:text-indigo-500 font-semibold">Se connecter</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>