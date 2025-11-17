<?php
require_once "../authCheck.php";
require_once "../connection.php";
require_once "kursService.php";
require_once "kurs.php";

header('Content-Type: application/json');

try {
    $id = $_POST['id'] ?? null;
    $kursnummer = $_POST['kursnummer'] ?? null;
    $kuerzel = $_POST['kuerzel'] ?? null;
    $beginn = $_POST['beginn'] ?? null;
    $ende = $_POST['ende'] ?? null;

    if (!$id || !$kursnummer || !$kuerzel) {
        echo json_encode(['success' => false, 'message' => 'Ungültige Eingaben!']);
        exit;
    }

    $beginnObj = DateHelper::createDateOrNull($beginn);
    $endeObj = DateHelper::createDateOrNull($ende);

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new KursService($pdo);

    $existing = $service->getKursByKursnummer($kursnummer);

    if ($existing && $existing->getKursId() !== (int)$id) {
        echo json_encode(['success' => false, 'message' => 'Kurs bereits vorhanden!']);
        exit;
    }


    $kurs = new Kurs(
        (int) $id,
        $kursnummer,
        $kuerzel,
        $beginnObj,
        $endeObj
    );

    $success = $service->updateKurs($kurs);

        echo json_encode([
        'success'   => $success,
        'message'   => 'Datensatz erfolgreich aktualisiert!',
        'beginnStr' => $kurs->getBeginnAsString(),
        'endeStr'   => $kurs->getEndeAsString()
    ]);

    //echo json_encode(['success' => $success, 'message' => 'Datensatz erfolgreich akualisiert!']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>