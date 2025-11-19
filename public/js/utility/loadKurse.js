$(document).ready(function () {

    $('#kursSelect').select2({
        dropdownParent: $('#kursModal'),
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
        placeholder: "Kurs auswählen...",
        minimumInputLength: 0,
        allowClear: true,
        width: "100%",
        language: {
            noResults: function () {
                return "Kein Kurs gefunden";
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

    
    $('#kursWeiterBtn').on('click', function () {
        const kursId = $('#kursSelect').val();

        if (!kursId) {
            showInfoModal('Fehler', 'Bitte einen Kurs auswählen.');
            return;
        }

        window.location.href = "teilnehmer/kursTeilnehmerView.php?kurs=" + kursId;
    });

});