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
    <button onclick="window.location.href='modell/modellDashboard.php';">Modelle verwalten</button>
    <button data-bs-toggle="modal" data-bs-target="#kursModal">Teilnehmer nach Kurs anzeigen</button>
    <button onclick="window.location.href='logout.php';">Logout</button>

    <div class="modal fade" id="kursModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Kurs auswählen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label for="kursSelect" class="form-label">Kurs</label>
                    <select id="kursSelect" class="form-control"></select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <button id="kursWeiterBtn" class="btn btn-primary">Öffnen</button>
                </div>

            </div>
        </div>
    </div>



</main>

<?php
include "footer.php";
?>