@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
                <h3>Member Outstanding</h3>
            </div>
            <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item active">Member Outstanding</li>
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
                    <div class="text-center text-black">
                        <h6 class="text-black">
                            @foreach ($society as $company)
                                <h3><b>{{ $company->name }}</b></h3>
                                <h6>{{ $company->address1 }}</h6>
                            @endforeach
                            <p> Member Outstanding </p>
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
                                        <input class="form-control" type="date" id="from_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date:</label>
                                        <input class="form-control" type="date" id="to_date" value="{{ date('Y-m-d') }}">
                                    </div>
                                    {{-- <div class="col-md-4 align-self-end">
                                        <button id="search" class="btn btn-primary">Search</button>
                                        <button id="clear-filters" class="btn btn-secondary">Clear Filters</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="memberOutstanding-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Alias</th>
                                    <th>Vch No.</th>
                                    <th>From Date</th>
                                    <th>Opening Bal</th>
                                    <th>Billed Amount</th>
                                    <th>Received</th>
                                    <th>Due Amt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th colspan="3"></th>
                                    <th></th>
                                    <th></th>
                                    <th>0.00</th>
                                    <th></th>
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
            // Initialize DataTable
            var table = $('#memberOutstanding-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('memberOutstanding.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.guid = "{{ $societyGuid }}";
                        d.group = "{{ $group }}";
                        var fromDate = $('#from_date').val();
                        var toDate = $('#to_date').val();
                        d.from_date = fromDate;
                        d.to_date = toDate;
                    }
                },
                columns: [
                                {
                                    data: 'name',
                                    name: 'name',
                                    render: function(data, type, row, meta) {
                                        var url = "{{ route('vouchers.index') }}?ledger_guid=" + row.guid;
                                        return '<a href="' + url + '" style="color: #337ab7;">' + data + '</a>';
                                    }
                                },
                                { data: 'alias1', name: 'alias1' },
                                { data: 'voucher_number', name: 'voucher_number' },
                                {
                                    data: null,
                                    name: 'from_date',
                                    render: function(data, type, row, meta) {
                                        var fromDate = $('#from_date').val();
                                        return fromDate;
                                    }
                                },
                                { data: 'amount', name: 'amount' },
                                {
                                    data: 'this_year_balance',
                                    name: 'this_year_balance',
                                    render: function(data, type, row, meta) {
                                        return Math.abs(data).toFixed(2); // Remove the sign and format to 2 decimal places
                                    }
                                },
                                {
                                    data: null,
                                    name: 'received',
                                    render: function(data, type, row, meta) {
                                        return '0.00'; // Always display Received as 0.00
                                    }
                                },
                                {
                                    data: null,
                                    name: 'due_amt',
                                    render: function(data, type, row, meta) {
                                        var openingBal = parseFloat(Math.abs(row.this_year_balance));
                                        var billAmount = parseFloat(row.amount);
                                        if (!isNaN(openingBal) && !isNaN(billAmount)) {
                                            var due_amt = openingBal + billAmount;
                                            if (!isNaN(due_amt)) {
                                                return due_amt.toFixed(2);
                                            } else {
                                                return "N/A"; // Return "Not Available" if outstanding is not a number
                                            }
                                        } else {
                                            return "N/A";
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
                    'colvis',
                                {
                                    extend: 'searchBuilder',
                                    config: {
                                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                                    },
                                    i18n: {
                                        conditions: {
                                            date: {
                                                '=': 'Equals',
                                                '!=': 'Not equal',
                                                'before': 'Before',
                                                'after': 'After'
                                            }
                                        },
                                        date: {
                                            format: 'YYYY-MM-DD'
                                        }
                                    }
                                }
                ],
                order: [[0, 'asc']],
                paging: false, // Remove pagination


                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Calculate the total balance for the current page
                    var OpeningBalTotal = api.column(4, { page: 'current' }).data().reduce(function (acc, val) {
                        return acc + parseFloat(val.replace(/,/g, '') || 0);
                    }, 0);
                    OpeningBalTotal = Math.abs(OpeningBalTotal); // Ensure the total balance is positive

                    // Calculate the total billed amount for the current page
                    var BilledAmountTotal = api.column(5, { page: 'current' }).data().reduce(function (acc, val) {
                        return acc + parseFloat(val.replace(/,/g, '') || 0);
                    }, 0);
                    BilledAmountTotal = Math.abs(BilledAmountTotal); // Ensure the total billed amount is positive

                    // Calculate the total due amount for the current page
                    var DueAmtTotal = OpeningBalTotal + BilledAmountTotal;

                    // Format the totals and update the footer
                    $(api.column(4).footer()).html(OpeningBalTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $(api.column(5).footer()).html(BilledAmountTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $(api.column(7).footer()).html(DueAmtTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
               

            });

            $('#from_date, #to_date').on('change', function() {
                table.ajax.reload();
            });

            // Clear filters button
            $('#clear-filters').click(function() {
                $("#name").val('').trigger('change');
                dataTable.search('').draw();
            });

            // Search button
            // Search button
            $('#search').click(function() {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var url = "{{ route('memberOutstanding.index') }}?from_date=" + fromDate + "&to_date=" + toDate + "&guid=" + "{{ $societyGuid }}";
                
                // Set the values of the DataTables search inputs
                table.columns(3).search(fromDate).draw(); // 3 is the index of the 'From Date' column
                table.columns(4).search(toDate).draw(); // 4 is the index of the 'To Date' column
                
                // Redirect to the URL
                window.location.href = url;
            });

    
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
