
<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Model/User.php';

class UserController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnexion();
    }

    public function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function verifyCsrfToken(): bool
    {
        return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    public function isValidUsername(string $username): bool
    {
        $length = function_exists('mb_strlen')
            ? mb_strlen($username, 'UTF-8')
            : strlen($username);

        return $length >= 3 && $length <= 50;
    }

    public function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function hasRole(string $role): bool
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    public function requireRole(string $role): void
    {
        $this->requireAuth();

        if (!$this->hasRole($role)) {
            http_response_code(403);
            exit('Accès refusé.');
        }
    }

    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->execute(['email' => $email]);

        return $query->fetch();
    }

    public function findById(int $id): array|false
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);

        return $query->fetch();
    }

    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM utilisateurs WHERE username = :username";
        $params = ['username' => $username];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return (bool)$query->fetch();
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM utilisateurs WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return (bool)$query->fetch();
    }

    public function register(): string
    {
        if (!$this->verifyCsrfToken()) {
            return "Requête invalide.";
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($username === '' || $email === '' || $password === '' || $confirm === '') {
            return "Tous les champs sont obligatoires.";
        }

        if (!$this->isValidUsername($username)) {
            return "Le username doit contenir entre 3 et 50 caractères.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Adresse email invalide.";
        }

        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if ($password !== $confirm) {
            return "Les mots de passe ne correspondent pas.";
        }

        if ($this->usernameExists($username)) {
            return "Ce username existe déjà.";
        }

        if ($this->emailExists($email)) {
            return "Cet email existe déjà.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateurs (username, email, password, role)
                VALUES (:username, :email, :password, 'user')";

        $query = $this->db->prepare($sql);

        $query->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        return "";
    }

    public function login(): string
    {
        if (!$this->verifyCsrfToken()) {
            return "Requête invalide.";
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            return "Tous les champs sont obligatoires.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Adresse email invalide.";
        }

        $user = $this->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return "Email ou mot de passe incorrect.";
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();

        if ($user['role'] === 'admin') {
            header('Location: index.php?action=show');
        } else {
            header('Location: index.php?action=profile');
        }

        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: index.php?action=login');
        exit;
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT id, username, email, role, created_at
                FROM utilisateurs ORDER BY id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function addUser(): string
    {
        if (!$this->verifyCsrfToken()) {
            return "Requête invalide.";
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($username === '' || $email === '' || $password === '') {
            return "Tous les champs sont obligatoires.";
        }

        if (!$this->isValidUsername($username)) {
            return "Le username doit contenir entre 3 et 50 caractères.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email invalide.";
        }

        if (strlen($password) < 8) {
            return "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if (!in_array($role, ['user', 'admin'], true)) {
            return "Rôle invalide.";
        }

        if ($this->usernameExists($username) || $this->emailExists($email)) {
            return "Username ou email déjà utilisé.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO utilisateurs (username, email, password, role)
                VALUES (:username, :email, :password, :role)";

        $query = $this->db->prepare($sql);

        $query->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);

        return "";
    }

    public function updateUser(): string
    {
        if (!$this->verifyCsrfToken()) {
            return "Requête invalide.";
        }

        $id = (int)($_POST['id'] ?? 0);
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $password = $_POST['password'] ?? '';

        if ($id <= 0 || $username === '' || $email === '') {
            return "Informations invalides.";
        }

        if (!$this->isValidUsername($username)) {
            return "Le username doit contenir entre 3 et 50 caractères.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email invalide.";
        }

        if (!in_array($role, ['user', 'admin'], true)) {
            return "Rôle invalide.";
        }

        if ($this->usernameExists($username, $id) || $this->emailExists($email, $id)) {
            return "Username ou email déjà utilisé.";
        }

        if ($password !== '') {
            if (strlen($password) < 8) {
                return "Le mot de passe doit contenir au moins 8 caractères.";
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE utilisateurs
                    SET username = :username, email = :email,
                        password = :password, role = :role
                    WHERE id = :id";

            $params = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role,
                'id' => $id
            ];
        } else {
            $sql = "UPDATE utilisateurs
                    SET username = :username, email = :email,
                        role = :role
                    WHERE id = :id";

            $params = [
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'id' => $id
            ];
        }

        $query = $this->db->prepare($sql);
        $query->execute($params);

        return "";
    }

    public function deleteUser(int $id): void
    {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
    }

    public function countByRole(string $role): int
    {
        $query = $this->db->prepare(
            "SELECT COUNT(*) FROM utilisateurs WHERE role = :role"
        );

        $query->execute(['role' => $role]);

        return (int)$query->fetchColumn();
    }

    public function countAllUsers(): int
    {
        return (int)$this->db
            ->query("SELECT COUNT(*) FROM utilisateurs")
            ->fetchColumn();
    }
}
