
<?php

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?action=login');
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Accès refusé : réservé aux administrateurs.');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification</title>
</head>
<body>

<h2>Vérification des accès</h2>

<p>Utilisateur connecté :</p>

<p>
    <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p>Rôle :
    <?= htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') ?>
</p>

<p style="color:green;">Accès administrateur autorisé.</p>

<a href="../../index.php?action=show">Accéder à la gestion des utilisateurs</a>

</body>
</html>
