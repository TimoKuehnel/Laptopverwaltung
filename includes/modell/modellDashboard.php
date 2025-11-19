<?php
require_once "../authCheck.php";
include "../header.php";

require_once "../connection.php";
require_once "modellService.php";
require_once "modell.php";


$db = new Datenbank();
$pdo = $db->connect();
$service = new modellService($pdo);
$modelle = $service->getAllModelle();

?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <p>Hier können in Zukunft neue Laptops (Geräte) hinzugefügt, geändert und gelöscht werden!</p>


    <table id="modellTable" class="dataTable display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Modell - Bezeichnung</th>
                <th>Bearbeiten</th>
                <th>Löschen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modelle as $m): ?>
                <tr>
                    <td><?= $m->getModellId() ?></td>
                    <td><?= htmlspecialchars($m->getModellbezeichnung()) ?></td>
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

    <button onclick="window.location.href='insertModellView.php';">Neues Modell hinzufügen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

    <div class="modal fade" id="editModellModal" tabindex="-1" aria-labelledby="editModellModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModellModalLabel">Gerät bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <form id="editFormModell">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label for="editModellbezeichnung" class="form-label">Modell - Bezeichnung</label>
                            <input type="text" class="form-control" id="editModellbezeichnung" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModellModal" tabindex="-1" aria-labelledby="deleteModellModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModellModalLabel">Gerät löschen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <p>Möchten Sie dieses Gerät wirklich löschen?</p>
                    <p><strong id="deleteInfo"></strong></p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteModell">Löschen</button>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include "../footer.php";
?>