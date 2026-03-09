<?php

require_once "teilnehmer.php";

class teilnehmerService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle Teilnehmer abrufen
    public function getAllTeilnehmer(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM teilnehmer");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allTeilnehmer = [];

        foreach ($rows as $row) {
            $allTeilnehmer[] = new Teilnehmer(
                $row['teilnehmerId'],
                $row['vorname'],
                $row['nachname'],
                $row['kursId']
            );
        }
        return $allTeilnehmer;
    }

    // Datensatz anhand der ID abrufen
    public function getTeilnehmerById(int $teilnehmerId): ?Teilnehmer
    {
        $stmt = $this->pdo->prepare("SELECT * FROM teilnehmer WHERE teilnehmerId = :teilnehmerId");
        $stmt->execute(['teilnehmerId' => $teilnehmerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Teilnehmer(
                $row['teilnehmerId'],
                $row['vorname'],
                $row['nachname'],
                $row['kursId']
            );
        } else {
            return null;
        }
    }

    // Datensätze anhand des Kurses abrufen
        public function getTeilnehmerByKurs(int $kursId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM teilnehmer WHERE kursId  = :kursId");
        $stmt->execute(['kursId' => $kursId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allTeilnehmer = [];

        foreach ($rows as $row) {
            $allTeilnehmer[] = new Teilnehmer(
                $row['teilnehmerId'],
                $row['vorname'],
                $row['nachname'],
                $row['kursId']
            );
        }
        return $allTeilnehmer;
    }

    // Neuen Teilnehmer einfügen
    public function insertTeilnehmer(Teilnehmer $teilnehmer): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO teilnehmer (vorname, nachname, kursId) VALUES (:vorname, :nachname, :kursId)");
        return $stmt->execute([
            'vorname'   => $teilnehmer->getVorname(),
            'nachname'  => $teilnehmer->getNachname(),
            'kursId'    => $teilnehmer->getKursId()
        ]);
    }

    // Teilnehmer aktualisieren
    public function updateTeilnehmer(Teilnehmer $teilnehmer): bool
    {
        $stmt = $this->pdo->prepare("UPDATE teilnehmer SET vorname = :vorname, nachname = :nachname, kursId = :kursId WHERE teilnehmerId = :teilnehmerId");
        return $stmt->execute([
            'vorname'       => $teilnehmer->getVorname(),
            'nachname'      => $teilnehmer->getNachname(),
            'kursId'        => $teilnehmer->getKursId(),
            'teilnehmerId'  => $teilnehmer->getTeilnehmerId()
        ]);
    }

    // Teilnehmer löschen
    public function deleteTeilnehmer(int $teilnehmerId) : bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM teilnehmer WHERE teilnehmerId = :teilnehmerId");
        return $stmt->execute(['teilnehmerId' => $teilnehmerId]);
    }
}
?>