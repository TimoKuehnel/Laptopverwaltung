$(document).ready(function () {
    $('.dataTable').each(function() {
        $(this).DataTable({
            language: languageDE,
            responsive: true,
            dom: 'Blfrtip',
            buttons: ['copy', 'excel', 'pdf']
        });
    });
});

// DOM -> Anpassen durch HTML Tags inkl. Klassen (Frontend)
