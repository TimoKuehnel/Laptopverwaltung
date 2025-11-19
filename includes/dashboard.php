<?php
require_once "connection.php";
require_once "authCheck.php";
include "header.php";
?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <button onclick="window.location.href='teilnehmer/teilnehmer.php';">Teilnehmer verwalten</button>
    <button onclick="window.location.href='geraet/geraetDashboard.php';">Laptops verwalten</button>
    <button onclick="window.location.href='kurs/kursDashboard.php';">Kurse verwalten</button>
    <button onclick="window.location.href='modell/modell.php';">Modelle verwalten</button>
    <button data-bs-toggle="modal" data-bs-target="#klasseModal">Teilnehmer nach Klasse anzeigen</button>
    <button onclick="window.location.href='logout.php';">Logout</button>

    <div class="modal fade" id="klasseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Klasse auswählen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label for="klasseSelect" class="form-label">Klasse</label>
                    <select id="klasseSelect" class="form-control"></select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button id="klasseWeiterBtn" class="btn btn-primary">Öffnen</button>
                </div>

            </div>
        </div>
    </div>



</main>

<?php
include "footer.php";
?>