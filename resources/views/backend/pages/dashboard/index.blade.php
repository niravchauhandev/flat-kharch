@extends('backend.layout.layout')

@section('content')
<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
    <!--begin::Col - Stat Card: Current Pool Balance-->
    <div class="col-sm-6 col-xl-3 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-primary me-2 lh-1 ls-n2">{{ format_rupee($stats['current_balance']) }}</span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Available Pool Balance</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                        <span class="fw-bold fs-7 text-gray-500">Fund Remaining</span>
                        <span class="fw-bolder fs-7 text-primary">68%</span>
                    </div>
                    <div class="h-8px mx-3 w-100 bg-light-primary rounded">
                        <div class="bg-primary rounded h-8px" role="progressbar" style="width: 68%;" aria-valuenow="68" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col - Stat Card: Total Spent-->
    <div class="col-sm-6 col-xl-3 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-danger me-2 lh-1 ls-n2">{{ format_rupee($stats['total_expenses']) }}</span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Expenses (This Month)</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                        <span class="fw-bold fs-7 text-gray-500">Expenses Logged</span>
                        <span class="badge badge-light-danger fw-bold">14 Bills</span>
                    </div>
                    <div class="h-8px mx-3 w-100 bg-light-danger rounded">
                        <div class="bg-danger rounded h-8px" role="progressbar" style="width: 32%;" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col - Stat Card: Target Pool Fund-->
    <div class="col-sm-6 col-xl-3 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-success me-2 lh-1 ls-n2">{{ format_rupee($stats['total_pool_balance']) }}</span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Target Monthly Fund</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                        <span class="fw-bold fs-7 text-gray-500">Collection Status</span>
                        <span class="badge badge-light-success fw-bold">8 / 8 Contributed</span>
                    </div>
                    <div class="h-8px mx-3 w-100 bg-light-success rounded">
                        <div class="bg-success rounded h-8px" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->

    <!--begin::Col - Stat Card: Flat Members-->
    <div class="col-sm-6 col-xl-3 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">{{ $stats['active_members'] }} Members</span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Active Flatmates</span>
                </div>
            </div>
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="symbol-group symbol-hover">
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Nirav">
                            <span class="symbol-label bg-primary text-inverse-primary fw-bold">N</span>
                        </div>
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Rahul">
                            <span class="symbol-label bg-success text-inverse-success fw-bold">R</span>
                        </div>
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Amit">
                            <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                        </div>
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Vijay">
                            <span class="symbol-label bg-info text-inverse-info fw-bold">V</span>
                        </div>
                        <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="4 More Members">
                            <span class="symbol-label bg-dark text-inverse-dark fw-bold">+4</span>
                        </div>
                    </div>
                    <span class="badge badge-light-primary fw-bold">1 Admin</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Col-->
</div>

<!--begin::Quick Actions & Overview Card-->
<div class="card card-flush mb-5 mb-xl-10">
    <div class="card-header pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-800 fs-3">Quick Actions & Central Pool Summary</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-6">Zero-Dispute transparent petty cash system for flatmates</span>
        </h3>
        <div class="card-toolbar">
            <a href="javascript:;" class="btn btn-sm btn-primary me-2">
                <i class="ki-duotone ki-plus fs-2"></i> Add Flat Expense
            </a>
            <a href="javascript:;" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-handcart fs-2"></i> Issue Advance Cash
            </a>
        </div>
    </div>
    <div class="card-body pt-5">
        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
            <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-semibold">
                    <h4 class="text-gray-900 fw-bold">Current Pool Month: {{ date('F Y') }}</h4>
                    <div class="fs-6 text-gray-700">
                        Total collection is ₹48,000. All members have paid their monthly contribution. Remaining balance is ₹32,450.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Quick Actions Card-->
@endsection
