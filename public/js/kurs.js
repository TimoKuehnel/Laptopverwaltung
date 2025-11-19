$(document).ready(function () {
    // Insert 
    $('#insertKursForm').on('submit', function (e) {
        e.preventDefault();
        createRecord('insertOrUpdateKurs.php', '#insertKursForm');
    });


    // Update
    $('#kursTable').on('click', '.editButton', function () {
        let row = $(this).closest('tr');
        $('#editFormKurs').data('row', row);

        let id = row.find('td:eq(0)').text().trim();
        let kursnummer = row.find('td:eq(1)').text().trim();
        let kuerzel = row.find('td:eq(2)').text().trim();
        let beginn = row.find('td:eq(3)').text().trim();
        let ende = row.find('td:eq(4)').text().trim();

        let partsBeginn = beginn.split('-');
        if (partsBeginn.length === 3) {
            beginn = `${partsBeginn[2]}-${partsBeginn[1]}-${partsBeginn[0]}`;
        }

        let partsEnde = ende.split('-');
        if (partsEnde.length === 3) {
            ende = `${partsEnde[2]}-${partsEnde[1]}-${partsEnde[0]}`;
        }

        $('#editId').val(id);
        $('#editKursnummer').val(kursnummer);
        $('#editKuerzel').val(kuerzel);
        $('#editBeginn').val(beginn);
        $('#editEnde').val(ende);


        new bootstrap.Modal(document.getElementById('editKursModal')).show();
    });

    $('#editFormKurs').on('submit', function (e) {
        e.preventDefault();

        const row = $(this).data('row');
        const data = {
            id: $('#editId').val(),
            kursnummer: $('#editKursnummer').val(),
            kuerzel: $('#editKuerzel').val(),
            beginn: $('#editBeginn').val(),
            ende: $('#editEnde').val()
        };

        updateRecord('insertOrUpdateKurs.php', data, document.getElementById('editKursModal'), function (response) {
            row.find('td:eq(0)').text(data.id);
            row.find('td:eq(1)').text(data.kursnummer);
            row.find('td:eq(2)').text(data.kuerzel);
            row.find('td:eq(3)').text(response.beginnStr);
            row.find('td:eq(4)').text(response.endeStr);
        });
    });

    // Delete
    $('#kursTable').on('click', '.deleteButton', function () {
        const row = $(this).closest('tr');
        const id = row.find('td:eq(0)').text().trim();
        const kursnummer = row.find('td:eq(1)').text().trim();

        $('#deleteKursModal').data('row', row);
        $('#delete_id').val(id);
        $('#deleteInfo').text(`Kurs-ID: ${id} | Kursnummer: ${kursnummer}`);

        new bootstrap.Modal(document.getElementById('deleteKursModal')).show();
    });

    $('#confirmDeleteKurs').on('click', function () {
        const id = $('#delete_id').val();
        const row = $('#deleteKursModal').data('row');
        deleteRecord('deleteKurs.php', id, row, document.getElementById('deleteKursModal'));
    });
});

// Alle Kurse laden
$(document).ready(function () {

    $('#kursModal').on('shown.bs.modal', function () {
        loadKurse($('#kursSelect'));
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