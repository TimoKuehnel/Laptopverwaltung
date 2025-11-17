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

    if (!$kursnummer || !$kuerzel) {
        echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen!']);
        exit;
    }

    $beginnObj = DateHelper::createDateOrNull($beginn);
    $endeObj = DateHelper::createDateOrNull($ende);

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new KursService($pdo);

    // Prüfen auf doppelten Kursnummer
    $existing = $service->getKursByKursnummer($kursnummer);
    if ($existing && (!$id || $existing->getKursId() !== (int)$id)) {
        echo json_encode(['success' => false, 'message' => 'Kursnummer bereits vorhanden!']);
        exit;
    }

    $kurs = new Kurs(
        $id ? (int) $id : null,
        $kursnummer,
        $kuerzel,
        $beginnObj,
        $endeObj
    );

    if ($id) {
        // Update
        $success = $service->updateKurs($kurs);
        echo json_encode([
            'success' => $success,
            'message' => 'Kurs erfolgreich aktualisiert!',
            'beginnStr' => $kurs->getBeginnAsString(),
            'endeStr'   => $kurs->getEndeAsString()
        ]);
    } else {
        // Insert
        $success = $service->insertKurs($kurs);
        echo json_encode(['success' => true, 'message' => 'Kurs erfolgreich hinzugefügt.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
}
