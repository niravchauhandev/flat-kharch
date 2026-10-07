/**
 * Dashboard Module JS
 */
var Dashboard = function () {
    var initWidgets = function () {
        // Initialize tooltips and widgets
        if (typeof $('[data-bs-toggle="tooltip"]').tooltip === 'function') {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    };

    return {
        init: function () {
            initWidgets();
        }
    };
}();
