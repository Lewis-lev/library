document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        var alertElement = document.getElementById('success-alert');
        if (alertElement) {
            // Try Bootstrap 5 programmatically
            if (window.bootstrap && window.bootstrap.Alert && typeof window.bootstrap.Alert.getOrCreateInstance === 'function') {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
                bsAlert.close();
            } else {
                // fallback: just hide
                alertElement.classList.remove('show');
                alertElement.style.display = 'none';
            }
        }
    }, 5000);
});
