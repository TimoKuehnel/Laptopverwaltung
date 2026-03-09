$(document).ready(function () {
    $('.dataTable').each(function () {
        $(this).DataTable({
            language: languageDE,
            responsive: true,
            dom: 'Blfrtip',
            buttons: ['copy', 'excel', 'pdf'],
            columnDefs: [
                { targets: '_all', className: 'dt-left' }
            ]
        });
    });
});

// DOM -> Anpassen durch HTML Tags inkl. Klassen (Frontend)
