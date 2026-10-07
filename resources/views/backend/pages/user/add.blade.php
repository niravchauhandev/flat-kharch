@extends('backend.layout.layout')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <!--begin::Card-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-800 fs-3">Member Details</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Fill in credentials to register a new flatmate</span>
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
                <form id="add-user-form" method="POST" action="{{ route('user-store') }}" enctype="multipart/form-data">
                    @csrf

                    <!--begin::Row - Avatar-->
                    <div class="row mb-7 align-items-center">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 text-gray-700">Profile Photo</label>
                        <div class="col-lg-8">
                            <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" 
                                 style="background-image: url('{{ get_user_avatar(null, 'User') }}')">
                                <div class="image-input-wrapper w-100px h-100px" id="avatar-preview-box"></div>
                                
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                       data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="file" name="avatar" id="avatar-input" accept=".png, .jpg, .jpeg" />
                                </label>

                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" 
                                      data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="form-text">Allowed file types: png, jpg, jpeg (Max: 2MB).</div>
                        </div>
                    </div>
                    <!--end::Row - Avatar-->

                    <!--begin::Row - Name-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Full Name</label>
                        <div class="col-lg-8">
                            <input type="text" name="name" class="form-control form-control-solid" 
                                   placeholder="e.g. Nirav Patel" value="{{ old('name') }}" required />
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
                                       placeholder="e.g. 9876543210" value="{{ old('phone') }}" required />
                            </div>
                            <div class="form-text">Used for logging into website/app and receiving WhatsApp summaries.</div>
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
                                       placeholder="e.g. nirav@flat.com" value="{{ old('email') }}" required />
                            </div>
                        </div>
                    </div>
                    <!--end::Row - Email Address-->

                    <!--begin::Row - Role-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Role</label>
                        <div class="col-lg-8">
                            <select name="role" class="form-select form-select-solid" required>
                                <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Flat Member (Default)</option>
                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Admin (Can manage fund & add members)</option>
                            </select>
                        </div>
                    </div>
                    <!--end::Row - Role-->

                    <!--begin::Row - Password-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6 text-gray-700">Password</label>
                        <div class="col-lg-8">
                            <div class="position-relative">
                                <input type="password" name="password" id="member_password" 
                                       class="form-control form-control-solid" 
                                       placeholder="Minimum 6 characters" required />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" 
                                      id="toggleMemberPassword" type="button">
                                    <i class="fa-regular fa-eye fs-6"></i>
                                </span>
                            </div>
                            <div class="form-text">Password given to member for sign in.</div>
                        </div>
                    </div>
                    <!--end::Row - Password-->

                    <!--begin::Row - Status-->
                    <div class="row mb-8">
                        <label class="col-lg-4 col-form-label fw-bold fs-6 text-gray-700">Account Status</label>
                        <div class="col-lg-8">
                            <select name="status" class="form-select form-select-solid">
                                <option value="1" selected>Active (Can login & participate)</option>
                                <option value="2">Inactive (Access disabled)</option>
                            </select>
                        </div>
                    </div>
                    <!--end::Row - Status-->

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('user') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary submitbtn">
                            <span class="indicator-label">
                                <i class="ki-duotone ki-check fs-2 me-1"></i> Save Flat Member
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
