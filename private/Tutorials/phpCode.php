<?php

/*
A. private ?DateTime $endeLeasing;

"?int" ist ein "nullable typed property"
=> Eine Property, deren Datentyp einen bestimmten Typ oder null annehmen darf



B. $row['endeLeasing'] ? new DateTime($row['endeLeasing']) : null

Ternärer Operator
Bedingung ? WertWennTrue : WertWennFalse
PHP prüft, ob der Wert $row['ausleihdatum'] nicht null oder leer ist
-> Wenn True (nicht leer oder null)  -> Erzeugen eines neues DateTime - Objekts
-> Wenn false (leer oder null)       -> Feld / Ergebnis auf null setzen


C. public function getUsername() : ?string {
    return $_SESSION['Username'] ?? null;
}

?string = Diese Methode kann entweder einen string oder null zurückgeben
 -> Wenn der Benutzername gesetzt ist    -> Rückgabewert ist ein String
 -> Wenn kein Benutzername gesetzt ist   -> Rückgabewert ist null
?? (Null-Coalescing-Operator) -> Kurzform für eine if - else Abfrage
 -> Wenn $_SESSION['Username'] existiert und nicht null ist, gib diesen Wert zurück.
 -> Sonst gib null zurück.
     -> ?? prüft, ob der linke Ausdruck existiert und nicht null ist
     -> Wenn nicht -> nimm den rechten Wert

Lange Variante mit if - else:
    public function getUsername(): ?string {
        if (isset($_SESSION['Username'])) {
            return $_SESSION['Username'];
        } else {
            return null;
        }
    }
*/


/*

1. Nullable Typed Property (?Type)
Eine nullable typed property erlaubt, dass eine Variable entweder vom angegebenen Typ oder null ist.

class User {
    public ?string $username; => darf string oder null sein
}

$user = new User();
$user->username = "Alice";  => gültig!
$user->username = null;     => gültig!

?string → die Variable kann string oder null sein
Ohne ? wäre nur string erlaubt, null würde einen Fehler erzeugen


2. Ternärer Operator (condition ? expr1 : expr2)
Der ternäre Operator ist eine Kurzform von if…else

$isLoggedIn = true;
$message = $isLoggedIn ? "Willkommen!" : "Bitte einloggen.";
echo $message; // Ausgabe: Willkommen!


condition ? expr1 : expr2
Wenn condition wahr     => Rückgabe expr1
Wenn condition falsch   => Rückgabe expr2


3. Null-Coalescing-Operator (??)
Der Null-Coalescing-Operator prüft, ob ein Wert existiert und nicht null ist, sonst wird ein Standardwert genutzt.

session_start();

$_SESSION['username'] = "Alice";
$username = $_SESSION['username'] ?? "Gast";
echo $username;     => Ausgabe: Alice

unset($_SESSION['username']);
$username = $_SESSION['username'] ?? "Gast";
echo $username;     => Ausgabe: Gast


?? prüft: existiert der linke Ausdruck und ist er nicht null?
Ja      => linkes Element
Nein    => rechtes Element (Standardwert)


4. Vergleich Ternär vs Null-Coalescing

4.1. Ternär
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Gast";

4.2. Null-Coalescing
$username = $_SESSION['username'] ?? "Gast";
?>