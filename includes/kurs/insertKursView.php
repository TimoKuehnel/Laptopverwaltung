<?php
require_once "../authCheck.php";
include "../header.php";
?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <form id="insertKursForm" class="" action="insertGeraet.php" method="post">
        <input type="hidden" id="edit_id">

        <div class="mb-3">
            <label for="insertKursnummer" class="form-label">Kursnummer</label>
            <input type="text" class="form-control" id="insertKursnummer" name="kursnummer" required>
        </div>

        <div class=" mb-3">
            <label for="insertKuerzel" class="form-label">Kürzel</label>
            <input type="text" class="form-control" id="insertKuerzel" name="kuerzel" required>
        </div>

        <div class=" mb-3">
            <label for="insertBeginn" class="form-label">Ausbildungsbeginn</label>
            <input type="date" class="form-control" id="insertBeginn" name="beginn">
        </div>

        <div class="mb-3">
            <label for="insertEnde" class="form-label">Ausbildungsende</label>
            <input type="date" class="form-control" id="insertEnde" name="ende">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

    <button onclick="window.location.href='kursDashboard.php';">Zurück zu den Kursen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

</main>

<?php
include "../footer.php";
?>