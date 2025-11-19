$(document).ready(function () {

    $('#klasseSelect').select2({
        dropdownParent: $('#klasseModal'),
        ajax: {
            url: 'kurs/kursUtility.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search: params.term || '' };
            },
            processResults: function (data) {
                return { results: data };
            }
        },
        placeholder: "Klasse auswählen...",
        minimumInputLength: 0,
        allowClear: true,
        width: "100%",
        language: {
            noResults: function () {
                return "Keine Klassen gefunden";
            },
            searching: function () {
                return "Suche...";
            },
            errorLoading: function () {
                return "Fehler beim Laden der Ergebnisse";
            },
            inputTooShort: function () {
                return "Bitte Suchbegriff eingeben...";
            }
        }
    });

    
    $('#klasseWeiterBtn').on('click', function () {
        const klasseId = $('#klasseSelect').val();

        if (!klasseId) {
            alert("Bitte eine Klasse auswählen!");
            return;
        }

        window.location.href = "klasseTeilnehmer.php?klasse=" + klasseId;
    });

});