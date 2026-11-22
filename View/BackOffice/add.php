
<?php
$token = $controller->getCsrfToken();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter utilisateur</title>
</head>
<body>

<h2>Ajouter un utilisateur</h2>

<?php if (!empty($message)): ?>
    <p style="color:red;">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="POST" action="index.php?action=add">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

    <label>Username :</label>
    <input type="text" name="username" required>
    <br><br>

    <label>Email :</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Mot de passe :</label>
    <input type="password" name="password" required>
    <br><br>

    <label>Rôle :</label>
    <select name="role" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <br><br>

    <button type="submit">Ajouter</button>

</form>

<p><a href="index.php?action=show">Retour à la liste</a></p>

</body>
</html>
