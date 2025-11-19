<?php

require_once "kurs.php";

class KursService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Alle Kurse abrufen
    public function getAllKurse(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM kurs");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allKurse = [];

        foreach ($rows as $row) {
            $allKurse[] = new Kurs(
                $row['kursId'],
                $row['kursnummer'],
                $row['kuerzel'],
                $row['beginn'] ? new DateTime($row['beginn']) : null,
                $row['ende'] ? new DateTime($row['ende']) : null
            );
        }
        return $allKurse;
    }

    // Datensatz anhand der ID abrufen
    public function getKursById(int $kursId): ?Kurs
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kurs WHERE kursId = :kursId");
        $stmt->execute(['kursId' => $kursId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Kurs(
                $row['kursId'],
                $row['kursnummer'],
                $row['kuerzel'],
                $row['beginn'] ? new DateTime($row['beginn']) : null,
                $row['ende'] ? new DateTime($row['ende']) : null
            );
        } else {
            return null;
        }
    }

    // Kursnummer anhand der KursId abrufen
    public function getKursnummerById(int $kursId): ?int
    {
        $stmt = $this->pdo->prepare("SELECT kursnummer FROM kurs WHERE kursId = :kursId");
        $stmt->execute(['kursId' => $kursId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['kursnummer'] : null;
    }

        public function getKuerzelById(int $kursId): ?int
    {
        $stmt = $this->pdo->prepare("SELECT kuerzel FROM kurs WHERE kursId = :kursId");
        $stmt->execute(['kursId' => $kursId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['kuerzel'] : null;
    }

    // Datensatz anhand der Kursnummer abrufen
    public function getKursByKursnummer(int $kursnummer): ?Kurs
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kurs WHERE kursnummer = :kursnummer");
        $stmt->execute(['kursnummer' => $kursnummer]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Kurs(
                $row['kursId'],
                $row['kursnummer'],
                $row['kuerzel'],
                $row['beginn'] ? new DateTime($row['beginn']) : null,
                $row['ende'] ? new DateTime($row['ende']) : null
            );
        } else {
            return null;
        }
    }

    // Datensatz anhand des Kuerzels abrufen
    public function getKursByKuerzel(int $kuerzel): ?Kurs
    {
        $stmt = $this->pdo->prepare("SELECT * FROM kurs WHERE kuerzel = :kuerzel");
        $stmt->execute(['kuerzel' => $kuerzel]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Kurs(
                $row['kursId'],
                $row['kursnummer'],
                $row['kuerzel'],
                $row['beginn'] ? new DateTime($row['beginn']) : null,
                $row['ende'] ? new DateTime($row['ende']) : null
            );
        } else {
            return null;
        }
    }

    // Kurs anhand eines Suchbegriffs abrufen
    public function searchKurse(string $search): array
    {
        $stmt = $this->pdo->prepare("
                            SELECT * FROM kurs 
                            WHERE kursnummer LIKE :search
                            ORDER BY kursnummer
                            LIMIT 50");
        $stmt->execute(['search' => "%$search%"]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($rows as $row) {
            $result[] = new Kurs(
                $row['kursId'],
                $row['kursnummer'],
                $row['kuerzel'],
                $row['beginn'],
                $row['ende']
            );
        }
        return $result;
    }

    // Neuen Kurs einfügen
    public function insertKurs(Kurs $kurs): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO kurs (kursnummer, kuerzel, beginn, ende) VALUES (:kursnummer, :kuerzel, :beginn, :ende)");
        return $stmt->execute([
            'kursnummer'    => $kurs->getKursnummer(),
            'kuerzel'       => $kurs->getKuerzel(),
            'beginn'        => $kurs->getBeginnForDb(),
            'ende'          => $kurs->getEndeForDb()
        ]);
    }

    // Kurs aktualisieren
    public function updateKurs(Kurs $kurs): bool
    {
        $stmt = $this->pdo->prepare("UPDATE kurs SET kursnummer = :kursnummer, kuerzel = :kuerzel, beginn = :beginn, ende = :ende WHERE kursId = :kursId");
        return $stmt->execute([
            'kursnummer'    => $kurs->getKursnummer(),
            'kuerzel'       => $kurs->getKuerzel(),
            'beginn'        => $kurs->getBeginnForDb(),
            'ende'          => $kurs->getEndeForDb(),
            'kursId'        => $kurs->getKursId()
        ]);
    }

    // Kurs löschen
    public function deleteKurs(int $kursId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM kurs WHERE kursId = :kursId");
        return $stmt->execute(['kursId' => $kursId]);
    }
}
?>