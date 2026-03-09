<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "kursService.php";
require_once "kurs.php";

header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
    exit;
}

try {
    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new KursService($pdo);

    $id = (int)$_POST['id'];

    if ($service->deleteKurs($id)) {
        echo json_encode(['success' => true, 'message' => 'Datensatz erfolgreich gelöscht!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Löschen fehlgeschlagen!']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
