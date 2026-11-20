/**
 * Flat-Kharch: Monthly Pool & Advance Fund Management Module
 */
var MonthlyPool = (function () {
    var searchQuery = '';
    var searchTimer = null;
    var currentPage = 1;
    var pageSize = 10;
    var currentPoolIdForModal = null;

    var getAjaxBaseUrl = function () {
        var base = typeof baseurl !== 'undefined' ? baseurl : '/';
        return base.endsWith('/') ? base : base + '/';
    };

    /**
     * Fetch and render server-side datatable rows with pagination
     */
    var loadDatatable = function (page) {
        currentPage = page || 1;
        var tableBody = $('#monthly_pool_table tbody');
        tableBody.html('<tr><td colspan="7" class="text-center py-6 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>Loading monthly pool data...</td></tr>');

        $.ajax({
            type: 'POST',
            url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
            data: {
                action: 'getdatatable',
                page: currentPage,
                limit: pageSize,
                q: searchQuery,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (!response || !response.data || response.data.length === 0) {
                    tableBody.html('<tr><td colspan="7" class="text-center py-8 text-muted">No monthly pool cycles found.</td></tr>');
                    $('#monthly_pool_info').text('Showing 0 to 0 of 0 entries');
                    $('#monthly_pool_pages').empty();
                    return;
                }

                var rowsHtml = '';
                $.each(response.data, function (index, columns) {
                    rowsHtml += '<tr>';
                    $.each(columns, function (colIndex, cellHtml) {
                        rowsHtml += '<td>' + cellHtml + '</td>';
                    });
                    rowsHtml += '</tr>';
                });

                tableBody.html(rowsHtml);

                // Render pagination info and controls
                var total = response.total || 0;
                var start = (currentPage - 1) * pageSize + 1;
                var end = Math.min(currentPage * pageSize, total);
                $('#monthly_pool_info').text('Showing ' + start + ' to ' + end + ' of ' + total + ' entries');

                renderPaginationControls(total);
            },
            error: function () {
                tableBody.html('<tr><td colspan="7" class="text-center py-6 text-danger">Failed to load monthly pool records. Please try again.</td></tr>');
            },
        });
    };

    /**
     * Render page buttons for pagination
     */
    var renderPaginationControls = function (total) {
        var container = $('#monthly_pool_pages');
        container.empty();

        var totalPages = Math.ceil(total / pageSize);
        if (totalPages <= 1) {
            return;
        }

        var prevDisabled = currentPage === 1 ? 'disabled' : '';
        var prevBtn = $('<button type="button" class="btn btn-sm btn-icon btn-light ' + prevDisabled + '"><i class="ki-outline ki-left fs-6"></i></button>');
        prevBtn.on('click', function () {
            if (currentPage > 1) {
                loadDatatable(currentPage - 1);
            }
        });
        container.append(prevBtn);

        for (var p = 1; p <= totalPages; p++) {
            (function (pageNum) {
                var activeClass = pageNum === currentPage ? 'btn-primary' : 'btn-light';
                var pageBtn = $('<button type="button" class="btn btn-sm btn-icon ' + activeClass + '">' + pageNum + '</button>');
                pageBtn.on('click', function () {
                    loadDatatable(pageNum);
                });
                container.append(pageBtn);
            })(p);
        }

        var nextDisabled = currentPage === totalPages ? 'disabled' : '';
        var nextBtn = $('<button type="button" class="btn btn-sm btn-icon btn-light ' + nextDisabled + '"><i class="ki-outline ki-right fs-6"></i></button>');
        nextBtn.on('click', function () {
            if (currentPage < totalPages) {
                loadDatatable(currentPage + 1);
            }
        });
        container.append(nextBtn);
    };

    /**
     * Setup search box listener with 250ms debounce
     */
    var handleSearch = function () {
        $('body').on('input keyup', '#q-monthly-pool', function () {
            searchQuery = ($(this).val() || '').trim();
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                loadDatatable(1);
            }, 250);
        });
    };

    /**
     * Handle modal forms validation and AJAX submit
     */
    var setupFormValidation = function (formSelector, modalSelector) {
        var form = $(formSelector);
        if (!form.length) return;

        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            errorPlacement: function (error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    var $select2 = element.next('.select2-container');
                    if ($select2.length) {
                        error.insertAfter($select2);
                    } else {
                        error.insertAfter(element);
                    }
                } else if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('error');
            },
            unhighlight: function (element) {
                $(element).removeClass('error');
            },
            submitHandler: function (formElement) {
                var submitBtn = $(formElement).find('.submitbtn');
                submitBtn.attr('data-kt-indicator', 'on').prop('disabled', true);

                $.ajax({
                    type: $(formElement).attr('method') || 'POST',
                    url: $(formElement).attr('action'),
                    data: $(formElement).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);

                        if (response.status === 'success') {
                            showToster('success', response.message);
                            $(modalSelector).modal('hide');
                            loadDatatable(currentPage);
                        } else {
                            showToster('error', response.message || 'Operation failed.');
                        }
                    },
                    error: function (xhr) {
                        submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                        var errorMsg = 'An error occurred during submission.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                            errorMsg = xhr.responseJSON.errors[firstKey][0];
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        showToster('error', errorMsg);
                    },
                });
                return false;
            },
        });
    };

    /**
     * Handle modal triggers (Add, Edit, Contributions, Payments, Delete)
     */
    var handleModals = function () {
        // Open Add Modal
        $('body').on('click', '.add-monthly-pool', function () {
            $.ajax({
                type: 'POST',
                url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                data: { action: 'add-monthly-pool' },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (data) {
                    $('.append-monthly-pool-data-add').html(data);
                    $('#add_monthly_pool_modal').modal('show');
                    setupFormValidation('#add-save-monthly-pool', '#add_monthly_pool_modal');
                },
            });
        });

        // Open Edit Modal
        $('body').on('click', '.edit-monthly-pool', function () {
            var poolId = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                data: { action: 'edit-monthly-pool', id: poolId },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (data) {
                    $('.append-monthly-pool-data-edit').html(data);
                    $('#edit_monthly_pool_modal').modal('show');
                    setupFormValidation('#edit-save-monthly-pool', '#edit_monthly_pool_modal');
                },
            });
        });

        // Open View Member Contributions Modal
        $('body').on('click', '.view-contributions', function () {
            var poolId = $(this).data('id');
            currentPoolIdForModal = poolId;
            openContributionsModal(poolId);
        });

        // Open Record Payment Modal
        $('body').on('click', '.record-payment-btn', function () {
            var contributionId = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                data: { action: 'record-payment-modal', id: contributionId },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (data) {
                    $('.append-record-payment-modal-data').html(data);
                    $('#record_payment_modal').modal('show');
                    setupRecordPaymentValidation();
                },
            });
        });

        // Open Delete Confirmation Modal
        $('body').on('click', '.delete-monthly-pool', function () {
            var poolId = $(this).data('id');
            $('#delete_pool_id').val(poolId);
            $('#confirm_delete_modal').modal('show');
        });

        // Confirm Delete Button Click
        $('body').on('click', '.confirm-delete-btn', function () {
            var poolId = $('#delete_pool_id').val();
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                data: {
                    action: 'common-monthly-pool',
                    id: poolId,
                    type: 3, // Logically Delete
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    $('#confirm_delete_modal').modal('hide');
                    if (response.status === 'success') {
                        showToster('success', response.message);
                        loadDatatable(currentPage);
                    } else {
                        showToster('error', response.message);
                    }
                },
                error: function () {
                    btn.prop('disabled', false);
                    showToster('error', 'Failed to delete monthly pool.');
                },
            });
        });

        // Send WhatsApp Reminder
        $('body').on('click', '.send-whatsapp-reminder', function () {
            var contributionId = $(this).data('id');
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                data: {
                    action: 'whatsapp-reminder',
                    id: contributionId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    btn.prop('disabled', false);
                    if (response.status === 'success' && response.url) {
                        window.open(response.url, '_blank');
                    } else {
                        showToster('error', response.message || 'Unable to generate WhatsApp reminder link.');
                    }
                },
                error: function () {
                    btn.prop('disabled', false);
                    showToster('error', 'Error generating WhatsApp link.');
                },
            });
        });

        // Modal Close: Form and Error Reset Standard
        $('.modal').on('hidden.bs.modal', function () {
            var form = $(this).find('form');
            if (form.length) {
                form[0].reset();
                form.find('span.error, label.error, .help-block-error').remove();
                form.find('.error').removeClass('error');
                form.find('.submitbtn').removeAttr('data-kt-indicator').prop('disabled', false);
            }
        });
    };

    /**
     * Open or refresh contributions modal
     */
    var openContributionsModal = function (poolId) {
        $.ajax({
            type: 'POST',
            url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
            data: { action: 'view-contributions', id: poolId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                $('.append-contributions-modal-data').html(data);
                $('#view_contributions_modal').modal('show');
            },
        });
    };

    /**
     * Setup payment recording form validation
     */
    var setupRecordPaymentValidation = function () {
        var form = $('#save-contribution-payment-form');
        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            submitHandler: function (formElement) {
                var submitBtn = $(formElement).find('.submitbtn');
                submitBtn.attr('data-kt-indicator', 'on').prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: getAjaxBaseUrl() + 'monthly-pool-ajaxcall',
                    data: $(formElement).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                        if (response.status === 'success') {
                            showToster('success', response.message);
                            $('#record_payment_modal').modal('hide');

                            // Refresh both contributions modal and datatable
                            if (currentPoolIdForModal) {
                                openContributionsModal(currentPoolIdForModal);
                            }
                            loadDatatable(currentPage);
                        } else {
                            showToster('error', response.message || 'Payment recording failed.');
                        }
                    },
                    error: function (xhr) {
                        submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                        var msg = 'Error processing payment.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showToster('error', msg);
                    },
                });
                return false;
            },
        });
    };

    return {
        init: function () {
            loadDatatable(1);
            handleSearch();
            handleModals();
        },
        reload: function () {
            loadDatatable(currentPage);
        },
        refreshContributionsModal: function (poolId) {
            if (poolId) {
                openContributionsModal(poolId);
            }
        },
    };
})();
