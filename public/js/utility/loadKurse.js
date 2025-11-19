function loadKurse($select, selectedId = null) {

    if ($select.hasClass("select2-hidden-accessible")) {
        $select.select2('destroy');
    }

    $select.select2({
        dropdownParent: ($select.closest('.modal').length > 0)
            ? $select.closest('.modal')
            : $(document.body),

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
            noResults: function () { return "Kein Kurs gefunden"; },
            searching: function () { return "Suche..."; },
            errorLoading: function () { return "Fehler beim Laden der Ergebnisse"; },
            inputTooShort: function () { return "Bitte Suchbegriff eingeben..."; }
        }
    });

    if (selectedId) {

        $.ajax({
            url: 'kurs/kursUtility.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {

                const kurs = data.find(k => k.id == selectedId);
                if (!kurs) return;

                const option = new Option(
                    kurs.text,
                    kurs.id,
                    true,
                    true
                );

                $select.append(option).trigger('change');
            },
            error: function (xhr, status, error) {
                console.error("Fehler beim Laden des ausgewählten Kurses:", status, error);
            }
        });
    }
}