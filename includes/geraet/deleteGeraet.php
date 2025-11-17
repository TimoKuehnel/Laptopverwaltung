<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "geraetService.php";
require_once "geraet.php";

header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
    exit;
}

try {
    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new GeraetService($pdo);

    $id = (int)$_POST['id'];

    if ($service->deleteGeraet($id)) {
        echo json_encode(['success' => true, 'message' => 'Datensatz erfolgreich gelöscht!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Löschen fehlgeschlagen!']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
