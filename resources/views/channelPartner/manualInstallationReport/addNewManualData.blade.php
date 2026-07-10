@extends('layouts.adminLayout')

@section('css')
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="/assets/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-light: #f5f7fa;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f1f3f5;
            --card-bg: #ffffff;
        }

        body {
            background: var(--primary-light);
            color: var(--text-primary);
            font-size: 0.78rem;
            /* smaller global font */
            line-height: 1.3;
        }

        .page-header {
            background: #ffffff;
            padding: 0.7rem 0;
            margin-bottom: 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .page-header h1 {
            color: var(--text-primary);
            font-weight: 600;
            margin: 0;
            font-size: 1.05rem;
        }

        .page-header p {
            color: var(--text-secondary);
            margin: 0.25rem 0 0 0;
            font-size: 0.8rem;
        }

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-body {
            padding: 0.75rem;
            /* reduced */
        }

        .btn-group-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 0.75rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .btn-success,
        .btn-primary {
            background: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: none;
        }

        .btn-success:hover,
        .btn-primary:hover {
            background: #3b7dc4;
            border-color: #3b7dc4;
            color: #fff;
        }

        .btn-secondary {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: var(--hover-bg);
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
        }

        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0.55rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
            border-color: var(--primary-blue);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .table thead th {
            background: #f8f9fa;
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding: 0.9rem;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 0.85rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table tbody tr:hover {
            background-color: var(--hover-bg);
        }

        .table-responsive {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .dt-button {
            background: var(--primary-blue) !important;
            border: 1px solid var(--primary-blue) !important;
            border-radius: 6px !important;
            padding: 0.45rem 0.8rem !important;
            font-weight: 600 !important;
            color: #fff !important;
            font-size: 0.8rem !important;
            box-shadow: none !important;
        }

        .dt-button:hover {
            background: #3b7dc4 !important;
            border-color: #3b7dc4 !important;
        }

        .badge-yes {
            background: #e7f5ff;
            color: #1c7ed6;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-no {
            background: #fff5f5;
            color: #c92a2a;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .text-muted-custom {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .modal-content.glass-modal {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .modal-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .modal-footer {
            background: #f8f9fa;
            border-top: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }



        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 6px !important;
            padding: 0.35rem 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            background: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
            color: var(--text-primary) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 6px !important;
        }

        .select2-container--default .select2-selection--single.is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <h1><i class="fas fa-box me-2"></i>Manual Installation Report</h1>
            <p>Report manual installations</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form id="inventoryForm" action="{{ route('storeManualInstallation') }}" method="POST">
                    @csrf
                    <!-- Additional Details Form Section -->
                    <div class="mt-4">
                        <div class="row g-3">
                            <!-- Customer Details -->
                            <div class="col-md-6">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control"
                                    placeholder="Enter customer name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer Number</label>
                                <input type="text" name="customer_number" class="form-control"
                                    placeholder="Enter customer number" required>
                            </div>

                            <!-- Component Counts -->
                            <div class="col-md-4">
                                <label class="form-label">DCR Panel Count</label>
                                <input type="number" min="0" name="dcr_panel_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Inverter Count</label>
                                <input type="number" min="0" name="inverter_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Meter Count</label>
                                <input type="number" min="0" name="meter_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Structure Count</label>
                                <input type="number" min="0" name="structure_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ACDB/DCDB Count</label>
                                <input type="number" min="0" name="acdb_dcdb_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Wire Count</label>
                                <input type="number" min="0" name="wire_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nut Count</label>
                                <input type="number" min="0" name="nut_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">J-Hook Count</label>
                                <input type="number" min="0" name="jhook_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Other 1 Name</label>
                                <input type="text" name="other_1_name" class="form-control"
                                    placeholder="Enter other component name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Other 1 Count</label>
                                <input type="number" min="0" name="other_1_count" class="form-control" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Other 2 Name</label>
                                <input type="text" name="other_2_name" class="form-control"
                                    placeholder="Enter other component name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Other 2 Count</label>
                                <input type="number" min="0" name="other_2_count" class="form-control" placeholder="0">
                            </div>

                            <!-- Bill Details -->
                            <div class="col-md-6">
                                <label class="form-label">Bill Number</label>
                                <input type="text" name="bill_number" class="form-control" placeholder="Enter bill number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bill Amount</label>
                                <input type="number" step="0.01" min="0" name="bill_amount" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">GST Amount</label>
                                <input type="number" step="0.01" min="0" name="gst_amount" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Amount</label>
                                <input type="number" step="0.01" min="0" name="total_amount" class="form-control"
                                    placeholder="0.00">
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label">Installation Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="installation_status" value="0"
                                        checked>
                                    <label class="form-check-label">Pending</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="installation_status" value="1">
                                    <label class="form-check-label">Completed</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Net Metering Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="net_metering_status" value="0"
                                        checked>
                                    <label class="form-check-label">Pending</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="net_metering_status" value="1">
                                    <label class="form-check-label">Completed</label>
                                </div>
                            </div>

                            <!-- Payments -->
                            <div class="col-md-4">
                                <label class="form-label">Payment 1</label>
                                <input type="number" step="0.01" min="0" name="payment_1" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment 2</label>
                                <input type="number" step="0.01" min="0" name="payment_2" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment 3</label>
                                <input type="number" step="0.01" min="0" name="payment_3" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stable Deposit</label>
                                <input type="number" step="0.01" min="0" name="stable_deposit" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Deposit</label>
                                <input type="number" step="0.01" min="0" name="other_deposit" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Cost</label>
                                <input type="number" step="0.01" min="0" name="total_cost" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Deposit</label>
                                <input type="number" step="0.01" min="0" name="total_deposit" class="form-control"
                                    placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pending Amount</label>
                                <input type="number" step="0.01" min="0" name="pending_amount" class="form-control"
                                    placeholder="0.00">
                            </div>
                        </div>
                    </div>


                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Insert Data</button>
                        <button type="reset" class="btn btn-secondary">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true
        });

        $bill_amount_field = document.getElementsByName('bill_amount')[0];
        $gst_field = document.getElementsByName('gst_amount')[0];
        $total_amount_field = document.getElementsByName('total_amount')[0];
        $bill_amount_field.addEventListener('input', function() {
            const gst_value = parseFloat($bill_amount_field.value*0.18) || 0;
            const bill_amount_value = parseFloat(this.value) || 0;
            $gst_field.value = gst_value.toFixed(2);
            $total_amount_field.value = (gst_value + bill_amount_value).toFixed(2);
 
        });

$payment_1_field = document.getElementsByName('payment_1')[0];
$payment_2_field = document.getElementsByName('payment_2')[0];
$payment_3_field = document.getElementsByName('payment_3')[0];
$payment_1_field.addEventListener('input', calculateTotalDeposit);
$payment_2_field.addEventListener('input', calculateTotalDeposit);
$payment_3_field.addEventListener('input', calculateTotalDeposit);


function calculateTotalDeposit() {
    $stable_deposit_value = document.getElementsByName('stable_deposit')[0];
    $other_deposit_field = document.getElementsByName('other_deposit')[0];
    const other_deposit_value = parseFloat($other_deposit_field.value) || 0
    const payment_1_value = parseFloat($payment_1_field.value) || 0;
    const payment_2_value = parseFloat($payment_2_field.value) || 0;
    const payment_3_value = parseFloat($payment_3_field.value) || 0;
    const total_deposit = payment_1_value + payment_2_value + payment_3_value + other_deposit_value;
    $stable_deposit_value.value = total_deposit.toFixed(2);
}

    });
</script>

@endsection