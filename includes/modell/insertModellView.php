<?php
require_once "../authCheck.php";
include "../header.php";
?>

<main>
    <?php
    echo "Willkommen, " . htmlspecialchars($auth->getUsername());
    ?>

    <form id="insertModellForm" class="" action="insertOrUpdateModell.php" method="post">
        <input type="hidden" id="edit_id">

        <div class="mb-3">
            <label for="insertModellbezeichnung" class="form-label">Modell - Bezeichnung</label>
            <input type="text" class="form-control" id="insertModellbezeichnung" name="modellbezeichnung" required>
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>

    <button onclick="window.location.href='modellDashboard.php';">Zurück zu den Modellen</button>
    <button onclick="window.location.href='../dashboard.php';">Zurück zum Dashboard</button>
    <button onclick="window.location.href='../logout.php';">Logout</button>

</main>

<?php
include "../footer.php";
?>