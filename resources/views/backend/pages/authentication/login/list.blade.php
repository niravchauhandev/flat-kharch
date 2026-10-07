<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Login || ' . get_project_name() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Google Fonts (Inter) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Metronic 8 Global Stylesheets Bundle -->
    <link href="{{ asset('backend/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/plugins/global/fonts/fontawesome/font_cdn.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body id="kt_body" class="app-blank d-flex flex-column flex-root align-items-center justify-content-center min-vh-100 p-4">
    <!--begin::Login Box-->
    <div class="w-100" style="max-width: 440px;">
        <div class="card login-card border-0 p-8 p-md-10">
            <!--begin::Logo & Heading-->
            <div class="text-center mb-8">
                @if(file_exists(public_path('backend/upload/system_setting/falt_kharch_logo.png')))
                    <img src="{{ asset('backend/upload/system_setting/falt_kharch_logo.png') }}" 
                         alt="Flat-Kharch" class="h-65px mb-3" />
                @else
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle p-4 mb-3" style="width: 60px; height: 60px;">
                        <i class="fa-solid fa-wallet fs-2 text-white"></i>
                    </div>
                @endif
                <h1 class="text-gray-900 fw-bolder fs-2 mb-1">{{ get_project_name() }}</h1>
                <div class="text-gray-500 fw-semibold fs-6">Sign in to manage your flat expenses</div>
            </div>
            <!--end::Logo & Heading-->

            <!--begin::Flash Messages-->
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center p-4 mb-6">
                    <i class="ki-duotone ki-information-5 fs-2hx text-danger me-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-4 mb-6">
                    <i class="ki-duotone ki-check-circle fs-2hx text-success me-3">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            <!--end::Flash Messages-->

            <!--begin::Form-->
            <form class="form w-100" id="login-form" method="POST" action="{{ route('login-check') }}">
                @csrf
                
                <!--begin::Email or Phone Input-->
                <div class="fv-row mb-6">
                    <label class="form-label fs-6 fw-bold text-gray-900">Email or Phone Number</label>
                    <input class="form-control form-control-lg form-control-solid" type="text" name="email" 
                           id="email" placeholder="e.g. nirav@flat.com or 9876543210" 
                           value="{{ old('email') }}" autocomplete="off" required />
                </div>
                <!--end::Email or Phone Input-->

                <!--begin::Password Input-->
                <div class="fv-row mb-6">
                    <div class="d-flex flex-stack mb-2">
                        <label class="form-label fw-bold text-gray-900 fs-6 mb-0">Password</label>
                    </div>
                    <div class="position-relative">
                        <input class="form-control form-control-lg form-control-solid" type="password" 
                               name="password" id="password" placeholder="Enter password" autocomplete="off" required />
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="togglePasswordBtn" type="button">
                            <i class="fa-regular fa-eye fs-6" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                </div>
                <!--end::Password Input-->

                <!--begin::Remember Me-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <label class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" />
                        <span class="form-check-label text-gray-700">Remember Me</span>
                    </label>
                </div>
                <!--end::Remember Me-->

                <!--begin::Submit Button-->
                <div class="d-grid mb-4">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary btn-lg submitbtn">
                        <span class="indicator-label fw-bold">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <!--end::Submit Button-->
            </form>
            <!--end::Form-->
        </div>
    </div>
    <!--end::Login Box-->

    <!--begin::Global Javascript Bundle-->
    <script src="{{ asset('backend/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('backend/js/customjs/comman_function.js') }}"></script>
    <script src="{{ asset('backend/js/customjs/login.js') }}"></script>

    <script>
        $(document).ready(function() {
            Login.init();
        });
    </script>
</body>
</html>
