<!--begin::Javascript-->
<script>
    var themeMode = "light";
    localStorage.setItem("data-bs-theme", "light");
    if (document.documentElement) {
        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
</script>

<script>
    var hostUrl = "{{ asset('/') }}";
    var baseurl = "{{ asset('/') }}";
</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('backend/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('backend/js/scripts.bundle.js') }}"></script>

<!-- Dynamic Plugin JS -->
@if (!empty($pluginjs))
    @foreach ($pluginjs as $value)
        <script src="{{ asset('backend/js/' . $value) }}" type="text/javascript"></script>
    @endforeach
@endif

<!-- Dynamic Custom Module JS (Loaded from public/backend/js/customjs/) -->
@if (!empty($js))
    @foreach ($js as $value)
        <script src="{{ asset('backend/js/customjs/' . $value) }}" type="text/javascript"></script>
    @endforeach
@endif

<!-- Dynamic Function Init -->
<script>
    jQuery(document).ready(function() {
        @if (!empty($funinit))
            @foreach ($funinit as $value)
                try {
                    {{ $value }};
                } catch (e) {
                    console.error("Function Init Error:", e);
                }
            @endforeach
        @endif
    });
</script>

<!-- Toastr & SweetAlert Flash Notifications -->
@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif

@if (session('warning'))
    <script>
        toastr.warning("{{ session('warning') }}");
    </script>
@endif

@stack('scripts')
