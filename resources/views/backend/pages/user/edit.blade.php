@extends('backend.layout.layout')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <!--begin::Card-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800 fs-3">Edit Member: {{ $user->name }}</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Update contact info, role or password</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('user') }}" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-black-left fs-3"></i> Back to Members
                    </a>
                </div>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-5">
                <form id="edit-user-form" method="POST" action="{{ route('user-update', $user->id) }}" enctype="multipart/form-data">
                    @csrf

                    <!--begin::Row - Avatar-->
                    <div class="row mb-7 align-items-center">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 text-gray-700">Profile Photo</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline" data-kt-image-input="true" 
                                 style="background-image: url('{{ $user->avatar_url }}')">
                                <div class="image-input-wrapper w-100px h-100px" 
                                     style="background-image: url('{{ $user->avatar_url }}')"></div>
                                
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                       data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="file" name="avatar" id="avatar-input" accept=".png, .jpg, .jpeg" />
                                </label>
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg (Max: 2MB). Leave unchanged to keep current.</div>
                        </div>
                    </div>
                    <!--end::Row - Avatar-->

                    <!--begin::Row - Name-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Full Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="name" class="form-control form-control-solid" 
                                   value="{{ old('name', $user->name) }}" required />
                        </div>
                    </div>
                    <!--end::Row - Name-->

                    <!--begin::Row - Phone Number-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Phone Number (Login ID)</label>
                        <div class="col-lg-8">
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="fa-solid fa-phone fs-6"></i></span>
                                <input type="tel" name="phone" class="form-control form-control-solid" 
                                       value="{{ old('phone', $user->phone) }}" required />
                            </div>
                        </div>
                    </div>
                    <!--end::Row - Phone Number-->

                    <!--begin::Row - Email Address-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Email Address (Login ID)</label>
                        <div class="col-lg-8">
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="fa-regular fa-envelope fs-6"></i></span>
                                <input type="email" name="email" class="form-control form-control-solid" 
                                       value="{{ old('email', $user->email) }}" required />
                            </div>
                        </div>
                    </div>
                    <!--end::Row - Email Address-->

                    <!--begin::Row - Role-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Role</label>
                        <div class="col-lg-8">
                            <select name="role" class="form-select form-select-solid" required {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Flat Member</option>
                                <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Admin</option>
                            </select>
                            @if(Auth::id() === $user->id)
                                <input type="hidden" name="role" value="1" />
                                <div class="form-text text-muted">You cannot change your own admin role.</div>
                            @endif
                        </div>
                    </div>
                    <!--end::Row - Role-->

                    <!--begin::Row - Password-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 text-gray-700">New Password</label>
                        <div class="col-lg-8">
                            <div class="position-relative">
                                <input type="password" name="password" id="member_password" 
                                       class="form-control form-control-solid" 
                                       placeholder="Leave blank to keep current password" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" 
                                      id="toggleMemberPassword" type="button">
                                    <i class="fa-regular fa-eye fs-6"></i>
                                </span>
                            </div>
                            <div class="form-text">Only enter if you want to reset this member's password.</div>
                        </div>
                    </div>
                    <!--end::Row - Password-->

                    <!--begin::Row - Status-->
                    <div class="row mb-8">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 text-gray-700">Account Status</label>
                        <div class="col-lg-8">
                            <select name="status" class="form-select form-select-solid" {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ old('status', $user->status) == 2 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @if(Auth::id() === $user->id)
                                <input type="hidden" name="status" value="1" />
                            @endif
                        </div>
                    </div>
                    <!--end::Row - Status-->

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('user') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary submitbtn">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-check fs-2 me-1"></i> Update Flat Member
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection
