function validateForm() {
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.value) {
        Swal.fire({
            icon: 'error',
            title: '<?= translate('error', $language); ?>',
            text: '<?= translate('select_image', $language); ?>',
        });
        return false;
    }
    return true;
}

function copyURL(url) {
    var textArea = document.createElement('textarea');
    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    Swal.fire({
        icon: 'success',
        title: 'URL <?= translate('copied', $language); ?>!',
        text: '<?= translate('url_copied', $language); ?>',
        confirmButtonText: 'OK'
    });
}
