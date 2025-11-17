<?php
header('Content-Type: application/json');

require_once "../authCheck.php";
require_once "../connection.php";
require_once "modell.php";
require_once "modellService.php";

try {
    $db = new Datenbank();
    $pdo = $db->connect();
    $service = new ModellService($pdo);

    if (isset($_GET['action']) && $_GET['action'] === 'list') {

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search !== '') {
            $modelle = $service->searchModelle($search);
        } else {
            $modelle = $service->getAllModelle();
        }

        $result = [];

        foreach ($modelle as $m) {
            $result[] = [
                'id' => $m->getModellId(),
                'text' => $m->getModellbezeichnung()
            ];
        }

        echo json_encode($result);
        exit;
    }

    echo json_encode([]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
