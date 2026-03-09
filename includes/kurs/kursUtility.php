<?php
require_once "../connection.php";
require_once "kursService.php";

header('Content-Type: application/json');

try {
    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new KursService($pdo);

    $search = $_GET['search'] ?? '';

    $klassen = ($search === '')
        ? $service->getAllKurse()
        : $service->searchKurse($search);

    $result = array_map(function ($k) {
        return [
            'id' => $k->getKursId(),
            'text' => $k->getKursnummer()
        ];
    }, $klassen);

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
