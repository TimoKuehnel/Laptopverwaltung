<?php
/*
 * PHP gibt normalerweise HTML aus, aber hier soll JSON an den JavaScript-AJAX-Request zurückgeliefert werden
 * Der HTTP-Header Content-Type "application/json" teilt dem Browser (oder jQuery) mit, dass er keine HTML-Seite, sondern JSON-Daten erwarten soll
 *  => Dadurch weiß jQuery später (dataType: 'json'), dass es die Antwort nicht als Text, sondern direkt als Objekt behandeln kann
 */
header('Content-Type: application/json');

// Einbinden externer PHP - Dateien (Nur ein einziges Mal!)
require_once "../authCheck.php";
require_once "../connection.php";
require_once "modell.php";
require_once "modellService.php";

try {

    // Erstellen einer Instanz der Klasse "Datenbank"
    $db = new Datenbank();
    // Aufruf der Methode "connect() => Verbindung zur MySQL Datenbank herstellen
    $pdo = $db->connect();
    // Erzeugen eines neuen ModellService Objekts und Übergabe des PDO - Objekts => Somit können SQL - Befehle ausgeführt werden
    $service = new ModellService($pdo);

    /* Pürfung, ob im Request - Parameter ($_GET) eine Aktion angegeben wurde
     * Im JavaScript - Code steht (loadModelle.js) "url: '../../includes/modell/modellUtility.php?action=list'"
     * Nur wenn diese Aktion (list) gesetzt ist, werden alle Modelle geladen
     */
    if (isset($_GET['action']) && $_GET['action'] === 'list') {
        // Aufruf der Methode "getAllModelle"
        $modelle = $service->getAllModelle();

        // Erstellt ein leeres Array, in das gleich die Daten für das JSON-Ergebnis geschrieben werden
        $result = [];

        foreach ($modelle as $m) {
            $result[] = [
                'id' => $m->getModellId(),
                'text' => $m->getModellbezeichnung()
            ];
        }
        // Wandelt das $result-Array in JSON um und gibt es an den Browser aus
        echo json_encode($result);
        // "exit" beendet das Skript sofort, damit nichts anderes mehr ausgeführt wird
        exit;
    }

    // Wenn action=list nicht gesetzt war, wird einfach ein leeres JSON-Array zurückgegeben
    echo json_encode([]);
} catch (Exception $e) {
    // Exception - Handling
    echo json_encode(['error' => $e->getMessage()]);
}

?>