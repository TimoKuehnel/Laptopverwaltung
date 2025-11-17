<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "geraetService.php";
require_once "geraet.php";

header('Content-Type: application/json');

try {
    $id = $_POST['id'] ?? null;
    $serviceTag = $_POST['serviceTag'] ?? null;
    $modellId = $_POST['modellId'] ?? null;
    $endeLeasing = $_POST['endeLeasing'] ?? null;

    if (!$serviceTag || !$modellId) {
        echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen!']);
        exit;
    }

    $endeLeasingObj = DateHelper::createDateOrNull($endeLeasing);

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new GeraetService($pdo);

    $existing = $service->getGeraetByServiceTag($serviceTag);
    if ($existing && $existing->getGeraeteId() !== (int) $id) {
        echo json_encode(['success' => false, 'message' => 'Service Tag bereits vorhanden!']);
        exit;
    }

    $geraet = new Geraet(
        $id ? (int) $id : null,
        $serviceTag,
        (int) $modellId,
        $endeLeasingObj
    );

    if ($id) {
        $success = $service->updateGeraet($geraet);

        echo json_encode([
            'success' => $success,
            'message' => 'Gerät erfolgreich aktualisiert!',
            'endeLeasingStr' => $geraet->getEndeLeasingAsString()
        ]);
    } else {
        $success = $service->insertGeraet($geraet);
        echo json_encode(['success' => $success, 'message' => 'Gerät erfolgreich hinzugefügt.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
?>