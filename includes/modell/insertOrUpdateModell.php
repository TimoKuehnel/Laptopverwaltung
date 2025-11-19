<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "modellService.php";
require_once "modell.php";

header('Content-Type: application/json');

try {
    $id = $_POST['id'] ?? null;
    $modellbezeichnung = $_POST['modellbezeichnung'] ?? null;

    if (!$modellbezeichnung) {
        echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen!']);
        exit;
    }

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new ModellService($pdo);

    $existing = $service->getModellByModellbezeichnung($modellbezeichnung);
    if ($existing && $existing->getModellId() !== (int) $id) {
        echo json_encode(['success' => false, 'message' => 'Modellbezeichnung bereits vorhanden!']);
        exit;
    }

    $modell = new Modell(
        $id ? (int) $id : null,
        $modellbezeichnung
    );

    if ($id) {
        $success = $service->updateModell($modell);

        echo json_encode([
            'success' => $success,
            'message' => 'Modell erfolgreich aktualisiert!'
        ]);
    } else {
        $success = $service->insertModell($modell);
        echo json_encode(['success' => $success, 'message' => 'Modell erfolgreich hinzugefügt.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
?>