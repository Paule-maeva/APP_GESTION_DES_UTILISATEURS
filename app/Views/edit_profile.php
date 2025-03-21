<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="../../public/assets/css/style.css">
</head>
<body>

<h1>Modifier mon Profil</h1>

<?php if (isset($_SESSION['success'])): ?>
    <p style="color:green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form action="index.php?action=update_profile" method="POST">
    <label>Nom :</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

    <label>Email :</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

    <label>Téléphone :</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

    <button type="submit">Mettre à jour</button>
</form>

<a href="index.php?action=userDashboard">Retour au tableau de bord</a>

</body>
</html>
