function sendAjaxRequest(url, method, data, onSuccess, onError) {
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: 'json',
        success: function(response) {

            if (Array.isArray(response)) {
                onSuccess({ success: true, data: response });
                return;
            }

            if (response.success) {
                onSuccess(response);
            } else {
                showInfoModal('Fehler', response.message || 'Unbekannter Fehler.');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX-Fehler:", status, error);
            if (onError) onError(xhr, status, error);
            else showInfoModal('Fehler', 'Es ist ein unbekannter Fehler aufgetreten.');
        }
    });
}
