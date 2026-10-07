

//  FOR EMPLOYEES
var ValidEmployees = (function () {
    "use strict";
    var add_save = function () {
        var KTCreateAccount = (function () {
            var e,
                t,
                i,
                o,
                a,
                r,
                s = [];
            return {
                init: function () {
                    (e = document.querySelector("#kt_modal_create_account")) &&
                        new bootstrap.Modal(e),
                        (t = document.querySelector(
                            "#kt_create_account_stepper"
                        )) &&
                        ((i = t.querySelector("#kt_create_account_form")),
                            (o = t.querySelector(
                                '[data-kt-stepper-action="submit"]'
                            )),
                            (a = t.querySelector(
                                '[data-kt-stepper-action="next"]'
                            )),
                            (r = new KTStepper(t)).on(
                                "kt.stepper.changed",
                                function (e) {
                                    4 === r.getCurrentStepIndex()
                                        ? (o.classList.remove("d-none"),
                                            o.classList.add("d-inline-block"),
                                            a.classList.add("d-none"))
                                        : 5 === r.getCurrentStepIndex()
                                            ? (o.classList.add("d-none"),
                                                a.classList.add("d-none"))
                                            : (o.classList.remove("d-inline-block"),
                                                o.classList.remove("d-none"),
                                                a.classList.remove("d-none"));
                                }
                            ),
                            r.on("kt.stepper.next", function (e) {
                                console.log("stepper.next");
                                var t = s[e.getCurrentStepIndex() - 1];
                                t
                                    ? t.validate().then(function (t) {
                                        console.log("validated!"),
                                            "Valid" == t
                                                ? (e.goNext(),
                                                    KTUtil.scrollTop())
                                                : Swal.fire({
                                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                                    icon: "error",
                                                    buttonsStyling: !1,
                                                    confirmButtonText:
                                                        "Ok, got it!",
                                                    customClass: {
                                                        confirmButton:
                                                            "btn btn-light",
                                                    },
                                                }).then(function () {
                                                    KTUtil.scrollTop();
                                                });
                                    })
                                    : (e.goNext(), KTUtil.scrollTop());
                            }),
                            r.on("kt.stepper.previous", function (e) {
                                console.log("stepper.previous"),
                                    e.goPrevious(),
                                    KTUtil.scrollTop();
                            }),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        name: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Name is required",
                                                },
                                            },
                                        },
                                        email: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Email is required",
                                                },
                                                emailAddress: {
                                                    message:
                                                        "The input is not a valid email address",
                                                },
                                            },
                                        },
                                        mobile_no: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Contact No is required",
                                                },
                                                regexp: {
                                                    regexp: /^[0-9]+$/,
                                                    message:
                                                        "The contact number can only contain digits",
                                                },
                                                stringLength: {
                                                    max: 10,
                                                    message:
                                                        "The contact number must be at most 10 digits long",
                                                },
                                            },
                                        },
                                        password: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Password is required",
                                                },
                                                stringLength: {
                                                    min: 8,
                                                    max: 20,
                                                    message:
                                                        "Password must be between 8 and 20 characters long",
                                                },
                                                regexp: {
                                                    regexp: /^(?=.*[a-zA-Z])(?=.*\d).+$/,
                                                    message:
                                                        "Password must contain at least one letter and one number",
                                                },
                                            },
                                        },
                                        confirm_password: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Confirm Password is required",
                                                },
                                                identical: {
                                                    compare: function () {
                                                        return i.querySelector(
                                                            '[name="password"]'
                                                        ).value;
                                                    },
                                                    message:
                                                        "Confirm Password must be the same as Password",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        department_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Department selection is required",
                                                },
                                            },
                                        },
                                        position: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Position is required",
                                                },
                                            },
                                        },
                                        date_of_birth: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Date of birth is required",
                                                },
                                            },
                                        },
                                        joining_date: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Joining date is required",
                                                },
                                            },
                                        },
                                        aadhar_card: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                        pan_card: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                        driving_license: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        bank_name: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Bank name is required",
                                                },
                                            },
                                        },
                                        account_no: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Bank account number is required",
                                                },
                                                digits: {
                                                    message:
                                                        "Bank account number must be digits only",
                                                },
                                            },
                                        },

                                        ifsc_code: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "IFSC code is required",
                                                },
                                                regexp: {
                                                    regexp: /^[A-Z]{5}\d{4}$/,
                                                    message:
                                                        "The IFSC code must be 5 Capital letters followed by 4 digits",
                                                },
                                            },
                                        },

                                        branch_address: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Branch address is required",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        address_1: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Address 1 is required",
                                                },
                                            },
                                        },
                                        address_2: {
                                            validators: {},
                                        },
                                        postal_code: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Postal code is required",
                                                },
                                                regexp: {
                                                    regexp: /^[0-9]{6}$/,
                                                    message:
                                                        "The postal code must be exactly 6 digits",
                                                },
                                            },
                                        },
                                        country_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Country selection is required",
                                                },
                                            },
                                        },
                                        state_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "State selection is required",
                                                },
                                            },
                                        },
                                        city_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "City selection is required",
                                                },
                                            },
                                        },
                                        report_to: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "reporting is required",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            /* o.addEventListener("click", function (e) {
                        s[3].validate().then(function (t) {
                            console.log("validated!"),
                                "Valid" == t
                                    ? (e.preventDefault(),
                                      (o.disabled = !0),
                                      o.setAttribute("data-kt-indicator", "on"),
                                      setTimeout(function () {
                                          o.removeAttribute(
                                              "data-kt-indicator"
                                          ),
                                              (o.disabled = !1),
                                              r.goNext();
                                      }, 2e3))
                                    : Swal.fire({
                                          text: "Sorry, looks like there are some errors detected, please try again.",
                                          icon: "error",
                                          buttonsStyling: !1,
                                          confirmButtonText: "Ok, got it!",
                                          customClass: {
                                              confirmButton: "btn btn-light",
                                          },
                                      }).then(function () {
                                          KTUtil.scrollTop();
                                      });
                        });
                    }),

                */

                            o.addEventListener("click", function (e) {
                                // alert("insde");
                                s[3].validate().then(function (t) {
                                    o.disabled = true;
                                    o.setAttribute("data-kt-indicator", "on");
                                    setTimeout(function () {
                                        o.removeAttribute("data-kt-indicator");
                                        o.disabled = false;
                                    }, 2000);
                                    if ("Valid" == t) {
                                        var formData = new FormData(i); // Assuming the form is named 'i'
                                        var csrfToken = document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .getAttribute("content");
                                        $.ajax({
                                            url:
                                                baseurl +
                                                "admin/employee-management/add-save-employee",
                                            type: "POST",
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            headers: {
                                                "X-CSRF-TOKEN": csrfToken, // Include the CSRF token in the request headers
                                            },
                                            success: function (response) {
                                                // Proceed to the next step if data is saved successfully
                                                o.disabled = true;
                                                o.setAttribute(
                                                    "data-kt-indicator",
                                                    "on"
                                                );
                                                setTimeout(function () {
                                                    o.removeAttribute(
                                                        "data-kt-indicator"
                                                    );
                                                    o.disabled = false;
                                                    r.goNext();
                                                }, 2000);
                                            },
                                            error: function (
                                                xhr,
                                                status,
                                                error
                                            ) {
                                                // Handle error scenario
                                                let errorMessage =
                                                    "Error occurred while saving data. Please try again.";

                                                if (
                                                    xhr.responseJSON &&
                                                    xhr.responseJSON.message
                                                ) {
                                                    errorMessage =
                                                        xhr.responseJSON
                                                            .message;
                                                }
                                                // console.error("Error saving data:", error);
                                                Swal.fire({
                                                    text: errorMessage,
                                                    icon: "error",
                                                    buttonsStyling: false,
                                                    confirmButtonText:
                                                        "Ok, got it!",
                                                    customClass: {
                                                        confirmButton:
                                                            "btn btn-light",
                                                    },
                                                }).then(function () {
                                                    KTUtil.scrollTop();
                                                });
                                            },
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-light",
                                            },
                                        }).then(function () {
                                            KTUtil.scrollTop();
                                        });
                                    }
                                });
                            }),
                            $(i.querySelector('[name="card_expiry_month"]')).on(
                                "change",
                                function () {
                                    s[3].revalidateField("card_expiry_month");
                                }
                            ),
                            $(i.querySelector('[name="card_expiry_year"]')).on(
                                "change",
                                function () {
                                    s[3].revalidateField("card_expiry_year");
                                }
                            ),
                            $(i.querySelector('[name="business_type"]')).on(
                                "change",
                                function () {
                                    s[2].revalidateField("business_type");
                                }
                            ));
                },
            };
        })();
        KTUtil.onDOMContentLoaded(function () {
            KTCreateAccount.init();
        });
    };

    var edit_save = function () {
        var KTEditAccount = (function () {
            var e,
                t,
                i,
                o,
                a,
                r,
                s = [];
            return {
                init: function () {
                    (e = document.querySelector("#kt_modal_create_account")) &&
                        new bootstrap.Modal(e),
                        (t = document.querySelector(
                            "#kt_create_account_stepper"
                        )) &&
                        ((i = t.querySelector("#kt_create_account_form")),
                            (o = t.querySelector(
                                '[data-kt-stepper-action="submit"]'
                            )),
                            (a = t.querySelector(
                                '[data-kt-stepper-action="next"]'
                            )),
                            (r = new KTStepper(t)).on(
                                "kt.stepper.changed",
                                function (e) {
                                    4 === r.getCurrentStepIndex()
                                        ? (o.classList.remove("d-none"),
                                            o.classList.add("d-inline-block"),
                                            a.classList.add("d-none"))
                                        : 5 === r.getCurrentStepIndex()
                                            ? (o.classList.add("d-none"),
                                                a.classList.add("d-none"))
                                            : (o.classList.remove("d-inline-block"),
                                                o.classList.remove("d-none"),
                                                a.classList.remove("d-none"));
                                }
                            ),
                            r.on("kt.stepper.next", function (e) {
                                console.log("stepper.next");
                                var t = s[e.getCurrentStepIndex() - 1];
                                t
                                    ? t.validate().then(function (t) {
                                        console.log("validated!"),
                                            "Valid" == t
                                                ? (e.goNext(),
                                                    KTUtil.scrollTop())
                                                : Swal.fire({
                                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                                    icon: "error",
                                                    buttonsStyling: !1,
                                                    confirmButtonText:
                                                        "Ok, got it!",
                                                    customClass: {
                                                        confirmButton:
                                                            "btn btn-light",
                                                    },
                                                }).then(function () {
                                                    KTUtil.scrollTop();
                                                });
                                    })
                                    : (e.goNext(), KTUtil.scrollTop());
                            }),
                            r.on("kt.stepper.previous", function (e) {
                                console.log("stepper.previous"),
                                    e.goPrevious(),
                                    KTUtil.scrollTop();
                            }),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        name: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Name is required",
                                                },
                                            },
                                        },
                                        email: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Email is required",
                                                },
                                                emailAddress: {
                                                    message:
                                                        "The input is not a valid email address",
                                                },
                                            },
                                        },
                                        mobile_no: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Employee Contact No is required",
                                                },
                                                regexp: {
                                                    regexp: /^[0-9]+$/,
                                                    message:
                                                        "The contact number can only contain digits",
                                                },
                                                stringLength: {
                                                    max: 10,
                                                    message:
                                                        "The contact number must be at most 10 digits long",
                                                },
                                            },
                                        },
                                        password: {
                                            validators: {
                                                stringLength: {
                                                    min: 8,
                                                    max: 20,
                                                    message:
                                                        "Password must be between 8 and 20 characters long",
                                                },
                                                regexp: {
                                                    regexp: /^(?=.*[a-zA-Z])(?=.*\d).+$/,
                                                    message:
                                                        "Password must contain at least one letter and one number",
                                                },
                                            },
                                        },
                                        confirm_password: {
                                            validators: {
                                                identical: {
                                                    compare: function () {
                                                        return i.querySelector(
                                                            '[name="password"]'
                                                        ).value;
                                                    },
                                                    message:
                                                        "Confirm Password must be the same as Password",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        department_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Department selection is required",
                                                },
                                            },
                                        },
                                        position: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Position is required",
                                                },
                                            },
                                        },
                                        date_of_birth: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Date of birth is required",
                                                },
                                            },
                                        },
                                        joining_date: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Joining date is required",
                                                },
                                            },
                                        },
                                        aadhar_card: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                        pan_card: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                        driving_license: {
                                            validators: {
                                                file: {
                                                    extension:
                                                        "pdf,jpg,png,jpeg",
                                                    type: "application/pdf,image/jpeg,image/png",
                                                    message:
                                                        "The selected file is not valid. Only pdf, jpg, and png files are allowed.",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        bank_name: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Bank name is required",
                                                },
                                            },
                                        },
                                        account_no: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Bank account number is required",
                                                },
                                                digits: {
                                                    message:
                                                        "Bank account number must be digits only",
                                                },
                                            },
                                        },
                                        ifsc_code: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "IFSC code is required",
                                                },
                                                regexp: {
                                                    regexp: /^[A-Z]{5}\d{4}$/,
                                                    message:
                                                        "The IFSC code must be 5 Capital letters followed by 4 digits",
                                                },
                                            },
                                        },

                                        branch_address: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Branch address is required",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            s.push(
                                FormValidation.formValidation(i, {
                                    fields: {
                                        address_1: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Address 1 is required",
                                                },
                                            },
                                        },
                                        address_2: {
                                            validators: {},
                                        },
                                        postal_code: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Postal code is required",
                                                },
                                                regexp: {
                                                    regexp: /^[0-9]{6}$/,
                                                    message:
                                                        "The postal code must be exactly 6 digits",
                                                },
                                            },
                                        },
                                        country_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "Country selection is required",
                                                },
                                            },
                                        },
                                        state_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "State selection is required",
                                                },
                                            },
                                        },
                                        city_id: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "City selection is required",
                                                },
                                            },
                                        },
                                        report_to: {
                                            validators: {
                                                notEmpty: {
                                                    message:
                                                        "reporting is required",
                                                },
                                            },
                                        },
                                    },
                                    plugins: {
                                        trigger:
                                            new FormValidation.plugins.Trigger(),
                                        bootstrap:
                                            new FormValidation.plugins.Bootstrap5(
                                                {
                                                    rowSelector: ".fv-row",
                                                    eleInvalidClass: "",
                                                    eleValidClass: "",
                                                }
                                            ),
                                    },
                                })
                            ),
                            /* o.addEventListener("click", function (e) {
                        s[3].validate().then(function (t) {
                            console.log("validated!"),
                                "Valid" == t
                                    ? (e.preventDefault(),
                                      (o.disabled = !0),
                                      o.setAttribute("data-kt-indicator", "on"),
                                      setTimeout(function () {
                                          o.removeAttribute(
                                              "data-kt-indicator"
                                          ),
                                              (o.disabled = !1),
                                              r.goNext();
                                      }, 2e3))
                                    : Swal.fire({
                                          text: "Sorry, looks like there are some errors detected, please try again.",
                                          icon: "error",
                                          buttonsStyling: !1,
                                          confirmButtonText: "Ok, got it!",
                                          customClass: {
                                              confirmButton: "btn btn-light",
                                          },
                                      }).then(function () {
                                          KTUtil.scrollTop();
                                      });
                        });
                    }),

                */

                            o.addEventListener("click", function (e) {
                                // alert("insde");
                                s[3].validate().then(function (t) {
                                    o.disabled = true;
                                    o.setAttribute("data-kt-indicator", "on");
                                    setTimeout(function () {
                                        o.removeAttribute("data-kt-indicator");
                                        o.disabled = false;
                                    }, 2000);
                                    if ("Valid" == t) {
                                        var formData = new FormData(i); // Assuming the form is named 'i'
                                        var csrfToken = document
                                            .querySelector(
                                                'meta[name="csrf-token"]'
                                            )
                                            .getAttribute("content");
                                        $.ajax({
                                            url:
                                                baseurl +
                                                "admin/employee-management/edit-save-employee",
                                            type: "POST",
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            headers: {
                                                "X-CSRF-TOKEN": csrfToken, // Include the CSRF token in the request headers
                                            },
                                            success: function (response) {
                                                // Proceed to the next step if data is saved successfully
                                                o.disabled = true;
                                                o.setAttribute(
                                                    "data-kt-indicator",
                                                    "on"
                                                );
                                                setTimeout(function () {
                                                    o.removeAttribute(
                                                        "data-kt-indicator"
                                                    );
                                                    o.disabled = false;
                                                    r.goNext();
                                                }, 2000);
                                            },
                                            error: function (
                                                xhr,
                                                status,
                                                error
                                            ) {
                                                // Handle error scenario
                                                let errorMessage =
                                                    "Error occurred while saving data. Please try again.";

                                                if (
                                                    xhr.responseJSON &&
                                                    xhr.responseJSON.message
                                                ) {
                                                    errorMessage =
                                                        xhr.responseJSON
                                                            .message;
                                                }
                                                // console.error("Error saving data:", error);
                                                Swal.fire({
                                                    text: errorMessage,
                                                    icon: "error",
                                                    buttonsStyling: false,
                                                    confirmButtonText:
                                                        "Ok, got it!",
                                                    customClass: {
                                                        confirmButton:
                                                            "btn btn-light",
                                                    },
                                                }).then(function () {
                                                    KTUtil.scrollTop();
                                                });
                                            },
                                        });
                                    } else {
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-light",
                                            },
                                        }).then(function () {
                                            KTUtil.scrollTop();
                                        });
                                    }
                                });
                            }),
                            $(i.querySelector('[name="card_expiry_month"]')).on(
                                "change",
                                function () {
                                    s[3].revalidateField("card_expiry_month");
                                }
                            ),
                            $(i.querySelector('[name="card_expiry_year"]')).on(
                                "change",
                                function () {
                                    s[3].revalidateField("card_expiry_year");
                                }
                            ),
                            $(i.querySelector('[name="business_type"]')).on(
                                "change",
                                function () {
                                    s[2].revalidateField("business_type");
                                }
                            ));
                },
            };
        })();

        KTUtil.onDOMContentLoaded(function () {
            KTEditAccount.init();
        });
    };

    return {
        add: function () {
            add_save();
        },
        edit: function () {
            edit_save();
        },
    };
})();
