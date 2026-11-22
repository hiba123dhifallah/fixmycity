
<?php
$token = $controller->getCsrfToken();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
</head>
<body>

<h2>Gestion des utilisateurs</h2>

<p>
    Bienvenue Admin :
    <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>
</p>

<?php if (isset($_GET['added'])): ?>
    <p style="color:green;">Utilisateur ajouté avec succès.</p>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <p style="color:green;">Utilisateur modifié avec succès.</p>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <p style="color:green;">Utilisateur supprimé avec succès.</p>
<?php endif; ?>

<a href="index.php?action=add">Ajouter un utilisateur</a>
<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= (int)$user['id'] ?></td>

                <td>
                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                </td>

                <td>
                    <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                </td>

                <td>
                    <?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>
                </td>

                <td>
                    <?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?>
                </td>

                <td>
                    <a href="index.php?action=update&id=<?= (int)$user['id'] ?>">
                        Modifier
                    </a>

                    <?php if ((int)$user['id'] !== (int)$_SESSION['user_id']): ?>
                        <form method="POST" action="index.php?action=delete"
                              onsubmit="return confirm('Voulez-vous supprimer cet utilisateur ?');">

                            <input type="hidden" name="csrf_token"
                                   value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

                            <input type="hidden" name="id"
                                   value="<?= (int)$user['id'] ?>">

                            <button type="submit">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br>
<a href="index.php?action=logout">Déconnexion</a>

</body>
</html>
