@extends('backend.layout.layout')

@section('content')
<!--begin::Card-->
<div class="card card-flush">
    <!--begin::Card header-->
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <!--begin::Card title - Search & Filters-->
        <div class="card-title">
            <form method="GET" action="{{ route('user') }}" class="d-flex align-items-center gap-3 flex-wrap">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control form-control-solid w-250px ps-12" 
                           placeholder="Search name, phone, email..." />
                </div>
                <!--end::Search-->

                <!--begin::Role Filter-->
                <div class="w-150px">
                    <select name="role" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Member</option>
                    </select>
                </div>
                <!--end::Role Filter-->

                <!--begin::Status Filter-->
                <div class="w-150px">
                    <select name="status" class="form-select form-select-solid" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <!--end::Status Filter-->

                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('user') }}" class="btn btn-light-danger btn-sm">
                        <i class="fa-solid fa-xmark me-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
            @can('user add')
                <a href="{{ route('user-create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i> Add Flat Member
                </a>
            @endcan
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Table-->
        <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-200px">Member</th>
                        <th class="min-w-125px">Phone Number</th>
                        <th class="min-w-100px">Role</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-125px">Joined On</th>
                        @if(auth()->user()->can('user edit') || auth()->user()->can('user delete'))
                            <th class="text-end min-w-100px">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold">
                    @forelse($users as $user)
                        <tr>
                            <!-- Member Name & Avatar -->
                            <td class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-45px me-3">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" />
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold fs-6">{{ $user->name }}</span>
                                    <span class="text-muted fs-7">{{ $user->email }}</span>
                                </div>
                            </td>

                            <!-- Phone Number -->
                            <td>
                                <span class="badge badge-light-secondary fs-7 fw-bold text-dark">
                                    <i class="fa-solid fa-phone fs-8 me-1 text-primary"></i>
                                    {{ $user->phone ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Role -->
                            <td>
                                {!! get_user_role_badge($user->role) !!}
                            </td>

                            <!-- Status -->
                            <td>
                                @can('user status')
                                    <button type="button" class="btn btn-sm p-0 border-0 status-toggle-btn" 
                                            data-id="{{ $user->id }}" 
                                            title="Click to toggle status">
                                        {!! get_status_badge($user->status) !!}
                                    </button>
                                @else
                                    {!! get_status_badge($user->status) !!}
                                @endcan
                            </td>

                            <!-- Joined Date -->
                            <td>
                                {{ date_formate($user->created_at) }}
                            </td>

                            <!-- Actions -->
                            @if(auth()->user()->can('user edit') || auth()->user()->can('user delete'))
                                <td class="text-end">
                                    @can('user edit')
                                        <a href="{{ route('user-edit', $user->id) }}" 
                                           class="btn btn-icon btn-light-primary btn-sm me-1" 
                                           data-bs-toggle="tooltip" title="Edit Member">
                                            <i class="ki-duotone ki-pencil fs-4">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                        </a>
                                    @endcan

                                    @can('user delete')
                                        @if(Auth::id() !== $user->id)
                                            <button type="button" 
                                                    class="btn btn-icon btn-light-danger btn-sm" 
                                                    onclick="User.deleteMember('{{ route('user-delete', $user->id) }}', '{{ $user->name }}')" 
                                                    data-bs-toggle="tooltip" title="Delete Member">
                                                <i class="ki-duotone ki-trash fs-4">
                                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                                </i>
                                            </button>
                                        @endif
                                    @endcan
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-muted">
                                <i class="ki-duotone ki-user fs-3tx mb-2 text-gray-400 d-block">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <h5>No Flat Members Found</h5>
                                @can('user add')
                                    <p class="fs-7 text-muted">Click "+ Add Flat Member" above to add your first flatmate.</p>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!--end::Table-->

        <!--begin::Pagination-->
        <div class="d-flex justify-content-between align-items-center flex-wrap pt-4">
            <div class="text-muted fs-7">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} members
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        <!--end::Pagination-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->
@endsection
