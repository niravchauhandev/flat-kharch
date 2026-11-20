<form id="save-contribution-payment-form">
    @csrf
    <input type="hidden" name="action" value="save-contribution-payment">
    <input type="hidden" name="contribution_id" value="{{ $contribution->id }}">
    <input type="hidden" name="monthly_pool_id" value="{{ $contribution->monthly_pool_id }}">

    <div class="modal-header border-0 pb-0">
        <h5 class="modal-title text-gray-900 fw-bold fs-4">Record Pool Deposit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body py-5">
        <div class="row g-4">
            <!-- Roommate Info Card -->
            <div class="col-12">
                <div class="p-3 rounded bg-secondary d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-7 text-muted d-block">Flatmate</span>
                        <strong class="fs-6 text-gray-900">{{ $contribution->user->name ?? '-' }}</strong>
                    </div>
                    <div class="text-end">
                        <span class="fs-7 text-muted d-block">Target Share</span>
                        <strong class="fs-6 text-primary">{{ format_rupee($contribution->expected_amount) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Paid Amount -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Amount Deposited (₹)</label>
                <input type="number" name="paid_amount" class="form-control form-control-solid" value="{{ $contribution->paid_amount > 0 ? $contribution->paid_amount : $contribution->expected_amount }}" step="0.01" min="0" required>
            </div>

            <!-- Payment Mode -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Payment Mode</label>
                <select name="payment_mode" class="form-select form-select-solid" required>
                    <option value="2" {{ $contribution->payment_mode == 2 ? 'selected' : '' }}>UPI / GPay / PhonePe</option>
                    <option value="1" {{ $contribution->payment_mode == 1 ? 'selected' : '' }}>Cash to Drawer</option>
                    <option value="3" {{ $contribution->payment_mode == 3 ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="4" {{ $contribution->payment_mode == 4 ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Transaction Reference ID -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800">Transaction Ref / UPI UTR</label>
                <input type="text" name="transaction_reference" class="form-control form-control-solid" value="{{ $contribution->transaction_reference }}">
            </div>

            <!-- Payment Date -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-bold text-gray-800 required">Payment Verification Date</label>
                <input type="date" name="payment_date" class="form-control form-control-solid" value="{{ $contribution->payment_date ? $contribution->payment_date->format('Y-m-d') : date('Y-m-d') }}" required>
            </div>

            <!-- Notes -->
            <div class="col-12">
                <label class="form-label fw-bold text-gray-800">Notes / Remarks</label>
                <textarea name="notes" class="form-control form-control-solid" rows="2">{{ $contribution->notes }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-out" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn pri submitbtn" id="kt_record_payment_submit_btn">
            <span class="indicator-label">Save Deposit</span>
            <span class="indicator-progress">
                Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
