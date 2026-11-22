
<?php
$token = $controller->getCsrfToken();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

    <style>
       
body {
    font-family: Arial, sans-serif;
    background: #F4F7FA;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

.login-container,
.register-container {
    background: white;
    width: 350px;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(11, 31, 58, 0.08);
}

h2 {
    text-align: center;
    color: #0B1F3A;
    margin-bottom: 25px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

label {
    font-weight: bold;
    color: #0B1F3A;
}

input {
    padding: 11px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 14px;
    outline: none;
}

input:focus {
    border-color: #14B8A6;
    box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.15);
}

button {
    padding: 12px;
    background: #14B8A6;
    color: #0B1F3A;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    margin-top: 10px;
    transition: 0.3s;
}

button:hover {
    background: #84CC16;
}

.message {
    color: #dc2626;
    text-align: center;
}

.register-link,
.login-link {
    text-align: center;
    margin-top: 20px;
    color: #0B1F3A;
}

a {
    color: #14B8A6;
    font-weight: bold;
    text-decoration: none;
}

a:hover {
    color: #84CC16;
}

    </style>
</head>

<body>

<div class="register-container">

    <h2>Créer un compte</h2>

    <?php if (!empty($message)): ?>
        <p class="message">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="index.php?action=register" autocomplete="off">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($token) ?>">

        <label>Username :</label>
        <input type="text" name="username" autocomplete="off" required>

        <label>Email :</label>
        <input type="email" name="email" autocomplete="off" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" autocomplete="new-password" required>

        <label>Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" autocomplete="new-password" required>

        <button type="submit">S'inscrire</button>

    </form>

    <div class="login-link">
        Déjà un compte ?
        <a href="index.php?action=login">Se connecter</a>
    </div>

</div>

</body>
</html>
