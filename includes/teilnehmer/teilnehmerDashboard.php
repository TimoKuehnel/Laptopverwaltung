<?php
require_once "../authCheck.php";
include "../header.php";

require_once "../connection.php";
require_once "teilnehmerService.php";
require_once "teilnehmer.php";
require_once "../kurs/kursService.php";


$db = new Datenbank();
$pdo = $db->connect();
$service = new teilnehmerService($pdo);
$serviceKurs = new KursService($pdo);
$teilnehmer = $service->getAllTeilnehmer();

?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <p>Hier können in Zukunft neue Teilnehmer hinzugefügt, geändert und gelöscht werden!</p>


    <table id="teilnehmerTable" class="dataTable display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Kursnummer</th>
                <th>Kuerzel</th>
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
                    <td data-id="<?= $t->getKursId() ?>">
                        <?= $serviceKurs->getKursnummerById($t->getKursId()) ?>
                    </td>
                    <td><?= $serviceKurs->getKuerzelById($t->getKursId()) ?></td>
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

    <button onclick="window.location.href='insertTeilnehmerView.php';">Neuen Teilnehmer hinzufügen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

    <div class="modal fade" id="editTeilnehmerModal" tabindex="-1" aria-labelledby="editTeilnehmerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTeilnehmerModalLabel">Teilnehmer bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormTeilnehmer">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label for="editVorname" class="form-label">Vorname</label>
                            <input type="text" class="form-control" id="editVorname" required>
                        </div>

                        <div class="mb-3">
                            <label for="editNachname" class="form-label">Nachname</label>
                            <input type="text" class="form-control" id="editNachname" required>
                        </div>

                        <div class="mb-3">
                            <label for="editKurs" class="form-label">Kurs</label>
                            <input type="text" class="form-control" id="editKurs" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteTeilnehmerModal" tabindex="-1" aria-labelledby="deleteTeilnehmerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteTeilnehmerModalLabel">Teilnehmer löschen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <p>Möchten Sie diesen Teilnehmer wirklich löschen?</p>
                    <p><strong id="deleteInfo"></strong></p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteTeilnehmer">Löschen</button>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include "../footer.php";
?>