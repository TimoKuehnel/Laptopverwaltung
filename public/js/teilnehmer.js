$(document).ready(function () {
    // Insert 
    $('#insertTeilnehmerForm').on('submit', function (e) {
        e.preventDefault();
        createRecord('insertOrUpdateTeilnehmer.php', '#insertTeilnehmerForm');
    });


    // Update
    $('#teilnehmerTable').on('click', '.editButton', function () {
        let row = $(this).closest('tr');
        $('#editFormTeilnehmer').data('row', row);

        let id = row.find('td:eq(0)').text().trim();
        let vorname = row.find('td:eq(1)').text().trim();
        let nachname = row.find('td:eq(2)').text().trim();
        let kursId = row.find('td:eq(3)').text().trim();

        $('#editId').val(id);
        $('#editVorname').val(vorname);
        $('#editNachname').val(nachname);

        new bootstrap.Modal(document.getElementById('editTeilnehmerModal')).show();
    });

    $('#editFormTeilnehmer').on('submit', function (e) {
        e.preventDefault();

        const row = $(this).data('row');
        const data = {
            id: $('#editId').val(),
            vorname: $('#editVorname').val(),
            nachname: $('#editNachname').val()
        };

        updateRecord('insertOrUpdateTeilnehmer.php', data, document.getElementById('editTeilnehmerModal'), function (response) {
            row.find('td:eq(0)').text(data.id);
            row.find('td:eq(1)').text(data.vorname);
        });
    });


    // Delete
    $('#TeilnehmerTable').on('click', '.deleteButton', function () {
        const row = $(this).closest('tr');
        const id = row.find('td:eq(0)').text().trim();
        const Teilnehmerbezeichnung = row.find('td:eq(1)').text().trim();

        $('#deleteTeilnehmerModal').data('row', row);
        $('#delete_id').val(id);
        $('#deleteInfo').text(`Teilnehmer-ID: ${id} | Teilnehmer - Bezeichnung: ${Teilnehmerbezeichnung}`);

        new bootstrap.Modal(document.getElementById('deleteTeilnehmerModal')).show();
    });

    $('#confirmDeleteTeilnehmer').on('click', function () {
        const id = $('#delete_id').val();
        const row = $('#deleteTeilnehmerModal').data('row');
        deleteRecord('deleteTeilnehmer.php', id, row, document.getElementById('deleteTeilnehmerModal'));
    });
});