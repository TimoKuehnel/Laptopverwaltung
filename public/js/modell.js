$(document).ready(function () {
    // Insert 
    $('#insertModellForm').on('submit', function (e) {
        e.preventDefault();
        createRecord('insertOrUpdateModell.php', '#insertModellForm');
    });


    // Update
    $('#modellTable').on('click', '.editButton', function () {
        let row = $(this).closest('tr');
        $('#editFormModell').data('row', row);

        let id = row.find('td:eq(0)').text().trim();
        let modellbezeichnung = row.find('td:eq(1)').text().trim();

        $('#editId').val(id);
        $('#editModellbezeichnung').val(modellbezeichnung);

        new bootstrap.Modal(document.getElementById('editModellModal')).show();
    });

    $('#editFormModell').on('submit', function (e) {
        e.preventDefault();

        const row = $(this).data('row');
        const data = {
            id: $('#editId').val(),
            modellbezeichnung: $('#editModellbezeichnung').val(),
        };

        updateRecord('insertOrUpdateModell.php', data, document.getElementById('editModellModal'), function (response) {
            row.find('td:eq(0)').text(data.id);
            row.find('td:eq(1)').text(data.modellbezeichnung);
        });
    });


    // Delete
    $('#modellTable').on('click', '.deleteButton', function () {
        const row = $(this).closest('tr');
        const id = row.find('td:eq(0)').text().trim();
        const modellbezeichnung = row.find('td:eq(1)').text().trim();

        $('#deleteModellModal').data('row', row);
        $('#delete_id').val(id);
        $('#deleteInfo').text(`Modell-ID: ${id} | Modell - Bezeichnung: ${modellbezeichnung}`);

        new bootstrap.Modal(document.getElementById('deleteModellModal')).show();
    });

    $('#confirmDeleteModell').on('click', function () {
        const id = $('#delete_id').val();
        const row = $('#deleteModellModal').data('row');
        deleteRecord('deleteModell.php', id, row, document.getElementById('deleteModellModal'));
    });
});