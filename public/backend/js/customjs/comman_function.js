/**
 * Flat-Kharch - Common Utility Functions
 * Follows standard VC enterprise structure
 */

function ajaxcall(url, data, callback) {
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function (result) {
            callback(result);
        },
        error: function (xhr, status, error) {
            var errorMessage = "An error occurred while processing your request.";
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                errorMessage = xhr.responseJSON.errors[firstKey][0];
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showToster("error", errorMessage);
            $(".submitbtn:visible").removeAttr("disabled").attr("data-kt-indicator", "off");
        }
    });
}

function showToster(status, message) {
    if (typeof toastr === "undefined") {
        alert(message);
        return;
    }

    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: "slideDown",
        timeOut: 3000
    };

    if (status === "success") {
        toastr.success(message, "Success");
    } else if (status === "error") {
        toastr.error(message, "Error");
    } else if (status === "warning") {
        toastr.warning(message, "Warning");
    } else {
        toastr.info(message, "Info");
    }
}

function showSwalAlert(title, message, icon) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: title || "Notification",
            text: message,
            icon: icon || "info",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    } else {
        alert(message);
    }
}

function deleteConfirm(deleteUrl, callback) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to revert this action!",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light"
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                if (typeof callback === "function") {
                    callback();
                } else if (deleteUrl) {
                    window.location.href = deleteUrl;
                }
            }
        });
    } else {
        if (confirm("Are you sure you want to proceed?")) {
            if (typeof callback === "function") callback();
            else if (deleteUrl) window.location.href = deleteUrl;
        }
    }
}
