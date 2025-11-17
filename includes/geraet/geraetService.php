<?php

require_once "geraet.php";

class GeraetService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle Geräte abrufen
    public function getAllGeraete(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM geraet");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allGeraete = [];

        foreach ($rows as $row) {
            $allGeraete[] = new Geraet(
                $row['geraeteId'],
                $row['serviceTag'],
                $row['modellId'],
                $row['endeLeasing'] ? new DateTime($row['endeLeasing']) : null
            );
        }
        return $allGeraete;
    }

    /* Ternärer Operator
     * Bedingung ? WertWennTrue : WertWennFalse
     * PHP prüft, ob der Wert $row['ausleihdatum'] nicht null oder leer ist
     * -> Wenn True (nicht leer oder null)  -> Erzeugen eines neues DateTime - Objekts
     * -> Wenn false (leer oder null)       -> Feld / Ergebnis auf null setzen
     */

    // Datensatz anhand der ID abrufen
    public function getGeraetById(int $geraeteId): ?Geraet
    {
        $stmt = $this->pdo->prepare("SELECT * FROM geraet WHERE geraeteId = :geraeteId");
        $stmt->execute(['geraeteId' => $geraeteId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Geraet(
                $row['geraeteId'],
                $row['serviceTag'],
                $row['modellId'],
                $row['endeLeasing'] ? new DateTime($row['endeLeasing']) : null
            );
        } else {
            return null;
        }
    }

    // Datensatz anhand des Service Tags abrufen
    public function getGeraetByServiceTag(string $serviceTag): ?Geraet
    {
        $stmt = $this->pdo->prepare("SELECT * FROM geraet WHERE serviceTag = :serviceTag");
        $stmt->execute(['serviceTag' => $serviceTag]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Geraet(
                $row['geraeteId'],
                $row['serviceTag'],
                $row['modellId'],
                $row['endeLeasing'] ? new DateTime($row['endeLeasing']) : null
            );
        } else {
            return null;
        }
    }

    // Neues Gerät einfügen
    public function insertGeraet(Geraet $geraet): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO geraet (serviceTag, modellId, endeLeasing) VALUES (:serviceTag, :modellId, :endeLeasing)");
        return $stmt->execute([
            'serviceTag'    => $geraet->getServiceTag(),
            'modellId'      => $geraet->getModellId(),
            'endeLeasing'   => $geraet->getEndeLeasingForDb()
        ]);
    }

    // Gerät aktualisieren
    public function updateGeraet(Geraet $geraet): bool
    {
        $stmt = $this->pdo->prepare("UPDATE geraet SET serviceTag = :serviceTag, modellId = :modellId, endeLeasing = :endeLeasing WHERE geraeteId = :geraeteId");
        return $stmt->execute([
            'serviceTag'    => $geraet->getServiceTag(),
            'modellId'      => $geraet->getModellId(),
            'endeLeasing'   => $geraet->getEndeLeasingForDb(),
            'geraeteId'     => $geraet->getGeraeteId()
        ]);
    }

    // Gerät löschen
    public function deleteGeraet(int $geraeteId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM geraet WHERE geraeteId = :geraeteId");
        return $stmt->execute(['geraeteId' => $geraeteId]);
    }
}
?>