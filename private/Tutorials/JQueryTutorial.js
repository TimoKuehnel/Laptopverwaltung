// crud.js

/* Definiert eine Funktion namens createRecord
 * Die Funktion erhält 3 Parameter:
 *  => url: Die Adresse, an die die Anfrage geschickt wird
 *  => formSelector: Ein jQuery-Selektor für das Formular
 *  => onSuccess: Eine optionale Funktion, die aufgerufen wird, wenn der Server erfolgreich antwortet
 */
function createRecord(url, formSelector, onSuccess) {

    // Sucht das Formular im DOM mithilfe des übergebenen Selektors und speichert es als jQuery-Objekt in form
    const form = $(formSelector);

    // Serialisiert alle Formularfelder in ein URL-kodiertes Format (z. B. name=Max&email=max@example.com)
    const data = form.serialize();

    /* Ruft eine Hilfsfunktion sendAjaxRequest auf
     *  => Diese sendet die Daten per POST an die angegebene url
     *  => Wenn der Server antwortet, wird die anonyme Callback-Funktion mit dem response-Objekt aufgerufen
     */
    sendAjaxRequest(url, 'POST', data, function (response) {

        // Zeigt ein Informations-Modalfenster mit dem Titel "Erfolg" und der Nachricht aus der Serverantwort (response.message) an
        showInfoModal('Erfolg', response.message);

        //Setzt das Formular zurück, also löscht alle Eingaben
        form[0].reset();

        // Wenn eine onSuccess-Funktion übergeben wurde, wird sie jetzt aufgerufen
        if (onSuccess) {
            onSuccess(response);
        }
    });
}

/* Eine anonyme Callback-Funktion ist eine Funktion ohne Namen, die als Argument an eine andere Funktion übergeben wird
 * Eine Callback-Funktion ist eine Funktion, die als Parameter an eine andere Funktion übergeben wird
 * => Die aufrufende Funktion entscheidet selbst, wann (oder ob) sie diese Callback-Funktion ausführt
 */

/* Definiert eine Funktion namens updateRecord
 * Die Funktion erhält 4 Parameter:
 *  => url: Die Adresse, an die die Anfrage geschickt wird
 *  => data: Objekt mit den zu sendenden Daten
 *  => modalElement: Ein HTML-Element (Bootstrap-Modal), das evtl. geschlossen werden soll
 *  => Eine optionale Funktion, die aufgerufen wird, wenn der Server erfolgreich antwortet
 */
function updateRecord(url, data, modalElement, onSuccess) {

    // Ajax-Aufruf mit den übergebenen Daten.
    sendAjaxRequest(url, 'POST', data, function (response) {

        // Prüft, ob der Server im Antwortobjekt success: true gesendet hat
        if (response.success) {

            // Wenn eine Erfolgs-Callback-Funktion angegeben ist, wird sie ausgeführt
            if (onSuccess) {
                onSuccess(response);
            }

            // Falls ein modalElement übergeben wurde (z. B. ein Edit-Dialog), wird mithilfe von Bootstrap das Modal geschlossen
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
            // Zeigt eine Erfolgsmeldung an
            showInfoModal('Erfolg', response.message);
        } else {
            // Wenn response.success nicht true ist, wird eine Fehlermeldung angezeigt
            showInfoModal('Fehler', response.message);
        }
    });
}

/* Definiert eine Funktion namens deleteRecord
 * Die Funktion erhält 5 Parameter:
 *  => url: Die Adresse, an die die Anfrage geschickt wird
 *  => id: Die ID des zu löschenden Eintrags
 *  => row: Das jQuery-Objekt der Tabellenzeile, die entfernt werden soll
 *  => Optionales Bootstrap-Modal, das geschlossen werden soll
 *  => onSuccess: Eine optionale Funktion, die aufgerufen wird, wenn der Server erfolgreich antwortet
 */
function deleteRecord(url, id, row, modalElement, onSuccess) {
    // Sendet eine POST-Anfrage an url mit dem zu löschenden id
    sendAjaxRequest(url, 'POST', { id: id }, function (response) {
        if (response.success) {
            /* Es wird DataTables-Objekt der Tabelle geholt, in der sich die Zeile befindet
             *  => $(row).closest('table') sucht den nächsten übergeordneten <table>-Tag von der Zeile
             *  => .DataTable() gibt das DataTables-Objekt zurück, mit dem die Zeilen gelöscht und die Anzeige aktualisiert werden können
             */
            const table = $(row).closest('table').DataTable();
            // Blendet die Tabellenzeile (z. B. einen Eintrag in einer Liste) sanft aus (über 400 ms) und entfernt sie anschließend aus dem DOM
            $(row).fadeOut(400, function () {
                /* Callback nach Fade-Out: Sobald das Ausblenden abgeschlossen ist:
                 *  => table.row(row).remove() entfernt die Zeile aus DataTables (nicht nur aus DOM)
                 *  => .draw(false) aktualisiert die Tabelle ohne die aktuelle Seite zurückzusetzen
                 *      => Wäre draw(true), würde die Tabelle auf die erste Seite springen
                 */
                table.row(row).remove().draw(false);
            });

            // Falls ein Modal geöffnet ist (z. B. eine Lösch-Bestätigung), wird es geschlossen
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }

            // Zeigt eine Erfolgsmeldung an
            showInfoModal('Erfolg', response.message);

            // Führt eine zusätzliche Erfolgsaktion aus, wenn definiert
            if (onSuccess) {
                onSuccess(response);
            }
            // Falls response.success false ist, zeige eine Fehlermeldung
        } else {
            showInfoModal('Fehler', response.message);
        }
    });
}

/* DOM (Document Object Model)
 * Ist eine strukturierte, programmierbare Darstellung einer HTML- oder XML-Webseite
 * Das DOM ist die „innere Datenstruktur“ einer Webseite, die der Browser erzeugt, damit JavaScript mit der Seite interagieren kann (=> Baumstruktur)
 */


// ajax.js
/* Hier wird eine Funktion namens sendAjaxRequest definiert
 * Diese dient als zentrale Schnittstelle für alle Ajax-Aufrufe der Anwendung und erhält 5 Parameter
 * => url: Zieladresse des Requests
 * => method: HTTP-Methode ('POST' oder 'GET')
 * => data: Daten, die an den Server gesendet werden (z. B. Formularinhalte oder eine ID)
 * => onSuccess: Callback-Funktion, die bei Erfolg ausgeführt wird
 * => onError: (optional) Callback-Funktion für Fehlerfälle
 */
function sendAjaxRequest(url, method, data, onSuccess, onError) {
    // jQuery-Shortcut für einen Ajax-Aufruf (Somit kann man HTTP-Anfrage an den Server senden, ohne die Seite neu zu laden)
    $.ajax({
        url: url,                           // Gibt die Adresse an, wohin die Anfrage gesendet wird
        type: method,                       // Legt die HTTP-Methode fest
        data: data,                         // Die Daten, die an den Server gesendet werden
        dataType: 'json',                   // Sagt jQuery, dass die Antwort des Servers im JSON-Format erwartet wird (z. B. { "success": true, "message": "Gespeichert!" })

        /* Anonyme Callback-Funktion
         * => Diese wird automatisch aufgerufen, wenn der Server erfolgreich antwortet (HTTP-Status 200)
         * => Das Argument response ist das JSON-Objekt, das der Server zurückgeschickt hat
         */
        success: function (response) {
            // Prüft, ob das Antwortobjekt vom Server ein Feld success mit dem Wert true enthält   
            if (response.success) {
                // Wenn der Server-Erfolg bestätigt wird, wird die Erfolgsfunktion ausgeführt, die crud.js beim Aufruf übergeben hat
                onSuccess(response);

                // Wenn der Server success: false sendet (also z. B. Validierungsfehler), wird kein onSuccess aufgerufen, sondern stattdessen ein Fehlermodal angezeigt
            } else {
                // Ruft die Funktion "showInfoModal" auf
                showInfoModal('Fehler', response.message);
            }
            // Ende der success-Callback-Funktion
        },
        /* Diese Callback-Funktion wird ausgeführt, wenn der Ajax-Aufruf selbst fehlschlägt, z. B. wenn:
         * der Server nicht erreichbar ist,
         * ein Netzwerkfehler auftritt,
         * oder der Server einen HTTP-Fehler (404, 500 etc.) zurückgibt

         * Die Parameter bedeuten:
         * xhr: das XMLHttpRequest-Objekt (enthält Details zur Antwort)
         * status: der Fehlerstatus (z. B. "timeout", "error")
         * error: die eigentliche Fehlermeldung
         */
        error: function (xhr, status, error) {

            // Schreibt die Fehlermeldung in die Browser-Konsole
            console.error("AJAX-Fehler:", status, error);

            // Wenn der Aufrufer (crud.js) eine eigene Fehlerbehandlung übergeben hat (onError), dann wird diese Funktion aufgerufen
            if (onError) onError(xhr, status, error);

            // Wenn keine eigene Fehlerbehandlung übergeben wurde, zeigt die Funktion stattdessen ein allgemeines Fehlermodal an
            else showInfoModal('Fehler', 'Es ist ein unbekannter Fehler aufgetreten.');
        }
    });
}

/* Hier wird eine Funktion namens sendAjaxRequest definiert
 * Diese dient als zentrale Schnittstelle für alle Ajax-Aufrufe der Anwendung und erhält 5 Parameter
 * => url: Zieladresse des Requests
 * => method: HTTP-Methode ('POST' oder 'GET')
 * => data: Daten, die an den Server gesendet werden (z. B. Formularinhalte oder eine ID)
 * => onSuccess: Callback-Funktion, die bei Erfolg ausgeführt wird
 * => onError: (optional) Callback-Funktion für Fehlerfälle
 */
function sendAjaxRequest(url, method, data, onSuccess, onError) {
    // Startet einen jQuery AJAX-Request mit einem Objekt von Optionen
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: 'json',

        // Callback, das ausgeführt wird, wenn der Request erfolgreich war und die Antwort korrekt als JSON geparst wurde. 
        //  => response ist das geparste JS-Objekt (oder Array).
        success: function (response) {

            /* Sonderfall: Wenn response ein Array ist , dann wird dieses Array in ein Objekt gepackt { success: true, data: response } 
             * und an onSuccess weitergereicht
             * Dadurch ist sendAjaxRequest flexibel
             * => sowohl rohe Arrays als auch objektförmige Antworten ({ success, data, message }) werden unterstützt
             * return; beendet die success-Funktion
             */
            if (Array.isArray(response)) {
                onSuccess({ success: true, data: response });
                return;
            }

            /* Wenn die Antwort kein Array war, wird hier das erwartete Objekt-Schema geprüft
             * Falls response.success true ist wird onSuccess(response) aufgerufen
             * Sonst wird ein Informations-Modal mit einer Fehlermeldung angezeigt
             *  => response.message wird verwendet, falls vorhanden
             *  => Falls keine message vorhanden ist, wird der Fallback-Text 'Unbekannter Fehler.' angezeigt
             *  => Annahme: Server-Antworten in diesem Fall haben das Schema { success: boolean, data: ..., message: string }
             */
            if (response.success) {
                onSuccess(response);
            } else {
                showInfoModal('Fehler', response.message || 'Unbekannter Fehler.');
            }
        },
        /* Fehler-Callback für den AJAX-Request. Wird aufgerufen bei:
         *  => Netzwerkfehlern (z. B. Kein Internet)
         *  => HTTP-Statuscodes außerhalb der 2xx (z. B. 404, 500)
         *  => oder wenn dataType: 'json' fehlschlägt (ParseError)
         * 
         * Parameter:
         * xhr      => das jqXHR Objekt (enthält Status, responseText, usw.).
         * status   => kurzer Text wie 'timeout', 'error', 'abort', 'parsererror'.
         * error    => die Error-Nachricht oder Exception.
         */
        error: function (xhr, status, error) {
            console.error("AJAX-Fehler:", status, error);
            if (onError) onError(xhr, status, error);
            else showInfoModal('Fehler', 'Es ist ein unbekannter Fehler aufgetreten.');
        }
    });
}

/* Der Browser sammelt Formulardaten -> sendet sie mit sendAjaxRequest() -> PHP verarbeitet sie -> schickt JSON zurück -> JavaScript empfängt die Antwort ->
 * ruft Callback-Funktion -> zeigt Meldung an bzw. aktualisiert die GUI
 *
 * Frontend (crud.js)	    createRecord()	        Holt Formulardaten & definiert Callback
 * Frontend (ajax.js)	    sendAjaxRequest()	    Sendet Daten an den Server & verarbeitet Antwort
 * Backend (PHP)	        geraet/add.php	        Prüft & speichert Daten, sendet JSON-Antwort
 * Zurück im Frontend	    Callback-Funktion	    Zeigt Erfolg oder Fehler an
 */


// geraet.js

/* jQuery-„Ready“-Funktion
 * Diese sorgt dafür, dass der Code erst ausgeführt wird, wenn das gesamte HTML-Dokument geladen ist (also alle Elemente im DOM existieren)
 */
$(document).ready(function () {

    /* Ruft eine Funktion loadModelle() auf, um die Dropdown-Liste der Modelle im Insert-Formular zu füllen
     * $('#insertModellId') wählt das <select>-Element im Formular aus
     * => Diese Funktion lädt per Ajax die verfügbaren Modelle aus der Datenbank
     */
    loadModelle($('#insertModellId'));

    // Insert 
    /* Hier wird ein Event Listener gesetzt:
     * Wenn das Formular mit der ID insertForm abgesendet wird (z. B. durch Klick auf einen Button), wird diese Funktion ausgeführt
     */
    $('#insertForm').on('submit', function (e) {
        // Verhindert das Standardverhalten des Formulars (also dass die Seite neu geladen wird)
        e.preventDefault();

        /* Ruft die CRUD-Funktion createRecord() auf
         * => Diese sendet die Formulardaten per Ajax an die PHP-Datei insertGeraet.php
         * => Wenn der Server mit success: true antwortet, zeigt createRecord() eine Erfolgsmeldung an und leert das Formular
         */
        createRecord('insertGeraet.php', '#insertForm');
    });


    // Update
    /* Event Listener: 
     * Wenn innerhalb der Tabelle #geraeteTable auf ein Element mit der Klasse .editButton geklickt wird, wird diese Funktion ausgeführt
     * Das ermöglicht dynamische Buttons -> auch neue Zeilen funktionieren, weil das Event an die Tabelle gebunden ist (Event Delegation)
     */
    $('#geraeteTable').on('click', '.editButton', function () {
        // Sucht die Tabellenzeile (<tr>), in der der geklickte „Bearbeiten“-Button liegt => Diese Zeile enthält alle Gerätedaten
        let row = $(this).closest('tr');

        // Speichert die Tabellenzeile temporär im Formular #editForm, damit man später weiß, welche Zeile aktualisiert werden soll
        $('#editForm').data('row', row);

        /* Holt die Werte aus den Tabellenzellen (Spalten):
         * Spalte 0: ID
         * Spalte 1: Service Tag
         * Spalte 2: Modell (mit data-id Attribut)
         * Spalte 3: Ende Leasing (als Text)
         */
        let id = row.find('td:eq(0)').text().trim();            // Die Methode .text() greift auf den sichtbaren Text innerhalb eines Elements zu: 
        // also alles zwischen <div> ... </div> oder <td> ... </td>
        let serviceTag = row.find('td:eq(1)').text().trim();
        let modellId = row.find('td:eq(2)').data('id');         // Damit werden benutzerdefinierte Datenattribute gelesen und gesetzt
        let endeLeasing = row.find('td:eq(3)').text().trim();

        /* Datumsformat-Korrektur
         * Die Tabelle zeigt das Datum z. B. als 31-10-2025, aber das HTML-Input-Feld erwartet 2025-10-31 (ISO-Format)
         */
        let parts = endeLeasing.split('-');
        if (parts.length === 3) {
            endeLeasing = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        /* Befüllt das Bearbeitungsformular (Modal) mit den Daten aus der Tabellenzeile
         * Dann wird loadModelle() aufgerufen, um das Modell-Select-Feld zu füllen und das aktuelle Modell vorzuwählen
         */
        $('#editId').val(id);                   // Die Methode .val() greift auf den Wert (value) von Formularelementen zu: <input>, <select>, <textarea>

        $('#editServiceTag').val(serviceTag);
        $('#editEndeLeasing').val(endeLeasing);
        loadModelle($('#editModellId'), modellId);

        // Öffnet das Bearbeitungs-Modal mit Bootstrap sodass der Benutzer nun die Daten bearbeiten kann
        new bootstrap.Modal(document.getElementById('editModal')).show();

        // Ende des Event-Handlers für den Klick auf .editButton
    });

    // Wenn das Bearbeitungsformular abgesendet wird, wird diese Funktion ausgeführt
    $('#editForm').on('submit', function (e) {

        // Kein Seitenreload 
        e.preventDefault();

        // Holt die gespeicherte Tabellenzeile zurück => So weiß das Skript, welche Zeile in der Tabelle später aktualisiert werden muss
        const row = $(this).data('row');

        // Erstellt ein Datenobjekt mit allen Eingaben aus dem Formular => Dieses Objekt wird an updateRecord() geschickt
        const data = {
            id: $('#editId').val(),
            serviceTag: $('#editServiceTag').val(),
            modellId: $('#editModellId').val(),
            endeLeasing: $('#editEndeLeasing').val()
        };

        /* Ruft updateRecord() auf (aus crud.js)
         * Diese Funktion sendet die Daten an updateGeraet.php (Server) und schließt das Modal nach Erfolg
         * Der letzte Parameter ist wieder eine anonyme Callback-Funktion, die bei Erfolg ausgeführt wird
         */
        updateRecord('updateGeraet.php', data, document.getElementById('editModal'), function () {

            // Aktualisiert die Tabelle direkt im Browser, damit der Nutzer sofort die neuen Werte sieht, ohne die Seite neu zu laden
            const selectedText = $('#editModellId option:selected').text();
            row.find('td:eq(1)').text(data.serviceTag);
            row.find('td:eq(2)').text(selectedText).data('id', data.modellId);

            /* Prüft, ob der Wert data.endeLeasing nicht leer ist.
             *  => data.endeLeasing kommt aus dem Bearbeitungsformular ($('#editEndeLeasing').val()), also als String
             */
            if (data.endeLeasing) {
                // Teilt den Datums-String anhand des Bindestrichs - in ein Array => "2025-11-05" → ["2025","11","05"].
                const parts = data.endeLeasing.split('-');

                // Prüft, ob das Array genau 3 Elemente enthält (Jahr, Monat, Tag)
                if (parts.length === 3) {
                    // Greift auf die 4. Spalte (td:eq(3)) der aktuellen Tabellenzeile zu (Ende-Leasing-Datum)
                    // => Setzt den Text auf das deutsche Datumsformat: Tag-Monat-Jahr
                    row.find('td:eq(3)').text(`${parts[2]}-${parts[1]}-${parts[0]}`);

                    // Falls die Länge des Arrays nicht 3 ist, wird der ursprüngliche String aus dem Formular genommen und direkt gesetzt
                } else {
                    row.find('td:eq(3)').text(data.endeLeasing);
                }
            } else {
                // Dieser Zweig wird ausgeführt, wenn data.endeLeasing leer ist (kein Datum ausgewählt)
                // Setzt die Tabellenzelle auf "Kein Datum vorhanden", damit der Nutzer sofort sieht, dass kein Datum eingetragen ist
                row.find('td:eq(3)').text('Kein Datum vorhanden');
            }
        });
    });


    // Wenn auf einen „Löschen“-Button in der Tabelle geklickt wird, startet dieser Event-Handler
    $('#geraeteTable').on('click', '.deleteButton', function () {

        // Sucht die Tabellenzeile und liest die ID & den Service Tag aus, damit im Bestätigungs-Modal angezeigt werden kann, was gelöscht werden soll
        const row = $(this).closest('tr');
        const id = row.find('td:eq(0)').text().trim();
        const serviceTag = row.find('td:eq(1)').text().trim();

        // Speichert die Zeile im Modal und füllt die Informationen hinein, damit der Benutzer weiß, was er gleich löscht
        $('#deleteModal').data('row', row);
        $('#delete_id').val(id);
        $('#deleteInfo').text(`Geräte-ID: ${id} | Service Tag: ${serviceTag}`);

        // Öffnet das Lösch-Bestätigungs-Modal
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    // Wenn im Löschmodal der „Löschen“-Button gedrückt wird, wird dieser Handler ausgeführt
    $('#confirmDelete').on('click', function () {

        // Holt sich die gespeicherte Geräte-ID und die Tabellenzeile zurück
        const id = $('#delete_id').val();
        const row = $('#deleteModal').data('row');

        /* Ruft deine CRUD-Funktion deleteRecord() auf
         * Diese sendet die ID an deleteGeraet.php (Server)
         * Wenn die Antwort success: true ist, blendet die Funktion die Zeile in der Tabelle aus und entfernt sie
         */
        deleteRecord('deleteGeraet.php', id, row, document.getElementById('deleteModal'));
    });

});

/* Datei "geraet.js"
 * Definieren einer anonymen Funktion 
 * => Diese wird nicht sofort ausgeführt, sondern als Parameter (onSuccess) an die Funktion updateRecord() übergeben
 * => Wenn das Update von updateRecord erfolgreich war, führt es die definierte Funktion aus
 * 
 * Datei "crud.js"
 * updateRecord() ruft die Funktion sendAjaxRequest() auf
 * => Diese übergibt eine weitere anonyme Callback - Funktion
 * => Diese wird von jQuery aufgerufen, wenn die Anfrage erfolgreich vom Server kommt
 * 
 * updateGeraet.php antwortet mit: { "success": true, "message": "Gerät erfolgreich aktualisiert." }
 * => jQuery ruft in sendAjaxRequest() die success-Callback auf
 * success: function(response) {
    if (response.success) {
        onSuccess(response);
    } else {
        showInfoModal('Fehler', response.message);
    }
}
 * onSuccess(response);
 * ist der Punkt, an dem die Callback-Funktion aus updateRecord() ausgelöst wird
 * function (response) {
    if (response.success) {
        => Wenn (in der vierten Parameterstelle) eine Callback-Funktion gesetzt wurde, wird sie nun ausgeführt
        if (onSuccess) onSuccess(response);
        ...
    }
}
 * Der Code springt zurück zur  ursprünglichen anonymen Funktion, die im updateRecord-Aufruf übergeben wurde:
 * function () {
    const selectedText = $('#editModellId option:selected').text();
    row.find('td:eq(1)').text(data.serviceTag);
    row.find('td:eq(2)').text(selectedText).data('id', data.modellId);

    const parts = data.endeLeasing.split('-');
    if (parts.length === 3) {
        row.find('td:eq(3)').text(`${parts[2]}-${parts[1]}-${parts[0]}`);
    }
}

[User klickt "Speichern"]
        ↓
$('#editForm').submit()  
        ↓
updateRecord(url, data, modal, [CALLBACK EINS])
        ↓
sendAjaxRequest(url, "POST", data, [CALLBACK ZWEI])
        ↓
Anfrage an updateGeraet.php
        ↓
Server antwortet mit { success: true, message: "..." }
        ↓
sendAjaxRequest → ruft CALLBACK ZWEI Callback-Funktion(response)
        ↓
updateRecord → prüft if(response.success)
        ↓
updateRecord → ruft CALLBACK EINS Callback-Funktion auf
        ↓
CALLBACK EINS Funktion aktualisiert die Tabelle im Frontend
 */


// loadModelle.js
/* Definiert eine Funktion namens loadModelle
 * Übergibt 2 Parameter:
 * $select: Ein jQuery-Objekt (also z. B. $('#modellSelect')), das das <select>-Element repräsentiert
 * selectedId: Optional; wenn übergeben, wird nach dem Laden dieser Eintrag vorausgewählt
 * => Standardwert ist null, falls nichts übergeben wird
 */
function loadModelle($select, selectedId = null) {
    // Das JavaScript schickt eine GET-Anfrage an die PHP-Datei modellUtility.php und übergibt den Query-Parameter "action=list"
    $.ajax({
        url: '../../includes/modell/modellUtility.php?action=list',
        // HTTP-Methode: GET
        type: 'GET',
        // Es wird eine json - Datei als Antwort erwartet
        dataType: 'json',

        // Callback, wenn HTTP 200 (oder erfolgreicher Status) und die Antwort korrekt geparst wurde
        // "modelle" ist der geparste JS-Wert; in diesem Fall wirdein Array von Objekten erwartet ( {id: ..., text: ...} )
        success: function (modelle) {

            /* Prüft, ob das empfangene Objekt das Feld "error" hat
             * Wenn ja, wird die Fehlermeldung in der Browserkonsole ausgegeben
             * Danach wird die success-Funktion beendet, damit der Code nicht versucht, z. B. modelle.forEach(...) auf einem Fehlerobjekt auszuführen
             */
            if (modelle.error) {
                console.error(modelle.error);
                return;
            }
            // Entfernt alle vorhandenen <option>-Elemente im Ziel-<select> => Sauberes Neuaufbauen
            $select.empty();
            // modelle ist das JSON-Array, das die PHP-Datei zurückgibt
            // Iteration über jedes Element im Array
            modelle.forEach(function (modell) {
                /* Erzeugt ein neues <option>-Element. Die Parameter für den Konstruktor Option(text, value, defaultSelected, selected) sind
                 *      => "modell.text" ist der sichtbare Text in der Dropdown-Liste
                 *      => "modell.id" is der Wert (value) der Option
                 *      => "false (defaultSelected)" definiert, ob die Option standardmäßig als selected markiert sein soll (bei Seitenstart)
                 *      => "false (selected)" definiert, ob sie jetzt direkt als ausgewählt hinzugefügt wird
                 *          ==> Hier beide false, weil Auswahl getrennt weiter unten passiert
                 * 
                 * Hinweis: new Option() ist natives DOM (kein jQuery)
                 *  => Anschließend wird es mit jQuery .append() in das <select> eingefügt
                 */
                let option = new Option(modell.text, modell.id, false, false);
                // Fügt die erzeugte Option in das jQuery-Select ein
                $select.append(option);
            });

            // Diese Bedingung prüft, ob das zurückgegebene Array leer ist — also keine Modelle in der Datenbank gefunden wurden
            if (!modelle.length) {
                $select.append(new Option('Keine Modelle gefunden', '', true, true));
            }
            // Wenn selectedId nicht null/undefined/0/'' ist, wird das <select> so gesetzt, dass die Option mit dem Wert "selectedId" ausgewählt wird
            if (selectedId) {
                // Wählt automatisch diese Option aus und informiert (z. B.) Select2, falls das Plugin verwendet wird
                $select.val(selectedId).trigger('change.select2');
            }
        },
        // Fehler-Callback, wenn Request fehlschlägt (z. B. Netzwerkfehler, HTTP 500, JSON-Parse-Fehler)
        error: function (xhr, status, err) {
            console.error("Fehler beim Laden der Modelle:", status, err);
        }
    });
}


// loadModelle für SELECT2 (mit Suchfunktion)
/* $select — erwartet ein jQuery-Objekt (z. B. $('#meineSelect'))
 *      => Das $ ist lediglich Konvention, kein spezieller Typ
 * selectedId = null
 *      => ES6 (ECMAScript 6) Default-Parameter: wenn kein selectedId übergeben wird, ist der Wert null
 */
function loadModelle($select, selectedId = null) {

    /* Prüft, ob das DOM-Element bereits von Select2 initialisiert wurde
     *  => Select2 fügt die Klasse "select2-hidden-accessible" zum ursprünglichen <select> hinzu
     *
     * Falls ja, wird select2('destroy') aufgerufen, um die alte Select2-Instanz zu entfernen
     *  => Dadurch werden doppelte Initialisierungen und Interferenzen verhindert 
     *      => z. B. wenn die Funktion mehrfach aufgerufen wird
     */
    if ($select.hasClass("select2-hidden-accessible")) {
        $select.select2('destroy');
    }

    /* ajax sagt Select2, dass die Liste per AJAX geladen werden soll
     * url legt den Endpoint fest, der die Modell-Daten liefert (modellUtility.php?action=list)
     * dataType: 'json' erwartet JSON zurück
     * delay: 250 Debounce: warte 250 ms nachdem der Benutzer tippt, bevor die Anfrage gesendet wird
     *      => verhindert viele Requests beim Tippen
     * data: function(params) baut die Query-Parameter für den Request
     *      => params.term ist der eingegebene Suchtext; hier wird er als search gesendet
     *      => Wenn params.term false ist, wird '' gesendet
     * 
     * processResults: function(data) Select2 erwartet die Ergebnisse in der Form { results: [...] }
     *      => Diese Funktion transformiert die empfangenen Daten in dieses Format
     * 
     * Zuerst wird geprüft, ob data.error vorhanden ist
     *      => Falls ja: Ausgabe in die Konsole und zurückgeben eines leeren Ergebnisses
     * Sonst wird { results: data } zurückgegeben
     *      => Das impliziert, dass data bereits ein Array von Items ist, wobei jedes Item idealerweise 
     * { id: ..., text: ... } enthält
     *
     * cache: true Browser/Select2 caches die Ergebnisse zur Performance
     */
    $select.select2({
        ajax: {
            url: '../../includes/modell/modellUtility.php?action=list',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term || ''
                };
            },
            processResults: function (data) {
                if (data.error) {
                    console.error(data.error);
                    return { results: [] };
                }

                return { results: data };
            },
            cache: true
        },
        /* placeholder setzt einen Platzhaltertext im Select2-Feld
         * minimumInputLength: 0 => bereits ohne Tippen werden Ergebnisse angefragt
         * width: '100%' => lässt das Select2-Element die volle Breite des Containers einnehmen
         * allowClear: true => zeigt ein „x“ zum Zurücksetzen des Wertes (funktioniert nur, wenn placeholder definiert ist)
         * dropdownParent: $('#editGeraetModal') Weist Select2 an, das Dropdown in diesem Container (z. B. Bootstrap-Modal) zu 
         *      => Wenn es kein Modal gibt wird das Dropdown im body gerendert
         * rendern, damit es nicht hinter dem Modal liegt (z-Index Problematik)
         */
        placeholder: 'Modell suchen oder auswählen...',
        minimumInputLength: 0,
        width: '100%',
        allowClear: true,
        dropdownParent: ($select.closest('.modal').length > 0)
            ? $select.closest('.modal')
            : $(document.body),
        // language überschreibt standardmäßige Texte von Select2
        language: {
            noResults: function () {
                return "Keine Modelle gefunden";
            },
            searching: function () {
                return "Suche...";
            }
        }
    });

    /* Prüft, ob selectedId true ist (nicht null, undefined, 0, '', false)
     *  => Nur wenn ein selectedId vorhanden ist, wird der nachfolgende AJAX-Aufruf gestartet
     */
    if (selectedId) {
        /* Ruft die Funktion sendAjaxRequest auf
         * Parameter 1: URL zum Endpoint, hier modellUtility.php?action=list
         * Parameter 2: HTTP-Methode 'GET'
         * Parameter 3: Daten-Payload (hier ein leeres Objekt {})
         */
        sendAjaxRequest(
            '../../includes/modell/modellUtility.php?action=list',
            'GET',
            {},

            /* OnSuccess-Callback, der ausgeführt wird, wenn sendAjaxRequest erfolgreich geparstes JSON zurückliefert (und success-Logik stimmt)
             * response ist das Objekt, das sendAjaxRequest an onSuccess übergibt
             */
            function (response) {

                /* Extrahiert aus der Server-Antwort das data-Feld in die Variable modelle
                 * Falls response.data undefined/null ist, wird ein leeres Array [] verwendet, damit der Code nicht mit undefined.find abstürzt
                 */
                const modelle = response.data || [];

                // Sucht im Array modelle das Objekt, dessen id mit selectedId übereinstimmt
                const selectedModell = modelle.find(m => m.id === selectedId);
                // Prüft, ob ein Modell gefunden wurde (nicht undefined) => Nur dann wird die Option erstellt und gesetzt
                if (selectedModell) {
                    /* Erstellt ein natives DOM-Option-Objekt (<option>)
                     * Parameter (in Reihenfolge):
                     *  => selectedModell.text ist der sichtbare Text der Option
                     *  => selectedModell.id ist der Wert (value) der Option
                     *  => true steht für defaultSelected (legt die Option als standardmäßig ausgewählt fest)
                     *  => true steht für selected (markiert die Option aktiv als ausgewählt)
                     *      => Durch die Verwendung von new Option(...) wird die Option korrekt vom <select>/Select2 erkannt
                     */
                    const option = new Option(
                        selectedModell.text,
                        selectedModell.id,
                        true,
                        true
                    );
                    /* Hängt die neu erstellte <option> an das jQuery-Objekt $select (<select id="...">)
                     * trigger('change') informiert Select2/jQuery darüber, dass sich der Wert geändert hat
                     *  => Dadurch aktualisiert sich die Anzeige des Select2-Widgets und zeigt die ausgewählte Option an
                     *  => Wichtig: Ohne trigger('change') kann Select2 die Änderung nicht sofort anzeigen
                     */
                    $select.append(option).trigger('change');
                }
            },
            // OnError-Callback, der ausgeführt wird, falls sendAjaxRequest im Fehlerfall (error-Handler) gerufen wird
            function (xhr, status, error) {
                console.error("Fehler beim Laden des ausgewählten Modells:", status, error);
            }
        );
    }
}