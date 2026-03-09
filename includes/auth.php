<?php

class Auth
{

    private PDO $pdo;
    private $lastError;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $username, string $password): bool
    {
        $statement = $this->pdo->prepare("SELECT * FROM admin WHERE name = :username");
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();

        if (!$user) {
            $this->lastError = "Benutzer nicht gefunden!";
            return false;
        }
        if ($user && password_verify($password, $user['passwort'])) {
            session_regenerate_id(true);
            $_SESSION['Username'] = $user['name'];
            return true;
        } else {
            $this->lastError = "Passwort nicht korrekt!";
            return false;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public function isLoggedIn(): bool {
        return isset($_SESSION['Username']);
    }

    public function getUsername() : ?string {
        return $_SESSION['Username'] ?? null;
    }

    /* ?string = Diese Methode kann entweder einen string oder null zurückgeben
     *  -> Wenn der Benutzername gesetzt ist -> Rückgabewert ist ein String
     *  -> Wenn kein Benutzername gesetzt ist -> Rückgabewert ist null
     * 
     * ?? (Null-Coalescing-Operator) -> Kurzform für eine if - else Abfrage
     *  -> Wenn $_SESSION['Username'] existiert und nicht null ist, gib diesen Wert zurück.
     *  -> Sonst gib null zurück.
     *      -> ?? prüft, ob der linke Ausdruck existiert und nicht null ist
     *      -> Wenn nicht -> nimm den rechten Wert
     */

    public function getError(): ?string {
        return $this->lastError ?? null;
    }
}
?>