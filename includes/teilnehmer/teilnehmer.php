<?php

require_once "../utility/dateCheck.php";
class Teilnehmer
{
    use DateValidationTrait;
    private ?int $teilnehmerId;
    private string $vorname;
    private string $nachname;
    private int $kursId;

    public function __construct(
        ?int $teilnehmerId,
        string $vorname,
        string $nachname,
        int $kursId
    ) {
        $this->teilnehmerId = $teilnehmerId;
        $this->vorname = $vorname;
        $this->nachname = $nachname;
        $this->kursId = $kursId;
    }


    // Getter und Setter (TeilnehmerId)
    public function getTeilnehmerId(): int
    {
        return $this->teilnehmerId;
    }

    public function setTeilnehmerId(int $teilnehmerId): void
    {
        $this->teilnehmerId = $teilnehmerId;
    }


    // Getter und Setter (Vorname)
    public function getVorname(): string
    {
        return $this->vorname;
    }

    public function setVorname(string $vorname): void
    {
        $this->vorname = $vorname;
    }


    // Getter und Setter (Nachname)
    function getNachname(): string
    {
        return $this->nachname;
    }

    function setNachname(string $nachname): void
    {
        $this->nachname = $nachname;
    }

    
    // Getter und Setter (KursId)
    public function getKursId(): int
    {
        return $this->kursId;
    }

    public function setKursId($kursId): void
    {
        $this->kursId = $kursId;
    }
}