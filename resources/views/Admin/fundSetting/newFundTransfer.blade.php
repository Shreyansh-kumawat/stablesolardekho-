@extends('layouts.adminLayout')

@section('title', 'Fund Transfer')

@section('css')
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-control { width: 100% !important; }
        .text-muted { display: block; margin-top: 0.25rem; }
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
        }

        body { background: var(--primary-light); color: var(--text-primary); }

        .page-header {
            background: #ffffff;
            padding: 1.5rem 0;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .page-header h1 { color: var(--text-primary); font-weight: 600; margin: 0; font-size: 1.25rem; }
        .page-header p { color: var(--text-secondary); margin: 0.35rem 0 0 0; font-size: 0.9rem; }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 1.5rem;
        }

        .form-label { font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.85rem; }
        .form-control, .form-select {
            border-radius: 6px !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.55rem 0.75rem !important;
            font-size: 0.9rem !important;
            min-height: 42px;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(74,144,226,0.15);
            border-color: var(--primary-blue);
        }

        .btn-primary-theme {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-primary-theme:hover { background: #3b7dc4; border-color: #3b7dc4; color: #fff; }

        .btn-secondary-theme {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
        }
        .btn-secondary-theme:hover { background: var(--hover-bg); }

        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single {
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 0.75rem !important;
        }
        .select2-container .select2-selection__arrow { height: 42px !important; right: 10px !important; }

        .modal-content.glass-modal {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .modal-header, .modal-footer {
            background: #f8f9fa;
            border-color: var(--border-color);
        }
    </style>
@endsection

@section('content')
<div class="page-header">
    <div class="container-fluid">
        <h1><i class="fas fa-money-check-alt me-2"></i>Fund Transfer</h1>
        <p>Transfer funds to a channel partner with purpose and remarks</p>
    </div>
</div>

<div class="card">
    <form id="fundTransferForm" method="POST" action="{{ route('storeFundTransfer') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Channel Partner</label>
                <select name="cp_id" id="cp_id" class="select2-element form-control" required>
                    <option value="">Select Channel Partner</option>
                    @foreach($cp_list as $cp)
                        <option value="{{ $cp->id }}">{{ $cp->cp_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Purpose</label>
                <select name="purpose" id="purpose" class="select2-element form-control" required>
                    <option value="">Select Purpose</option>
                    @foreach(($purposes ?? ['Advance', 'Settlement', 'Commission', 'Refund', 'Other']) as $purpose)
                        <option value="{{ $purpose }}">{{ $purpose }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Amount</label>
                <input type="text" name="amount" id="amount" class="form-control"
                       placeholder="Enter amount" inputmode="numeric" required>
                <small class="text-muted">Only numbers allowed</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" placeholder="Enter remark">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="button" class="btn-primary-theme" id="openConfirmBtn">
                <i class="fas fa-paper-plane me-2"></i>Transfer
            </button>
            <a href="{{ route('fundTransactionList') }}" class="btn-secondary-theme">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
</div>
@endsection
<!-- Remove Confirm Modal block entirely -->

@section('js')
    <script src="/assets/js/select2.min.js"></script>
    <script src="/assets/js/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('.select2-element').select2({
                width: 'resolve',
                dropdownParent: $(document.body),
                minimumResultsForSearch: 6
            });

            const amountInput = document.getElementById('amount');
            amountInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            document.getElementById('openConfirmBtn').addEventListener('click', function () {
                const cpText = $('#cp_id option:selected').text();
                const purposeText = $('#purpose option:selected').text();
                const amount = document.getElementById('amount').value;
                const remark = document.getElementById('remark').value || '-';

                if (!$('#cp_id').val() || !$('#purpose').val() || !amount) {
                    document.getElementById('fundTransferForm').reportValidity();
                    return;
                }

                Swal.fire({
                    title: 'Confirm Transfer',
                    html: `
                        <div style="text-align:left;">
                            <p><strong>Channel Partner:</strong> ${cpText}</p>
                            <p><strong>Purpose:</strong> ${purposeText}</p>
                            <p><strong>Amount:</strong> ${amount}</p>
                            <p><strong>Remark:</strong> ${remark}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Transfer',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#4A90E2'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('fundTransferForm').submit();
                    }
                });
            });
        });
    </script>
@endsection