<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? get_project_name() }}</title>

    <!-- Google Fonts (Inter) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Metronic 8 Global Stylesheets Bundle -->
    <link href="{{ asset('backend/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!-- FontAwesome CDN / Local Bundle -->
    @if(file_exists(public_path('backend/plugins/global/fonts/fontawesome/font_cdn.css')))
        <link href="{{ asset('backend/plugins/global/fonts/fontawesome/font_cdn.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @endif

    <!-- Custom CSS Overrides -->
    <style>
        .app-sidebar-logo {
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }
        .flat-brand-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <!-- Dynamic Plugin CSS -->
    @if (!empty($plugincss))
        @foreach ($plugincss as $value)
            @if (!empty($value))
                <link rel="stylesheet" href="{{ asset('backend/' . $value) }}">
            @endif
        @endforeach
    @endif

    <!-- Dynamic Custom CSS -->
    @if (!empty($css))
        @foreach ($css as $value)
            @if (!empty($value))
                <link rel="stylesheet" href="{{ asset('backend/css/' . $value) }}">
            @endif
        @endforeach
    @endif

    @stack('styles')
</head>
