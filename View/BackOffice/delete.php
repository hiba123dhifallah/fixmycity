
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Accès refusé.');
}

?>

<p>La suppression d'un utilisateur doit être effectuée depuis la liste des utilisateurs.</p>

<a href="../../index.php?action=show">Retour à la liste</a>
