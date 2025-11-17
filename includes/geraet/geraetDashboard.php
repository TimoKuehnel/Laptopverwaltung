<?php
require_once "../authCheck.php";
include "../header.php";

require_once "../connection.php";
require_once "geraetService.php";
require_once "geraet.php";
require_once "../modell/modellService.php";


$db = new Datenbank();
$pdo = $db->connect();
$service = new GeraetService($pdo);
$serviceModell = new ModellService($pdo);
$geraete = $service->getAllGeraete();

?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <p>Hier können in Zukunft neue Laptops (Geräte) hinzugefügt, geändert und gelöscht werden!</p>


    <table id="geraeteTable" class="dataTable display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Tag</th>
                <th>Modell - Bezeichnung</th>
                <th>Ende Leasing</th>
                <th>Bearbeiten</th>
                <th>Löschen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($geraete as $g): ?>
                <tr>
                    <td><?= $g->getGeraeteId() ?></td>
                    <td><?= htmlspecialchars($g->getServiceTag()) ?></td>
                    <td data-id="<?= $g->getModellId() ?>">
                        <?= htmlspecialchars($serviceModell->getModellNameById($g->getModellId())) ?>
                    </td>
                    <td><?= $g->getEndeLeasingAsString() ?></td>
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

    <button onclick="window.location.href='insertGeraetView.php';">Neues Gerät hinzufügen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

    <div class="modal fade" id="editGeraetModal" tabindex="-1" aria-labelledby="editGeraetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGeraetModalLabel">Gerät bearbeiten</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
                </div>
                <div class="modal-body">
                    <form id="editGeraetForm">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label for="editServiceTag" class="form-label">Service Tag</label>
                            <input type="text" class="form-control" id="editServiceTag" required>
                        </div>

                        <div class="mb-3">
                            <label for="editModellId" class="form-label">Modell - Bezeichnung</label>
                            <select class="form-control" id="editModellId" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="editEndeLeasing" class="form-label">Ende Leasing</label>
                            <input type="date" class="form-control" id="editEndeLeasing">
                        </div>

                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteGeraetModal" tabindex="-1" aria-labelledby="deleteGeraetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteGeraetModalLabel">Gerät löschen</h5>
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
                    <button type="button" class="btn btn-danger" id="confirmDeleteGeraet">Löschen</button>
                </div>
            </div>
        </div>
    </div>


</main>

<?php
include "../footer.php";
?>