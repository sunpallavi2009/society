@extends('layouts.main')
@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
                <a href="javascript:void(0)" id="back-button">
                    <div class="col-sm-1 card">
                        <div class="card-header pb-0 p-0" style="background-color: none;">
                        <div class="card-header-right top-0">
                            <ul class="list-unstyled card-option">
                            <li>
                                <div><i class="icon-settings icon-angle-double-left"></i></div>
                            </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item active">vouchers Ledgers list</li>
                </ol>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center text-white">
                        <h6 class="text-black">
                            @if($society)
                                <h3><b>{{ $society->name }}</b></h3>
                                <h6>{{ $society->address1 }}</h6>
                            @else
                                <h6>No Society Information Found</h6>
                            @endif

                            @if($members && $members->count() > 0)
                                @foreach ($members as $member)
                                    <div>
                                        <h6>{{ $member->name }}</h6>
                                        <h6>{{ $member->alias1 }}</h6>
                                    </div>
                                @endforeach
                            @else
                                <h6>No Member Information Found</h6>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="list-product-header">
                        <div> 
                            <div class="light-box"><a data-bs-toggle="collapse" href="#collapseProduct" role="button" aria-expanded="false" aria-controls="collapseProduct"><i class="filter-icon show" data-feather="filter"></i><i class="icon-close filter-close hide"></i></a></div>
                        </div>
                        <div class="collapse show" id="collapseProduct">
                            <div class="card card-body list-product-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date:</label>
                                        <input class="form-control" type="date" id="from_date" value="{{ date('Y-m-01', strtotime('-2 months')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date:</label>
                                        <input class="form-control" type="date" id="to_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-4 align-self-end">
                                        <button id="search" class="btn btn-primary">Search</button>
                                        <button id="clear-filters" class="btn btn-secondary">Clear Filters</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="voucher-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Voucher Type</th>
                                    <th>Voucher Number</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: left;"><b>Total</b></td>
                                    <td id="total-debit" style="text-align: right;font-weight: bolder;"></td>
                                    <td id="total-credit" style="text-align: right;font-weight: bolder;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: left;"><b>Closing Balance</b></td>
                                    <td id="closing-debit" style="text-align: right;font-weight: bolder;"></td>
                                    <td id="closing-credit" style="text-align: right;font-weight: bolder;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: left;"></td>
                                    <td id="additional-sum-debit" style="text-align: right;font-weight: bolder;"></td>
                                    <td id="additional-sum-credit" style="text-align: right;font-weight: bolder;"></td>
                                    <td></td>
                                </tr>
                            </tfoot>  
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
    <!-- Include DataTables Buttons and SearchBuilder JS -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>

    <script>
        $(document).ready(function() {
            var openingBalance = 0.00; 
            
            // Initialize DataTable
            var table = $('#voucher-datatable').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('vouchers.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.ledger_guid = "{{ request()->query('ledger_guid') }}";
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                    dataSrc: function(json) {
                        var openingBalance = 0.00;

                        if (json.data.length > 0) {
                            var balance = parseFloat(json.data[0].balance_amount);
                            var amount = parseFloat(json.data[0].amount);

                            if (isNaN(balance) || isNaN(amount)) {
                                openingBalance = 0.00;
                            } else {
                                openingBalance = balance - amount; // Calculate opening balance using the formula
                            }
                        }

                        // Prepend opening balance row to the data
                        var openingBalanceRow = {
                            voucher_date: '', // Set to empty string
                            credit_ledger: '<b>Opening Balance</b>', 
                            type: '', 
                            voucher_number: '', 
                            debit: openingBalance < 0 ? '<b>' + Math.abs(openingBalance).toFixed(2) + '</b>' : '', 
                            credit: openingBalance > 0 ? '<b>' + (-openingBalance).toFixed(2) + '</b>' : '', 
                            balance_amount: '', // Set to empty string
                        };

                        if (openingBalance === 0) {
                            openingBalanceRow.debit = '';
                            openingBalanceRow.credit = '';
                        }

                        json.data.unshift(openingBalanceRow);
                        return json.data;
                    }


                },
                columns: [
                    {
                        data: 'voucher_date',
                        name: 'voucher_date',
                        render: function(data, type, row) {
                            if (data) {
                                return moment(data).format('DD-MM-YY');
                            }
                            return ''; 
                        }
                    },
                    {data: 'credit_ledger', name: 'credit_ledger'},
                    {
                        data: 'type', 
                        name: 'type',
                        className: 'dt-body-center',
                        render: function(data, type, row, meta) {
                            if (data === 'Bill' || data === 'Rcpt') {
                                var companyGuid = '{{ $society ? $society->guid : '' }}';
                                var ledgerGuid = row.ledger_guid;
                                var vchDate = moment(row.voucher_date).format('DD/MM/YYYY');
                                var vchNumber = row.voucher_number;

                                if (companyGuid) {
                                    var url = 'http://ledger365.in:10000/get_vch_pdf?company_guid=' + companyGuid +
                                        '&ledger_guid=' + ledgerGuid +
                                        '&vch_date=' + vchDate +
                                        '&vch_number=' + vchNumber +
                                        '&vch_type=' + data;

                                    return '<a href="' + url + '" style="color: #337ab7;">' + data + '</a>';
                                } else {
                                    return data; // Handle the case where companyGuid is empty or null
                                }
                            } else {
                                return data;
                            }
                        }
                    },

                    {data: 'voucher_number', name: 'voucher_number', className: 'dt-body-center',},
                    {data: 'debit', name: 'debit', className: 'dt-body-right',},
                    {data: 'credit', name: 'credit', className: 'dt-body-right',},
                    {
                        data: 'balance_amount',
                        name: 'balance_amount',
                        className: 'dt-body-right',
                        render: function(data, type, row, meta) {
                            if (isNaN(data) || data === null || data === "NaN") {
                                return ""; // Return empty string if data is NaN, null, or "NaN"
                            } else {
                                var balance_amount = parseFloat(data);
                                if (isNaN(balance_amount)) {
                                    return ""; // Return empty string if parsed balance_amount is NaN
                                } else {
                                    if (balance_amount === 0) {
                                        return "0.00";
                                    }
                                    balance_amount = balance_amount * -1; // Change sign
                                    balance_amount = balance_amount.toFixed(2); // Format to 2 decimal places
                                    balance_amount = balance_amount.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas for thousands
                                    return balance_amount; // Return formatted balance without currency symbol
                                }
                            }
                        }
                    }


                ],
                dom: 'Blfrtip', // Add the letter 'B' for Buttons
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible',
                            modifier: {
                                page: 'all'
                            }
                        }
                    },
                    'colvis'
                ],
                order: [[0, 'asc']],
                paging: false, // Remove pagination
                drawCallback: function(settings) {
                    calculateClosingBalance();
                    calculateAdditionalSum();
                }
            });

            $('#search').click(function() {
                table.draw();
            });

            $('#clear-filters').click(function () {
                $("#from_date").val('').trigger('change');
                $("#to_date").val('').trigger('change');
                table.search('').draw();
            });

            function calculateClosingBalance() {
                var totalDebit = 0.00;
                var totalCredit = 0.00;

                // Calculate total debit and credit from table rows
                $('#voucher-datatable tbody tr').each(function() {
                    var debit = parseFloat($(this).find('td:nth-child(5)').text().replace(/,/g, '')) || 0;
                    var credit = parseFloat($(this).find('td:nth-child(6)').text().replace(/,/g, '')) || 0;
                    totalDebit += debit;
                    totalCredit += credit;
                });

                // Update total debit and credit in the UI
                $('#total-debit').text(totalDebit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#total-credit').text(totalCredit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                // Calculate total balance
                var totalBalance = totalCredit - totalDebit;

                // Display closing balances
                if (totalBalance >= 0) {
                    $('#closing-debit').text(totalBalance.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#closing-credit').text('0.00');
                } else {
                    $('#closing-debit').text('0.00');
                    $('#closing-credit').text(Math.abs(totalBalance).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }

                // Hide closing debit and credit if they are '0.00'
                if ($('#closing-debit').text().trim() === '0.00') {
                    $('#closing-debit').text('');
                }
                if ($('#closing-credit').text().trim() === '0.00') {
                    $('#closing-credit').text('');
                }
            }


            function calculateAdditionalSum() {
                var additionalSumDebit = 0.00;
                var additionalSumCredit = 0.00;

                $('#voucher-datatable tbody tr').each(function() {
                    var debit = parseFloat($(this).find('td:nth-child(5)').text().replace(/,/g, '')) || 0;
                    var credit = parseFloat($(this).find('td:nth-child(6)').text().replace(/,/g, '')) || 0;
                    additionalSumDebit += debit;
                    additionalSumCredit += credit;
                });

                var closingDebit = parseFloat($('#closing-debit').text().replace(/,/g, '')) || 0;
                var closingCredit = parseFloat($('#closing-credit').text().replace(/,/g, '')) || 0;

                additionalSumDebit += closingDebit;
                additionalSumCredit += closingCredit;

                $('#additional-sum-debit').text(additionalSumDebit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#additional-sum-credit').text(additionalSumCredit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }

            // Initial calculation
            calculateClosingBalance();
            calculateAdditionalSum();

            // Hide DataTable buttons initially
            $('.dt-buttons').show();

            // Toggle DataTable buttons visibility when collapse section is shown/hidden
            $('#collapseProduct').on('shown.bs.collapse', function () {
                $('.dt-buttons').show();
            }).on('hidden.bs.collapse', function () {
                $('.dt-buttons').hide();
            });
        });

    </script>
    
    
@endpush
