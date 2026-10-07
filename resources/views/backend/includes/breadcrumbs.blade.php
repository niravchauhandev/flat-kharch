<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{ $header['title'] ?? ($title ?? 'Dashboard') }}
            </h1>
            <!--end::Title-->
            
            <!--begin::Breadcrumb-->
            @if(isset($header['breadcrumb']) && is_array($header['breadcrumb']))
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    @php
                        $count = count($header['breadcrumb']);
                        $temp = 1;
                    @endphp
                    @foreach($header['breadcrumb'] as $key => $value)
                        @php
                            $value = (empty($value)) ? 'javascript:;' : $value;
                        @endphp

                        @if($temp != $count)
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ $value }}" class="text-muted text-hover-primary">
                                    @if($temp == 1)
                                        <i class="fa fa-home me-1"></i> {{ $key }}
                                    @else
                                        {{ $key }}
                                    @endif
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                        @else
                            <li class="breadcrumb-item text-muted">
                                {{ $key }}
                            </li>
                        @endif
                        @php $temp++; @endphp
                    @endforeach
                </ul>
            @endif
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->

        <!--begin::Actions-->
        @if(isset($header['subtitle']))
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <span class="text-muted fs-7">{{ $header['subtitle'] }}</span>
            </div>
        @endif
        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
