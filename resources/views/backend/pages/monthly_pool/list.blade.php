@extends('backend.layout.layout')

@section('content')
<div class="d-flex flex-column gap-6">

    <!-- Active Monthly Pool Summary Cards -->
    @if(!empty($activePoolSummary))
        @php
            $activePool = $activePoolSummary['pool'];
            $percent = $activePoolSummary['percentage'];
        @endphp
        <div class="row g-5">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-flush bg-body shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="symbol symbol-40px bg-light-primary rounded-3 me-3 d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-wallet fs-2 text-primary">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                </i>
                            </span>
                            <div>
                                <span class="text-muted fs-7 fw-semibold d-block">Active Pool</span>
                                <span class="text-gray-900 fs-5 fw-bold">{{ $activePool->month_name }} {{ $activePool->target_year }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="text-gray-800 fs-2 fw-bolder">{{ format_rupee($activePool->total_target_amount) }}</span>
                            <span class="chip ok">Ongoing</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-flush bg-body shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="symbol symbol-40px bg-light-success rounded-3 me-3 d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-check-circle fs-2 text-success">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                            <div>
                                <span class="text-muted fs-7 fw-semibold d-block">Total Deposited</span>
                                <span class="text-gray-900 fs-5 fw-bold">{{ $activePoolSummary['paid_count'] }} of {{ $activePoolSummary['total_members'] }} Paid</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                            <span class="text-success fs-2 fw-bolder">{{ format_rupee($activePoolSummary['total_collected']) }}</span>
                            <span class="text-muted fs-7 fw-bold">{{ $percent }}%</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: rgba(255,255,255,0.08); border-radius: 999px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 999px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-flush bg-body shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="symbol symbol-40px bg-light-warning rounded-3 me-3 d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-time fs-2 text-warning">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </span>
                            <div>
                                <span class="text-muted fs-7 fw-semibold d-block">Pending Collection</span>
                                <span class="text-gray-900 fs-5 fw-bold">{{ $activePoolSummary['pending_count'] + $activePoolSummary['partial_count'] }} Roommates</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="text-warning fs-2 fw-bolder">{{ format_rupee($activePoolSummary['remaining_target']) }}</span>
                            <span class="chip warn">Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-flush bg-body shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="symbol symbol-40px bg-light-info rounded-3 me-3 d-flex align-items-center justify-content-center">
                                <i class="ki-duotone ki-profile-user fs-2 text-info">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                </i>
                            </span>
                            <div>
                                <span class="text-muted fs-7 fw-semibold d-block">Per Flatmate Share</span>
                                <span class="text-gray-900 fs-5 fw-bold">{{ $activePool->pool_code }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <span class="text-gray-900 fs-2 fw-bolder">{{ format_rupee($activePool->target_amount_per_member) }}</span>
                            <button type="button" class="btn btn-sm btn-light-primary view-contributions" data-id="{{ $activePool->id }}">
                                Member Ledger
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Datatable Card -->
    <div class="card card-flush shadow-sm">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3 class="fs-4 fw-bolder text-gray-900 mb-0">Monthly Pool Cycles</h3>
            </div>

            <div class="card-toolbar d-flex align-items-center gap-3">
                <!-- Toolbar Search Box (Far Right Standard) -->
                <div class="d-flex align-items-center position-relative">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4 text-muted">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" id="q-monthly-pool" class="form-control form-control-solid w-250px ps-12" placeholder="Search..." autocomplete="off">
                </div>

                @can('monthly-pool add')
                    <button type="button" class="btn pri add-monthly-pool">
                        <i class="ki-duotone ki-plus fs-2 text-dark"></i>
                        <span>Create Monthly Pool</span>
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="monthly_pool_table" style="width: 100%;">
                    <thead>
                        <tr class="text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th style="width: 60px; text-align: center;">#</th>
                            <th style="min-width: 130px;">Pool Code</th>
                            <th style="min-width: 140px;">Month / Year</th>
                            <th style="min-width: 130px;">Per Member</th>
                            <th style="min-width: 180px;">Collection Progress</th>
                            <th style="width: 120px; text-align: center;">Status</th>
                            <th style="width: 140px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold">
                        <!-- Rendered via AJAX by monthly_pool.js -->
                    </tbody>
                </table>
            </div>

            <div id="monthly_pool_pagination" class="d-flex justify-content-between align-items-center pt-5">
                <div id="monthly_pool_info" class="text-muted fs-7"></div>
                <div id="monthly_pool_pages" class="d-flex gap-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Monthly Pool Modal -->
<div class="modal fade" id="add_monthly_pool_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px" role="document">
        <div class="modal-content append-monthly-pool-data-add"></div>
    </div>
</div>

<!-- Edit Monthly Pool Modal -->
<div class="modal fade" id="edit_monthly_pool_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px" role="document">
        <div class="modal-content append-monthly-pool-data-edit"></div>
    </div>
</div>

<!-- View Member Contributions Modal -->
<div class="modal fade" id="view_contributions_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-850px" role="document">
        <div class="modal-content append-contributions-modal-data"></div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="record_payment_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px" role="document">
        <div class="modal-content append-record-payment-modal-data"></div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirm_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-400px" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-gray-900 fw-bold">Delete Monthly Pool Cycle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-gray-700 fs-6 mb-0">Are you sure you want to delete this monthly pool cycle? This action will archive all associated contribution records.</p>
                <input type="hidden" id="delete_pool_id" value="">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-out" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger confirm-delete-btn">
                    <span>Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
