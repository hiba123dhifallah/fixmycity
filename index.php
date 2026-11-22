
<?php

ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

session_start();

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline';");

require_once __DIR__ . '/Controller/UserController.php';

$controller = new UserController();

/* Vérification de l'expiration de la session */
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 60) {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

$action = $_GET['action'] ?? 'login';

switch ($action) {

    /* INSCRIPTION */
    case 'register':

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $controller->register();

            if ($message === '') {
                header('Location: index.php?action=login&registered=1');
                exit;
            }
        }

        require __DIR__ . '/View/FrontOffice/register.php';
        break;


    /* CONNEXION */
    case 'login':

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $controller->login();
        }

        require __DIR__ . '/View/FrontOffice/login.php';
        break;


    /* DÉCONNEXION */
    case 'logout':

        $controller->requireAuth();
        $controller->logout();
        break;


    /* PROFIL UTILISATEUR */
    case 'profile':

        $controller->requireRole('user');

        require __DIR__ . '/View/FrontOffice/profile.php';
        break;


    /* LISTE DES UTILISATEURS - ADMIN */
    case 'show':

        $controller->requireRole('admin');

        $users = $controller->getAllUsers();

        require __DIR__ . '/View/BackOffice/show.php';
        break;


    /* AJOUTER UN UTILISATEUR */
    case 'add':

        $controller->requireRole('admin');

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $controller->addUser();

            if ($message === '') {
                header('Location: index.php?action=show&added=1');
                exit;
            }
        }

        require __DIR__ . '/View/BackOffice/add.php';
        break;


    /* MODIFIER UN UTILISATEUR */
    case 'update':

        $controller->requireRole('admin');

        $message = '';

        $id = (int)($_GET['id'] ?? 0);

        $user = $controller->findById($id);

        if (!$user) {
            http_response_code(404);
            exit('Utilisateur introuvable.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $controller->updateUser();

            if ($message === '') {
                header('Location: index.php?action=show&updated=1');
                exit;
            }

            $user = $controller->findById($id);
        }

        require __DIR__ . '/View/BackOffice/update.php';
        break;


    /* SUPPRIMER UN UTILISATEUR */
    case 'delete':

        $controller->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        if (!$controller->verifyCsrfToken()) {
            http_response_code(403);
            exit('Token CSRF invalide.');
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0 && $id !== (int)$_SESSION['user_id']) {
            $controller->deleteUser($id);
        }

        header('Location: index.php?action=show&deleted=1');
        exit;


    /* VÉRIFICATION ADMIN */
    case 'verification':

        $controller->requireRole('admin');

        require __DIR__ . '/View/BackOffice/verification.php';
        break;


    /* REDIRECTION PAR DÉFAUT */
    default:

        if ($controller->isAuthenticated()) {

            if ($controller->hasRole('admin')) {
                header('Location: index.php?action=show');
            } else {
                header('Location: index.php?action=profile');
            }

        } else {
            header('Location: index.php?action=login');
        }

        exit;
}
