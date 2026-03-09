<?php

trait DateValidationTrait
{
    public function createDateOrNull(?string $datumString, string $format = 'Y-m-d'): ?DateTime
    {
        if (empty($datumString)) {
            return null;
        }

        $date = DateTime::createFromFormat($format, $datumString);

        if ($date === false) {
            throw new InvalidArgumentException(
                "Ungültiges Datumsformat für '$datumString'. Erwartet wird: $format."
            );
        }

        $errors = DateTime::getLastErrors();

        if (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0) {
            throw new InvalidArgumentException(
                "Ungültiges Datumsformat für '$datumString'. Erwartet wird: $format."
            );
        }

        return $date;
    }

    public function formatDateOrNull(?DateTime $date, string $format = 'Y-m-d', ?string $default = 'Kein Datum vorhanden'): ?string
    {
        return $date ? $date->format($format) : $default;
    }

    public function createDateOrNullFromInput(?string $datumString): ?DateTime
    {
        return $this->createDateOrNull($datumString, 'Y-m-d');
    }
}

class DateHelper
{
    use DateValidationTrait;

    public static function createDateOrNull(?string $datumString, string $format = 'Y-m-d'): ?DateTime
    {
        $tmp = new class {
            use DateValidationTrait;
        };

        return $tmp->createDateOrNull($datumString, $format);
    }
}
?>