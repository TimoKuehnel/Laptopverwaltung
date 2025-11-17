<?php
require_once "../utility/dateCheck.php";

class Kurs
{
    use DateValidationTrait;
    private ?int $kursId;
    private int $kursnummer;
    private int $kuerzel;
    private ?DateTime $beginn;
    private ?DateTime $ende;

    public function __construct(
        ?int $kursId,
        int $kursnummer,
        int $kuerzel,
        ?DateTime $beginn,
        ?DateTime $ende
    ) {
        $this->kursId = $kursId;
        $this->kursnummer = $kursnummer;
        $this->kuerzel = $kuerzel;
        $this->beginn = $beginn ?? null;
        $this->ende = $ende ?? null;
    }

    // Getter und Setter (KursId)
    public function getKursId(): int
    {
        return $this->kursId;
    }

    public function setKursId(int $kursId): void
    {
        $this->kursId = $kursId;
    }

    // Getter und Setter (Kursnummer)
    public function getKursnummer(): int
    {
        return $this->kursnummer;
    }

    public function setKursnummer(int $kursnummer): void
    {
        $this->kursnummer = $kursnummer;
    }

    // Getter und Setter (Kuerzel)
    public function getKuerzel(): int
    {
        return $this->kuerzel;
    }

    public function setKuerzel(int $kuerzel): void
    {
        $this->kuerzel = $kuerzel;
    }

    // Getter und Setter (Beginn)
    public function getBeginn(): ?DateTime
    {
        return $this->beginn;
    }

    public function getBeginnForDb(): ?string
    {
        return $this->beginn ? $this->formatDateOrNull($this->beginn, 'Y-m-d', null) : null;
    }

    public function getBeginnAsString(): ?string
    {
        return $this->formatDateOrNull($this->beginn, 'd-m-Y');
    }

    public function setBeginn(?string $beginn): void
    {
        $this->beginn = $this->createDateOrNull($beginn);
    }

    // Getter und Setter (Ende)
    public function getEnde(): ?DateTime
    {
        return $this->ende;
    }

    public function getEndeForDb(): ?string
    {
        return $this->ende ? $this->formatDateOrNull($this->ende, 'Y-m-d', null) : null;
    }

    public function getEndeAsString(): ?string
    {
        return $this->formatDateOrNull($this->ende, 'd-m-Y');
    }

    public function setEnde(?string $ende): void
    {
        $this->ende = $this->createDateOrNull($ende);
    }
}
?>