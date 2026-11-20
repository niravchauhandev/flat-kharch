<form id="add-save-monthly-pool" method="POST" action="{{ route('add-save-monthly-pool') }}">
    @csrf
    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-gray-900 fw-bold fs-4">Initialize Monthly Pool Cycle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body py-5">
        <div class="row g-4">
            <!-- Target Month -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Target Month</label>
                <select name="target_month" class="form-select form-select-solid" required>
                    @foreach(config('constants.MONTHS', []) as $monthNumber => $monthName)
                        <option value="{{ $monthNumber }}" {{ $monthNumber == ($nextMonth ?? date('n')) ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Target Year -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Target Year</label>
                <input type="number" name="target_year" class="form-control form-control-solid" value="{{ $currentYear ?? date('Y') }}" min="2020" max="2099" required>
            </div>

            <!-- Target Amount Per Flatmate -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Target Amount Per Member (₹)</label>
                <input type="number" name="target_amount_per_member" class="form-control form-control-solid" step="1" min="1" required>
            </div>

            <!-- Opening Balance Rollover -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800">Opening Balance Rollover (₹)</label>
                <input type="number" name="opening_balance" class="form-control form-control-solid" step="0.01" min="0" value="0.00">
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-bold text-gray-800">Cycle Notes / Instructions</label>
                <textarea name="notes" class="form-control form-control-solid" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-out" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn pri submitbtn" id="kt_add_pool_submit_btn">
            <span class="indicator-label">Create Pool Cycle</span>
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
