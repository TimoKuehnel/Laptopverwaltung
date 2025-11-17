<?php
require_once "modell.php";

class ModellService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle Modelle abrufen
    public function getAllModelle(): array
    {
        $sql = "SELECT * FROM modell ORDER BY modellbezeichnung";

        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allModelle = [];

        foreach ($rows as $row) {
            $allModelle[] = new Modell(
                $row['modellId'],
                $row['modellbezeichnung']
            );
        }
        return $allModelle;
    }

    // Modelle anhand eines Suchbegriffs abrufen
    public function searchModelle(string $search): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM modell
            WHERE modellbezeichnung LIKE :search
            ORDER BY modellbezeichnung
            LIMIT 50
        ");
        $stmt->execute(['search' => "%$search%"]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($rows as $row) {
            $result[] = new Modell(
                $row['modellId'],
                $row['modellbezeichnung']
            );
        }

        return $result;
    }
    
    // Modellbezeichnung anhand der ModellId abrufen
    public function getModellNameById(int $modellId): ?string
    {
        $stmt = $this->pdo->prepare("SELECT modellbezeichnung FROM modell WHERE modellId = :id");
        $stmt->execute(['id' => $modellId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['modellbezeichnung'] : null;
    }

    // Datensatz anhand der Modellbezeichnung abrufen
    public function getModellByModellbezeichnung(string $modellbezeichnung): ?Modell
    {
        $stmt = $this->pdo->prepare("SELECT * FROM modell WHERE modellbezeichnung = :modellbezeichnung");
        $stmt->execute(['modellbezeichnung' => $modellbezeichnung]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Modell(
                $row['modellId'],
                $row['modellbezeichnung']
            );
        } else {
            return null;
        }
    }

    // Neues Modell einfügen
    public function insertModell(Modell $modell): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO modell (modellbezeichnung");
        return $stmt->execute([
            'modellbezeichnung' => $modell->getModellbezeichnung()
        ]);
    }

    // Modell aktualisieren
    public function updateModell(Modell $modell): bool
    {
        $stmt = $this->pdo->prepare("UPDATE modell SET modellbezeichnung = :modellbezeichnung");
        return $stmt->execute([
            'modellbezeichnung' => $modell->getModellbezeichnung()
        ]);
    }

    // Modell löschen
    public function deleteModell(int $modellId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM modell WHERE modellId = :modellId");
        return $stmt->execute([
            'modellId' => $modellId
        ]);
    }
}

?>