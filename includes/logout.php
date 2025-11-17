<?php
require_once "connection.php";
require_once "auth.php";

$db = new Datenbank();
$pdo = $db->connect();

$auth = new Auth($pdo);

$auth->logout();

header("Location: login.php");
exit;
?>