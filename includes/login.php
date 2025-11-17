<?php
require_once "connection.php";
require_once "auth.php";

$db = new Datenbank();
$pdo = $db->connect();

$auth = new Auth($pdo);
$error = "";

if (isset($_GET['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        header("Location: dashboard.php");
        exit;
    }
    else {
        $error = $auth->getError();
    }
}

if (isset($_GET['error']) && $_GET['error'] === 'notloggedin') {
    $error = "Bitte zeurst einloggen!";
}

include "header.php";
?>

<main>
    <form action="?login=1" method="post">
        <p>Benutzername</p>
        <p><input type="text" name="username" size="30" maxlength="250"></p>

        <p>Passwort</p>
        <p><input type="password" name="password" size="30" maxlength="250"></p>

        <input type="submit" value="Login">
    </form>

    <?php
    if ($error) {
        echo '<div>' . htmlspecialchars($error) . '</div>';
    }
    ?>
</main>

<?php
include "footer.php";
?>