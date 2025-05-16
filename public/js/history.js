document.addEventListener('DOMContentLoaded', function () {
    var currentTab = localStorage.getItem('borrowHistoryTab');
    if (currentTab && document.getElementById(currentTab)) {
        var tab = new bootstrap.Tab(document.getElementById(currentTab));
        tab.show();
    }
    document.querySelectorAll('#historyTabs .nav-link').forEach(function (link) {
        link.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem('borrowHistoryTab', e.target.id);
        });
    });
});
