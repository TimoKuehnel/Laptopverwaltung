<?php

// Definiert einen Trait namens DateValidationTrait
// Traits sind wiederverwendbare Code-Module, die in Klassen mit use eingebunden werden können
trait DateValidationTrait
{
    /* public: die Methode ist von außen sichtbar.
     * ?string $datumString: Parameter kann string oder null sein
     * format = 'Y-m-d': Default-Format ist 'Y-m-d'
     * Rückgabewert ?DateTime: die Methode gibt DateTime oder null zurück
     */
    public function createDateOrNull(?string $datumString, string $format = 'Y-m-d'): ?DateTime
    {
        /* if (empty($datumString)) prüft, ob $datumString leer ist (null, '', '0' etc.).
         * Wenn er leer ist, wird sofort null zurückgeben. Das verhindert, dass ein leerer String später zu new DateTime() (also „heute“) wird
         */
        if (empty($datumString)) {
            return null;
        }

        /* DateTime::createFromFormat($format, $datumString) versucht, aus dem gegebenen String ein DateTime-Objekt zu erstellen
         * => Dabei wird exakt das angegebene Format erwartet (z. B. 'Y-m-d').
         * Ergebnis: ein DateTime-Objekt oder false, falls die Erstellung fehlschlägt.
         */
        $date = DateTime::createFromFormat($format, $datumString);

        /* DateTime::createFromFormat($format, $datumString) kann false zurückgeben, wenn der String nicht zum angegebenen Format passt
         * => '2025-13-05' passt nicht zu 'Y-m-d', weil der Monat 13 ungültig ist
         * => Falls der String nicht korrekt ist, wird sofort eine Exception geworfen.
         */
        if ($date === false) {
            throw new InvalidArgumentException(
                "Ungültiges Datumsformat für '$datumString'. Erwartet wird: $format."
            );
        }

        // getLastErrors() liefert ein Array mit Fehlern und Warnungen, die beim Parsen des Datums entstanden sind
        $errors = DateTime::getLastErrors();

        /* ($errors['warning_count'] ?? 0)  => Prüft die Anzahl der Warnungen.
         * ?? 0 bedeutet:                   => Falls der Key warning_count nicht existiert, nimm 0.
         * ($errors['error_count'] ?? 0)    => Prüft die Anzahl der Fehler.
         * if (... || ...)                  => Wenn Warnungen oder Fehler > 0, dann ist das Datum nicht sauber
         *  => Wieder wird eine InvalidArgumentException geworfen, um klarzumachen, dass das Datum ungültig ist.
         */
        if (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0) {
            throw new InvalidArgumentException(
                "Ungültiges Datumsformat für '$datumString'. Erwartet wird: $format."
            );
        }

        // Wenn alles okay ist ($date !== false und keine Warnungen/Fehler), geben wir das DateTime-Objekt zurück
        return $date;
    }

    /* function formatDateOrNull ist der Methodenname
     * : string (am Ende der Signatur) ist die Rückgabentypdeklaration
     * => Die Methode gibt immer einen string zurück. Wenn sie null zurückgeben könnte, müsste hier ?string stehen
     * 
     * Das sind die Parameter — jeweils mit Typ und optionalen Standardwerten:
     * 1. ?DateTime $date
     *      => Das ? vor DateTime bedeutet: der Parameter kann entweder ein DateTime-Objekt oder null sein
     *      => WICHTIG: Falls statt DateTime ein String reinkommt, führt $date->format(...) zu einem Fehler — deshalb ist der Typ wichtig
     * 
     * 2. string $format = 'Y-m-d'
     *      => $format ist ein String mit dem Datumsformat nach PHPs DateTime::format() Regeln
     *      => Standard: 'Y-m-d' (ISO-ähnlich: z. B. 2025-11-06).
     *      => Es kann beim Aufruf ein anderes Format übergeben (z. B. 'd-m-Y' → 06-11-2025) werden
     *
     * 3. string $default = 'Kein Datum vorhanden'
     *      => Text, der zurückgegeben wird, wenn $date === null
     *      => Standardwert ist 'Kein Datum vorhanden'
     *      => Es kann beim Aufruf einen anderen Default angegeben werden
     */
    public function formatDateOrNull(?DateTime $date, string $format = 'Y-m-d', string $default = 'Kein Datum vorhanden'): string
    {
        /* Ternärer Operator
         * 1. $date ? ... : ...
         *      => Ternärer Operator: Wenn $date truthy (hier: nicht null), dann wird die erste Expression ausgewertet, sonst die zweite
         *      => Weil $date entweder DateTime-Objekt oder null ist, entspricht das einer Null-Abfrage
         * 
         * 2. $date->format($format)
         *      => Ruft die Methode format() des DateTime-Objekts auf und liefert einen formatierten String zurück
         *      => Beispiel: $date->format('Y-m-d') → '2025-11-06'.
         *      => Diese Methode ignoriert nicht das null-Case — deswegen prüft die Ternary vorher
         * 
         * 3. : $default
         *      => Falls $date null ist, wird der $default-String zurückgegeben (z. B. 'Kein Datum vorhanden')
         */
        return $date ? $date->format($format) : $default;
    }
}

class DateHelper
{
    // use DateValidationTrait bindet den Trait DateValidationTrait in die Klasse ein. 
    // Dadurch bekommt DateHelper automatisch alle Instanzmethoden (createDateOrNull, formatDateOrNull) aus diesem Trait
    use DateValidationTrait;

    /* Die Methode ist öffentlich und von außen zugreifbar
     * Die Methode ist statisch, sie wird über DateHelper::createDateOrNull(...) aufgerufen, ohne ein Objekt von DateHelper zu erzeugen
     * function createDateOrNull(...): ?DateTime — Methodensignatur:
     * => Parameter 1: ?string $datumString — ein String oder null.
     * => Parameter 2: string $format = 'Y-m-d' — optionales Format mit Default 'Y-m-d'.
     * => Rückgabetyp ?DateTime — die Methode gibt entweder ein DateTime-Objekt oder null zurück
     */
    public static function createDateOrNull(?string $datumString, string $format = 'Y-m-d'): ?DateTime
    {
        /* new class { ... }; — erzeugt eine anonyme Klasse (eine Klasse ohne Namen) und instanziiert sie sofort
         * Innerhalb dieser anonymen Klasse wird use DateValidationTrait aufgerufen
         * => Das heißt: die anonyme Klasse enthält die Methoden aus dem Trait
         * 
         * Das Ergebnis new class { ... } ist ein neues Objekt (Instanz der anonymen Klasse)
         * => Dieses Objekt wird in der Variablen $tmp gespeichert
         * 
         * Weil Traits selbst keine Instanzen sind, brauchen wir ein Objekt, das den Trait verwendet, um die Trait-Methoden aufzurufen
         * => Die anonyme Klasse ist ein schneller Weg, ein solches Objekt zu erstellen, ohne eine benannte Hilfsklasse zu definieren
         */
        $tmp = new class {
            use DateValidationTrait;
        };

        /* $tmp->createDateOrNull($datumString, $format) ruft die Trait-Methode createDateOrNull auf dem temporären Objekt $tmp auf
         * => Diese Methode führt die eigentliche Validierungs-/Parsing-Logik aus (z. B. DateTime::createFromFormat, Fehlerprüfung etc.)
         * 
         * return — gibt das Ergebnis als Rückgabewert der statischen Methode DateHelper::createDateOrNull() zurück
         * Das Ergebnis ist entweder ein DateTime-Objekt oder null
         */
        return $tmp->createDateOrNull($datumString, $format);
    }
}

?>