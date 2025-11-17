function loadModelle($select, selectedId = null) {

    if ($select.hasClass("select2-hidden-accessible")) {
        $select.select2('destroy');
    }

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
        placeholder: 'Modell suchen oder auswählen...',
        minimumInputLength: 0,
        width: '100%',
        allowClear: true,
        dropdownParent: ($select.closest('.modal').length > 0)
            ? $select.closest('.modal')
            : $(document.body),
        language: {
            noResults: function () {
                return "Keine Modelle gefunden";
            },
            searching: function () {
                return "Suche...";
            }
        }
    });

    if (selectedId) {
        sendAjaxRequest(
            '../../includes/modell/modellUtility.php?action=list',
            'GET',
            {},

            function (response) {

                const modelle = response.data || [];

                const selectedModell = modelle.find(m => m.id == selectedId);
                if (selectedModell) {
                    const option = new Option(
                        selectedModell.text,
                        selectedModell.id,
                        true,
                        true
                    );
                    $select.append(option).trigger('change');
                }
            },

            function (xhr, status, error) {
                console.error("Fehler beim Laden des ausgewählten Modells:", status, error);
            }
        );
    }
}
