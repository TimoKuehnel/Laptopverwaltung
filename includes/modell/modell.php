<?php

require_once "../utility/dateCheck.php";
class Modell
{
    use DateValidationTrait;

    private ?int $modellId;
    private string $modellbezeichnung;

    public function __construct(
        ?int $modellId,
        string $modellbezeichnung
    ) {
        $this->modellId = $modellId;
        $this->modellbezeichnung = $modellbezeichnung;
    }

    // Getter und Setter (ModellId)
    public function getModellId(): int
    {
        return $this->modellId;
    }

    public function setModellId(int $modellId): void
    {
        $this->modellId = $modellId;
    }

    // Getter und Setter (Modellbezeichnung)
    public function getModellbezeichnung(): string
    {
        return $this->modellbezeichnung;
    }

    public function setModellbezeichnung(string $modellbezeichnung): void
    {
        $this->modellbezeichnung = $modellbezeichnung;
    }
}
?>