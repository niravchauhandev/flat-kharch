@php
    $currentRoute = Route::currentRouteName();
@endphp

<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="240px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('dashboard') }}" class="flat-brand-title text-decoration-none">
            <span class="symbol symbol-35px bg-primary rounded-3 d-flex align-items-center justify-content-center text-white">
                <i class="fa-solid fa-wallet fs-5"></i>
            </span>
            <span class="fs-4 fw-bolder text-white">Flat-Kharch</span>
        </a>
        
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->

    <!--begin::Sidebar Menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    <!--begin:Heading-->
                    <div class="menu-item pt-2 pb-1">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Core Overview</span>
                        </div>
                    </div>
                    <!--end:Heading-->

                    <!--begin:Dashboard Menu-->
                    <div class="menu-item">
                        <a class="menu-link {{ in_array($currentRoute, ['dashboard', 'home']) ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    <!--end:Dashboard Menu-->

                    <!--begin:Heading-->
                    <div class="menu-item pt-4 pb-1">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Pool & Cash Flow</span>
                        </div>
                    </div>
                    <!--end:Heading-->

                    <!--begin:Monthly Pool-->
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'monthly-pool') ? 'active' : '' }}" 
                           href="{{ Route::has('monthly-pool.index') ? route('monthly-pool.index') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-wallet fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Monthly Pool</span>
                        </a>
                    </div>
                    <!--end:Monthly Pool-->

                    <!--begin:Cash Advances-->
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'cash-advance') ? 'active' : '' }}" 
                           href="{{ Route::has('cash-advance.index') ? route('cash-advance.index') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-handcart fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Cash Advances</span>
                        </a>
                    </div>
                    <!--end:Cash Advances-->

                    <!--begin:Expenses-->
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'expense') ? 'active' : '' }}" 
                           href="{{ Route::has('expense.index') ? route('expense.index') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-bill fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title">Expenses & Bills</span>
                        </a>
                    </div>
                    <!--end:Expenses-->

                    <!--begin:Heading-->
                    <div class="menu-item pt-4 pb-1">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Flat Harmony</span>
                        </div>
                    </div>
                    <!--end:Heading-->

                    <!--begin:Leave Mode-->
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'leave') ? 'active' : '' }}" 
                           href="{{ Route::has('leave.index') ? route('leave.index') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-calendar-tick fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title">Leave Mode</span>
                        </a>
                    </div>
                    <!--end:Leave Mode-->

                    <!--begin:Grocery Wishlist-->
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'grocery') ? 'active' : '' }}" 
                           href="{{ Route::has('grocery.index') ? route('grocery.index') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-basket fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Grocery Wishlist</span>
                        </a>
                    </div>
                    <!--end:Grocery Wishlist-->

                    <!--begin:Heading-->
                    <div class="menu-item pt-4 pb-1">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Admin & Reports</span>
                        </div>
                    </div>
                    <!--end:Heading-->

                    <!--begin:Flat Members-->
                    @can('user list')
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'user') ? 'active' : '' }}" 
                           href="{{ Route::has('user') ? route('user') : 'javascript:;' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-profile-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Flat Members</span>
                        </a>
                    </div>
                    @endcan
                    <!--end:Flat Members-->

                    <!--begin:Reports & PDF-->
                    @can('report list')
                    <div class="menu-item">
                        <a class="menu-link {{ str_contains($currentRoute ?? '', 'report') ? 'active' : '' }}" 
                           href="{{ Route::has('report') ? route('report') : (Route::has('report.index') ? route('report.index') : 'javascript:;') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-document fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title">Reports & PDF</span>
                        </a>
                    </div>
                    @endcan
                    <!--end:Reports & PDF-->

                </div>
                <!--end::Menu-->
            </div>
        </div>
    </div>
    <!--end::Sidebar Menu-->
</div>
<!--end::Sidebar-->
