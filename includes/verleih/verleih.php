<?php

require_once "../utility/dateCheck.php";
class verleih
{
    use DateValidationTrait;

    private ?int $verleihId;
    private int $teilnehmerId;
    private int $geraeteId;
    private ?DateTime $ausleihdatum;
    private ?DateTime $rueckgabedatum;

    public function __construct(
        ?int $verleihId,
        int $teilnehmerId,
        int $geraeteId,
        ?DateTime $ausleihdatum,
        ?DateTime $rueckgabedatum
    ) {
        
    }

    // Getter und Setter (VerleihId)
    public function getVerleihId() : int {
        return $this->verleihId;
    }

    public function setVerleihId(int $verleihId) : void {
        $this->verleihId = $verleihId;
    }

    // Getter und Setter TeilnehmerId
    public function getTeilnehmerId(): int
    {
        return $this->teilnehmerId;
    }

    public function setTeilnehmerId(int $teilnehmerId): void
    {
        $this->teilnehmerId = $teilnehmerId;
    }

    // Getter und Setter (GeraeteId)
    public function getGeraeteId(): int
    {
        return $this->geraeteId;
    }

    public function setGeraeteId(int $geraeteId): void
    {
        $this->geraeteId = $geraeteId;
    }

    // Getter und Setter (Ausleihdatum)
    public function getAusleihdatum(): ?DateTime
    {
        return $this->ausleihdatum;
    }

    public function getAusleihdatumForDb(): ?string
    {
        return $this->ausleihdatum
            ? $this->formatDateOrNull($this->ausleihdatum, 'Y-m-d', null)
            : null;
    }

    public function getAusleihdatumAsString(): ?string
    {
        return $this->formatDateOrNull($this->ausleihdatum, 'd-m-Y');
    }

    public function setAusleihdatum(?string $ausleihdatum): void
    {
        $this->ausleihdatum = $this->createDateOrNull($ausleihdatum);
    }

    // Getter und Setter (Rueckgabedatum)
    public function getRueckgabedatum(): ?DateTime
    {
        return $this->rueckgabedatum;
    }

    public function getRueckgabedatumForDb(): ?string
    {
        return $this->rueckgabedatum
            ? $this->formatDateOrNull($this->rueckgabedatum, 'Y-m-d', null)
            : null;
    }

    public function getRueckgabedatumAsString(): ?string
    {
        return $this->formatDateOrNull($this->rueckgabedatum, 'd-m-Y');
    }

    public function setRueckgabedatum(?string $rueckgabedatum): void
    {
        $this->rueckgabedatum = $this->createDateOrNull($rueckgabedatum);
    }
}

?>