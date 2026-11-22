
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
</head>
<body>

<h2>Bienvenue dans votre espace utilisateur</h2>

<p>Username :
    <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p>Rôle : User</p>

<a href="index.php?action=logout">Déconnexion</a>

</body>
</html>
