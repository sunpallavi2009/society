@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
                <h3>vouchers Ledgers</h3>
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
                        <div class="collapse" id="collapseProduct">
                            <div class="card card-body list-product-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date:</label>
                                        <input class="form-control" type="date" id="from_date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date:</label>
                                        <input class="form-control" type="date" id="to_date">
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
                                    <td id="total-debit" style="text-align: right;"></td>
                                    <td id="total-credit" style="text-align: right;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: left;"><b>Closing Balance</b></td>
                                    <td id="closing-debit" style="text-align: right;"></td>
                                    <td id="closing-credit" style="text-align: right;"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="text-align: left;"><b>Additional Sum</b></td>
                                    <td id="additional-sum-debit" style="text-align: right;"></td>
                                    <td id="additional-sum-credit" style="text-align: right;"></td>
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
                                openingBalance = balance - amount; // Calculate opening balance using the formula
                            }

                            // Prepend opening balance row to the data
                            var openingBalanceRow = {
                                voucher_date: '', 
                                credit_ledger: '<b>Opening Balance</b>', 
                                type: '', 
                                voucher_number: '', 
                                debit: openingBalance >= 0 ? openingBalance.toFixed(2) : '0.00', 
                                credit: openingBalance < 0 ? Math.abs(openingBalance).toFixed(2) : '0.00',
                                balance_amount: '',
                            };

                            if (openingBalance === 0) {
                                openingBalanceRow.debit = '0.00';
                                openingBalanceRow.credit = '0.00';
                            }

                            json.data.unshift(openingBalanceRow);
                            return json.data;
                        }
                },
                columns: [
                                 {data: 'voucher_date', name: 'voucher_date'},
                                {data: 'credit_ledger', name: 'credit_ledger'},
                                {
                                    data: 'type', 
                                    name: 'type',
                                    render: function(data, type, row, meta) {
                                        if (data === 'Bill' || data === 'Rcpt') {
                                            var companyGuid = '{{ $society->guid }}';
                                            var ledgerGuid = row.ledger_guid;
                                            var vchDate = moment(row.voucher_date).format('DD/MM/YYYY');
                                            var vchNumber = row.voucher_number;
                                            var url = 'http://ledger365.in:10000/get_vch_pdf?company_guid=' + companyGuid +
                                                '&ledger_guid=' + ledgerGuid +
                                                '&vch_date=' + vchDate +
                                                '&vch_number=' + vchNumber +
                                                '&vch_type=' + data;

                                            return '<a href="' + url + '" style="color: #337ab7;">' + data + '</a>';
                                        } else {
                                            return data;
                                        }
                                    }
                                },
                                {data: 'voucher_number', name: 'voucher_number'},
                                {data: 'debit', name: 'debit'},
                                {data: 'credit', name: 'credit'},
                                {
                                    data: 'balance_amount',
                                    name: 'balance_amount',
                                    render: function(data, type, row, meta) {
                                        if (isNaN(data) || data === null) {
                                            return "0.00";
                                        }
                                        var balance_amount = parseFloat(data);
                                        if (balance_amount === 0) {
                                            return "0.00";
                                        }
                                        balance_amount = balance_amount.toFixed(2); // Format to 2 decimal places
                                        balance_amount = balance_amount.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas for thousands
                                        return balance_amount; // Return formatted balance without currency symbol
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
                    'colvis',
                    // {
                    //     extend: 'searchBuilder',
                    //     config: {
                    //         columns: [0, 1, 2, 3, 4, 5], // Specify the searchable columns
                    //     }
                    // }
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

                $('#voucher-datatable tbody tr').each(function() {
                    var debit = parseFloat($(this).find('td:nth-child(5)').text().replace(/,/g, '')) || 0;
                    var credit = parseFloat($(this).find('td:nth-child(6)').text().replace(/,/g, '')) || 0;
                    totalDebit += debit;
                    totalCredit += credit;
                });

                $('#total-debit').text(totalDebit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#total-credit').text(totalCredit.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

                var totalBalance = totalCredit - totalDebit;

                // Check if the total balance is positive or negative
                if (totalBalance >= 0) {
                    $('#closing-debit').text(totalBalance.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#closing-credit').text('0.00');
                } else {
                    $('#closing-debit').text('0.00');
                    $('#closing-credit').text(Math.abs(totalBalance).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
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
            $('.dt-buttons').hide();
    
            // Toggle DataTable buttons visibility when collapse section is shown/hidden
            $('#collapseProduct').on('shown.bs.collapse', function () {
                $('.dt-buttons').show();
            }).on('hidden.bs.collapse', function () {
                $('.dt-buttons').hide();
            });
        });
    </script>
    
    
@endpush
