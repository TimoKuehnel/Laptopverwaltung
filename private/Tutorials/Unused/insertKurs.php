<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "kursService.php";
require_once "kurs.php";

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceTag = $_POST['serviceTag'] ?? null;
    $modellId = $_POST['modellId'] ?? null;
    $endeLeasing = $_POST['endeLeasing'] ?? null;

    if (!$serviceTag || !$modellId) {
        echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen!']);
        exit;
    }

    $endeLeasingObj = DateHelper::createDateOrNull($endeLeasing);
    
    try {
        $db = new Datenbank();
        $pdo = $db->connect();
        $service = new GeraetService($pdo);

        $existing = $service->getGeraetByServiceTag($serviceTag);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Service Tag bereits vorhanden!']);
            exit;
        }

        $geraet = new Geraet(
            null,
            $serviceTag,
            (int) $modellId,
            $endeLeasingObj
        );

        $success = $service->insertGeraet($geraet);

        echo json_encode(['success' => true, 'message' => 'Gerät erfolgreich hinzugefügt.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
    }
}


?>