<?php

require_once "../utility/dateCheck.php";
class Geraet
{
    use DateValidationTrait;
    private ?int $geraeteId;
    private string $serviceTag;
    private int $modellId;
    private ?DateTime $endeLeasing;

    /* "?int" ist ein "nullable typed property"
     *  => Eine Property, deren Datentyp einen bestimmten Typ oder null annehmen darf
     */
    public function __construct(
        ?int $geraeteId,
        string $serviceTag,
        int $modellId,
        ?DateTime $endeLeasing
    ) {
        $this->geraeteId = $geraeteId;
        $this->serviceTag = $serviceTag;
        $this->modellId = $modellId;
        $this->endeLeasing = $endeLeasing ?? null;
    }

    // Getter und Setter (GeräteId)
    public function getGeraeteId(): int
    {
        return $this->geraeteId;
    }

    public function setGeraeteId(int $geraeteId): void
    {
        $this->geraeteId = $geraeteId;
    }

    // Getter und Setter (ServiceTag)
    public function getServiceTag(): string
    {
        return $this->serviceTag;
    }

    public function setServiceTag(string $serviceTag): void
    {
        $this->serviceTag = $serviceTag;
    }

    // Getter und Setter (ModellId)
    public function getModellId(): int
    {
        return $this->modellId;
    }

    public function setModellId($modellId): void
    {
        $this->modellId = $modellId;
    }

    // Getter und Setter (EndeLeasing -> DateTime und String)
    public function getEndeLeasing(): ?DateTime
    {
        return $this->endeLeasing;
    }

    public function getEndeLeasingForDb(): ?string
    {
        return $this->endeLeasing
            ? $this->formatDateOrNull($this->endeLeasing, 'Y-m-d', null)
            : null;
    }

    public function getEndeLeasingAsString(): string
    {
        return $this->formatDateOrNull($this->endeLeasing, 'd-m-Y');
    }
    public function setEndeLeasing(?string $endeLeasing): void
    {
        $this->endeLeasing = $this->createDateOrNull($endeLeasing);
    }
}

?>