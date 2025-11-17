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
    <button onclick="window.location.href='logout.php';">Logout</button>
</main>

<?php
include "footer.php";
?>