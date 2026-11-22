
<?php
$token = $controller->getCsrfToken();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
</head>
<body>

<h2>Modifier un utilisateur</h2>

<?php if (!empty($message)): ?>
    <p style="color:red;">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="POST"
      action="index.php?action=update&id=<?= (int)$user['id'] ?>">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">

    <label>Username :</label>
    <input type="text" name="username"
           value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>"
           required>
    <br><br>

    <label>Email :</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"
           required>
    <br><br>

    <label>Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
    <input type="password" name="password">
    <br><br>

    <label>Rôle :</label>
    <select name="role">
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>
            User
        </option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
            Admin
        </option>
    </select>
    <br><br>

    <button type="submit">Enregistrer les modifications</button>

</form>

<p><a href="index.php?action=show">Retour à la liste</a></p>

</body>
</html>
