@php
    $auth_user = Auth::user();
    $avatar_url = $auth_user ? $auth_user->avatar_url : get_user_avatar(null, 'User');
    $user_name = $auth_user ? $auth_user->name : 'Flat Admin';
    $user_email = $auth_user ? $auth_user->email : 'admin@flat.com';
    $user_role = $auth_user ? $auth_user->role : 1;
@endphp

<!--begin::User menu-->
<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
    <!--begin::Menu wrapper-->
    <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end">
        <img src="{{ $avatar_url }}" alt="avatar" class="rounded-3" />
    </div>
    <!--begin::User account menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-4">
                    <img alt="Logo" src="{{ $avatar_url }}" class="rounded-3" />
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ $user_name }}
                    </div>
                    <span class="fw-semibold text-muted fs-7">{{ $user_email }}</span>
                    <div class="mt-1">
                        {!! get_user_role_badge($user_role) !!}
                    </div>
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="#" class="menu-link px-5">
                <i class="fa-regular fa-user me-2 text-primary"></i> My Profile
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="#" class="menu-link px-5">
                <i class="fa-solid fa-sliders me-2 text-info"></i> Flat Settings
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->
        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a href="{{ route('logout') }}" class="menu-link px-5 text-danger">
                <i class="fa-solid fa-arrow-right-from-bracket me-2 text-danger"></i> Sign Out
            </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::User account menu-->
    <!--end::Menu wrapper-->
</div>
<!--end::User menu-->
