<?php
require_once "../authCheck.php";
include "../header.php";
?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <form id="insertGeraetForm" class="" action="insertGeraet.php" method="post">
        <input type="hidden" id="edit_id">

        <div class="mb-3">
            <label for="insertServiceTag" class="form-label">Service Tag</label>
            <input type="text" class="form-control" id="insertServiceTag" name="serviceTag" required>
        </div>

        <div class="mb-3">
            <label for="insertModellId" class="form-label">Modell - Bezeichnung</label>
            <select class="form-control" id="insertModellId" name="modellId" required></select>
        </div>

        <div class="mb-3">
            <label for="insertEndeLeasing" class="form-label">Ende Leasing</label>
            <input type="date" class="form-control" id="insertEndeLeasing" name="endeLeasing">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

    <button onclick="window.location.href='geraetDashboard.php';">Zurück zu den Geräten</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

</main>

<?php
include "../footer.php";
?>