<form id="edit-save-monthly-pool" method="POST" action="{{ route('edit-save-monthly-pool') }}">
    @csrf
    <input type="hidden" name="id" value="{{ $item->id }}">

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-gray-900 fw-bold fs-4">Edit Monthly Pool ({{ $item->pool_code }})</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body py-5">
        <div class="row g-4">
            <!-- Pool Code (Display Only) -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800">Pool Reference Code</label>
                <input type="text" class="form-control form-control-solid bg-secondary" value="{{ $item->pool_code }}" readonly>
            </div>

            <!-- Cycle Month / Year (Display Only) -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800">Cycle Period</label>
                <input type="text" class="form-control form-control-solid bg-secondary" value="{{ $item->month_name }} {{ $item->target_year }}" readonly>
            </div>

            <!-- Target Amount Per Member -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Target Amount Per Member (₹)</label>
                <input type="number" name="target_amount_per_member" class="form-control form-control-solid" value="{{ $item->target_amount_per_member }}" step="1" min="1" required>
            </div>

            <!-- Opening Balance Rollover -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800">Opening Balance (₹)</label>
                <input type="number" name="opening_balance" class="form-control form-control-solid" value="{{ $item->opening_balance }}" step="0.01" min="0">
            </div>

            <!-- Pool Cycle Status -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Cycle Status</label>
                <select name="pool_status" class="form-select form-select-solid" required>
                    <option value="1" {{ $item->pool_status == 1 ? 'selected' : '' }}>Ongoing</option>
                    <option value="2" {{ $item->pool_status == 2 ? 'selected' : '' }}>Settled and Closed</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-bold text-gray-800">Cycle Notes / Remarks</label>
                <textarea name="notes" class="form-control form-control-solid" rows="3">{{ $item->notes }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-out" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn pri submitbtn" id="kt_edit_pool_submit_btn">
            <span class="indicator-label">Update Pool</span>
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
