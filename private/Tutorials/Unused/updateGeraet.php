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

    if (!$id || !$serviceTag || !$modellId) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Eingaben!']);
        exit;
    }

    $endeLeasingObj = DateHelper::createDateOrNull($endeLeasing);

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new GeraetService($pdo);

    $existing = $service->getGeraetByServiceTag($serviceTag);

    if ($existing && $existing->getGeraeteId() !== (int)$id) {
        echo json_encode(['success' => false, 'message' => 'Service Tag bereits vorhanden!']);
        exit;
    }


    $geraet = new Geraet(
        (int) $id,
        $serviceTag,
        (int) $modellId,
        $endeLeasingObj
    );

    $success = $service->updateGeraet($geraet);

        echo json_encode([
        'success' => $success,
        'message' => 'Datensatz erfolgreich aktualisiert!',
        'endeLeasingStr' => $geraet->getEndeLeasingAsString()
    ]);

    //echo json_encode(['success' => $success, 'message' => 'Datensatz erfolgreich akualisiert!']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>