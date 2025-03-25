<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <title>Connexion</title>
    <script>
        // Fonction pour basculer la visibilité du mot de passe
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const passwordToggle = document.getElementById("password-toggle");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggle.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                passwordToggle.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</head>
<body class="h-screen flex items-center justify-center bg-gray-100">

<div class="grid grid-cols-1 md:grid-cols-2 bg-white shadow-lg rounded-lg max-w-3xl w-full">
    <!-- Bloc Gauche : Image -->
    <div class="hidden md:block">
    <img class="w-full h-full object-cover rounded-l-lg" src="/App_Gestion_des_Utilisateurs/public/assets/img/6333204.jpg" alt="illustration">
    </div>

    <!-- Bloc Droite : Formulaire -->
    <div class="flex flex-col justify-center px-6 py-12">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="text-center text-2xl font-bold text-gray-900">Connectez-vous à votre compte</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <!-- Affichage des messages d'erreur -->
            <?php if(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Affichage des messages de succès -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire de connexion -->
            <form class="space-y-6" action="index.php?action=login_process" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900">Adresse email</label>
                    <input type="email" name="email" id="email" required class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring-indigo-600 focus:border-indigo-600">
                </div>

                <div class="relative">
                    <div class="flex justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-900">Mot de passe</label>
                    </div>
                    <input type="password" name="password" id="password" required class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 focus:ring-indigo-600 focus:border-indigo-600">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-2/3 transform -translate-y-1/2 text-gray-500">
                        <i id="password-toggle" class="fa fa-eye"></i>
                    </button>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember_me" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Se rappeler de moi</label>
                </div>

                <div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-800 text-white font-semibold py-2 rounded-md transition duration-300 ease-in-out">Se connecter</button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Vous n'avez pas encore de compte ? 
                <a href="index.php?action=register" class="text-indigo-600 hover:text-indigo-500 font-semibold">S'inscrire</a>

            </p>
        </div>
    </div>
</div>

</body>
</html>
