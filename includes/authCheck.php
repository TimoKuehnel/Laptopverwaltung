<?php
require_once "connection.php";
require_once "auth.php";
require_once "utility/config.php";

$db = new Datenbank();
$pdo = $db->connect();
$auth = new Auth($pdo);

if (!$auth->isLoggedIn()) {
    header("Location: " . BaseURL . "includes/login.php?error=notloggedin");
    exit;
}

?>