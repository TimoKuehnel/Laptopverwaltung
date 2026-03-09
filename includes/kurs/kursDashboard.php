<?php
require_once "../authCheck.php";
include "../header.php";

require_once "../connection.php";
require_once "kursService.php";
require_once "kurs.php";

$db = new Datenbank();
$pdo = $db->connect();
$service = new KursService($pdo);
$kurse = $service->getAllKurse();
?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <p>Hier können in Zukunft neue Kurse hinzugefügt, geändert und gelöscht werden!</p>


    <table id="kursTable" class="dataTable display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kursnummer</th>
                <th>Kürzel</th>
                <th>Ausbildungsbeginn</th>
                <th>Ausbildungsende</th>
                <th>Bearbeiten</th>
                <th>Löschen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kurse as $k): ?>
                <tr>
                    <td><?= $k->getKursId() ?></td>
                    <td><?= $k->getKursnummer() ?></td>
                    <td><?= $k->getKuerzel() ?></td>
                    <td><?= $k->getBeginnAsString() ?></td>
                    <td><?= $k->getEndeAsString() ?></td>
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

    <button onclick="window.location.href='insertKursView.php';">Neuen Kurs hinzufügen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

    <div class="modal fade" id="editKursModal" tabindex="-1" aria-labelledby="editKursModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Kurs bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormKurs">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label for="editKursnummer" class="form-label">Kursnummer</label>
                            <input type="text" class="form-control" id="editKursnummer" required>
                        </div>

                        <div class="mb-3">
                            <label for="editKuerzel" class="form-label">Kürzel</label>
                            <input type="text" class="form-control" id="editKuerzel" required>
                        </div>

                        <div class="mb-3">
                            <label for="editBeginn" class="form-label">Ausbildungsbeginn</label>
                            <input type="date" class="form-control" id="editBeginn">
                        </div>

                        <div class="mb-3">
                            <label for="editEnde" class="form-label">Ausbildungsende</label>
                            <input type="date" class="form-control" id="editEnde">
                        </div>

                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteKursModal" tabindex="-1" aria-labelledby="deleteKursModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteKursModalLabel">Kurs löschen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <p>Möchten Sie diesen Kurs wirklich löschen?</p>
                    <p><strong id="deleteInfo"></strong></p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteKurs">Löschen</button>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include "../footer.php";
?>