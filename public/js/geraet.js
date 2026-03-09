$(document).ready(function () {

    loadModelle($('#insertModellId'));

    // Insert 
    $('#insertGeraetForm').on('submit', function (e) {
        e.preventDefault();
        createRecord('insertOrUpdateGeraet.php', '#insertGeraetForm');
    });


    // Update
    $('#geraeteTable').on('click', '.editButton', function () {
        let row = $(this).closest('tr');
        $('#editGeraetForm').data('row', row);

        let id = row.find('td:eq(0)').text().trim();
        let serviceTag = row.find('td:eq(1)').text().trim();
        let modellId = row.find('td:eq(2)').data('id');
        let endeLeasing = row.find('td:eq(3)').text().trim();

        let parts = endeLeasing.split('-');
        if (parts.length === 3) {
            endeLeasing = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        $('#editId').val(id);
        $('#editServiceTag').val(serviceTag);
        $('#editEndeLeasing').val(endeLeasing);
        loadModelle($('#editModellId'), modellId);

        new bootstrap.Modal(document.getElementById('editGeraetModal')).show();
    });

    $('#editGeraetForm').on('submit', function (e) {
        e.preventDefault();

        const row = $(this).data('row');
        const data = {
            id: $('#editId').val(),
            serviceTag: $('#editServiceTag').val(),
            modellId: $('#editModellId').val(),
            endeLeasing: $('#editEndeLeasing').val()
        };

        updateRecord('insertOrUpdateGeraet.php', data, document.getElementById('editGeraetModal'), function (response) {
            const selectedText = $('#editModellId option:selected').text();
            row.find('td:eq(1)').text(data.serviceTag);
            row.find('td:eq(2)').text(selectedText).data('id', data.modellId);

            row.find('td:eq(3)').text(response.endeLeasingStr);
        });
    });

    // Delete
    $('#geraeteTable').on('click', '.deleteButton', function () {
        const row = $(this).closest('tr');
        const id = row.find('td:eq(0)').text().trim();
        const serviceTag = row.find('td:eq(1)').text().trim();

        $('#deleteGeraetModal').data('row', row);
        $('#delete_id').val(id);
        $('#deleteInfo').text(`Geräte-ID: ${id} | Service Tag: ${serviceTag}`);

        new bootstrap.Modal(document.getElementById('deleteGeraetModal')).show();
    });

    $('#confirmDeleteGeraet').on('click', function () {
        const id = $('#delete_id').val();
        const row = $('#deleteGeraetModal').data('row');
        deleteRecord('deleteGeraet.php', id, row, document.getElementById('deleteGeraetModal'));
    });
});