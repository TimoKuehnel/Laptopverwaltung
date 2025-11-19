<?php
require_once "../authCheck.php";
include "../header.php";

require_once "../connection.php";
require_once "teilnehmerService.php";
require_once "teilnehmer.php";
require_once "../kurs/kursService.php";

$kursId = $_GET['kurs'] ?? null;

$db = new Datenbank();
$pdo = $db->connect();
$service = new teilnehmerService($pdo);
$teilnehmer = $service->getTeilnehmerByKurs($kursId);
$serviceKurs = new KursService($pdo);
$kurs = $serviceKurs->getKursById($kursId);
?>

<main class="container p-4">

    <h3>Teilnehmer der Klasse <?= $kurs->getKursnummer(); ?></h3>

    <table id="geraeteTable" class="dataTable display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Bearbeiten</th>
                <th>Löschen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teilnehmer as $t): ?>
                <tr>
                    <td><?= $t->getTeilnehmerId() ?></td>
                    <td><?= htmlspecialchars($t->getVorname()) ?></td>
                    <td><?= htmlspecialchars($t->getNachname()) ?></td>
                    <td>
                        <button class="editButton">Bearbeiten</button>
                    </td>
                    <td>
                        <button class="deleteButton">Löschen</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

</main>

<?php
include "../footer.php";
?>