/**
 * Login Module JS
 */
var Login = function () {
    var handleLoginForm = function () {
        // Toggle password visibility
        $('#togglePasswordBtn').on('click', function () {
            var passwordInput = $('#password');
            var icon = $('#togglePasswordIcon');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-regular fa-eye').addClass('fa-solid fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-solid fa-eye-slash').addClass('fa-regular fa-eye');
            }
        });

        // Form submission via AJAX
        $('#login-form').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('.submitbtn');
            var email = $('#email').val().trim();
            var password = $('#password').val();

            if (!email || !password) {
                showToster('warning', 'Please enter email/phone and password.');
                return false;
            }

            submitBtn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                    if (response.status === 'success') {
                        showToster('success', response.message);
                        setTimeout(function () {
                            window.location.href = response.redirect || '/';
                        }, 800);
                    } else {
                        showToster('error', response.message || 'Login failed.');
                    }
                },
                error: function (xhr) {
                    submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
                    var msg = 'Invalid credentials or server error.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToster('error', msg);
                }
            });
        });
    };

    return {
        init: function () {
            handleLoginForm();
        }
    };
}();
