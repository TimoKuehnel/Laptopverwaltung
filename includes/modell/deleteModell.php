<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "modellService.php";
require_once "modell.php";
require_once "../geraet/geraet.php";
require_once "../geraet/geraetService.php";

header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
    exit;
}

try {
    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new ModellService($pdo);
    $serviceGeraet = new GeraetService($pdo);
;
    $id = (int)$_POST['id'];

    

    if ($serviceGeraet->getGeraetByModellId($id)) {
        echo json_encode(['success' => false, 'message' => 'Modell kann nicht gelöscht werden, da noch Geräte dieses Modells vorhanden sind!']);
        exit;
    }

    if ($service->deleteModell($id)) {
        echo json_encode(['success' => true, 'message' => 'Datensatz erfolgreich gelöscht!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Löschen fehlgeschlagen!']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
