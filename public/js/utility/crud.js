function createRecord(url, formSelector, onSuccess) {
    const form = $(formSelector);
    const data = form.serialize();

    sendAjaxRequest(url, 'POST', data, function (response) {
        showInfoModal('Erfolg', response.message);
        form[0].reset();
        if (onSuccess) {
            onSuccess(response);
        }
    });
}


function updateRecord(url, data, modalElement, onSuccess) {
    sendAjaxRequest(url, 'POST', data, function (response) {
        if (response.success) {
            if (onSuccess) {
                onSuccess(response);
            }
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
            //showInfoModal('Erfolg', response.message);
        } else {
            showInfoModal('Fehler', response.message);
        }
    });
}


function deleteRecord(url, id, row, modalElement, onSuccess) {
    sendAjaxRequest(url, 'POST', { id: id }, function (response) {
        if (response.success) {
            const table = $(row).closest('table').DataTable();
            $(row).fadeOut(400, function () {
                table.row(row).remove().draw(false);
            });

            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();

            showInfoModal('Erfolg', response.message);

            if (onSuccess) onSuccess(response);
        } else {
            showInfoModal('Fehler', response.message);
        }
    });
}

