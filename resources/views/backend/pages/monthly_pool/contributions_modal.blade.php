<div class="modal-header border-0 pb-0">
    <div>
        <h5 class="modal-title text-gray-900 fw-bold fs-4">
            Member Contribution Ledger — {{ $pool->month_name }} {{ $pool->target_year }}
        </h5>
        <span class="text-muted fs-7">{{ $pool->pool_code }} &bull; Total Target: {{ format_rupee($total_target) }} &bull; Collected: <strong class="text-success">{{ format_rupee($total_collected) }}</strong> ({{ $percentage }}%)</span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body py-4">
    <!-- Progress Bar -->
    <div class="progress mb-4" style="height: 8px; background-color: rgba(255,255,255,0.08); border-radius: 999px;">
        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background: linear-gradient(135deg, #10B981, #06B6D4); border-radius: 999px;"></div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-4">
            <thead>
                <tr class="text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th style="width: 50px; text-align: center;">#</th>
                    <th style="min-width: 160px;">Flatmate</th>
                    <th style="min-width: 100px;">Assigned</th>
                    <th style="min-width: 100px;">Deposited</th>
                    <th style="width: 110px; text-align: center;">Status</th>
                    <th style="min-width: 130px;">Mode / Ref</th>
                    <th style="width: 130px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 fw-semibold">
                @forelse($contributions as $index => $row)
                    @php
                        $user = $row->user;
                        $isPaid = $row->payment_status == 1;
                        $isPartial = $row->payment_status == 3;
                    @endphp
                    <tr>
                        <!-- 1-Based Row Index -->
                        <td style="text-align: center; color: var(--muted, #64748b);">{{ $index + 1 }}</td>

                        <!-- Roommate Profile -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-35px me-3">
                                    <img src="{{ $user ? $user->avatar_url : asset('backend/media/avatars/blank.png') }}" alt="{{ $user->name ?? '-' }}" />
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-900 fw-bold fs-6">{{ $user->name ?? '-' }}</span>
                                    <span class="text-muted fs-8">{{ $user->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Expected Amount -->
                        <td>{{ format_rupee($row->expected_amount) }}</td>

                        <!-- Deposited Amount -->
                        <td>
                            <strong class="{{ $isPaid ? 'text-success' : ($isPartial ? 'text-warning' : 'text-danger') }}">
                                {{ format_rupee($row->paid_amount) }}
                            </strong>
                        </td>

                        <!-- Status Badge -->
                        <td style="text-align: center;">
                            @if($isPaid)
                                <span class="chip ok">Paid</span>
                            @elseif($isPartial)
                                <span class="chip warn">Partial</span>
                            @else
                                <span class="chip dmg">Pending</span>
                            @endif
                        </td>

                        <!-- Payment Mode / Reference -->
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fs-7 text-gray-800">{{ $row->payment_mode_name }}</span>
                                @if($row->transaction_reference)
                                    <span class="text-muted fs-8">{{ $row->transaction_reference }}</span>
                                @else
                                    <span class="text-muted fs-8">-</span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
                        <td style="text-align: center;">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Record Payment Button -->
                                <button type="button" class="btn btn-sm btn-icon btn-light-success record-payment-btn" 
                                        data-id="{{ $row->id }}" title="Record Payment">
                                    <i class="ki-outline ki-dollar fs-5"></i>
                                </button>

                                <!-- WhatsApp Reminder for unpaid members -->
                                @if(!$isPaid && $user && !empty($user->phone))
                                    <button type="button" class="btn btn-sm btn-icon btn-light-warning send-whatsapp-reminder" 
                                            data-id="{{ $row->id }}" title="Send WhatsApp Reminder">
                                        <i class="fa-brands fa-whatsapp fs-5"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No flatmate contributions found for this cycle.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal-footer border-0 pt-0">
    <button type="button" class="btn btn-out" data-bs-dismiss="modal">Close</button>
</div>
