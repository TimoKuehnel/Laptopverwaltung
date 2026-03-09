<?php
require_once "../connection.php";
require_once "teilnehmerService.php";

header('Content-Type: application/json');

try {
    $kurs = $_GET['kurs'] ?? null;

    if (!$kurs) {
        echo json_encode(['error' => 'Keine Klasse übergeben']);
        exit;
    }

    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new TeilnehmerService($pdo);

    $teilnehmer = $service->getTeilnehmerByKurs($kurs);

    echo json_encode(['success' => true, 'data' => $teilnehmer]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
