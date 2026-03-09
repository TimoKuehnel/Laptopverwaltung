function showInfoModal(title, message) {
    $('#infoModalTitle').text(title);
    $('#infoModalBody').text(message);
    const modal = new bootstrap.Modal(document.getElementById('infoModal'));
    modal.show();
}