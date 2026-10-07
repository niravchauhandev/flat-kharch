/**
 * Flat Member Management JS Module
 */
var User = function () {
    var handlePasswordToggle = function () {
        $('#toggleMemberPassword').on('click', function () {
            var input = $('#member_password');
            var icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-regular fa-eye').addClass('fa-solid fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-solid fa-eye-slash').addClass('fa-regular fa-eye');
            }
        });
    };

    var handleStatusToggle = function () {
        $('.status-toggle-btn').on('click', function () {
            var btn = $(this);
            var userId = btn.data('id');

            $.ajax({
                type: 'POST',
                url: baseurl + 'user-change-status',
                data: { id: userId },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showToster('success', response.message);
                        if (response.new_status === 1) {
                            btn.html('<span class="badge badge-light-success text-success fw-semibold px-2 py-1">Active</span>');
                        } else {
                            btn.html('<span class="badge badge-light-danger text-danger fw-semibold px-2 py-1">Inactive</span>');
                        }
                    } else {
                        showToster('error', response.message);
                    }
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error updating status';
                    showToster('error', msg);
                }
            });
        });
    };

    var handleFormSubmit = function (formId) {
        $(formId).on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('.submitbtn');
            var formData = new FormData(this);

            submitBtn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                    if (response.status === 'success') {
                        showToster('success', response.message);
                        setTimeout(function () {
                            window.location.href = response.redirect || baseurl + 'user';
                        }, 800);
                    } else {
                        showToster('error', response.message);
                    }
                },
                error: function (xhr) {
                    submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                    var msg = 'An error occurred during submission.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                        msg = xhr.responseJSON.errors[firstKey][0];
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToster('error', msg);
                }
            });
        });
    };

    return {
        init: function () {
            handleStatusToggle();
        },
        initAdd: function () {
            handlePasswordToggle();
            handleFormSubmit('#add-user-form');
        },
        initEdit: function () {
            handlePasswordToggle();
            handleFormSubmit('#edit-user-form');
        },
        deleteMember: function (deleteUrl, memberName) {
            deleteConfirm(null, function () {
                $.ajax({
                    type: 'POST',
                    url: deleteUrl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showToster('success', response.message);
                            setTimeout(function () {
                                window.location.reload();
                            }, 800);
                        } else {
                            showToster('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Error deleting member';
                        showToster('error', msg);
                    }
                });
            });
        }
    };
}();
