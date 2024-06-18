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
                        <div class="collapse show" id="collapseProduct">
                            <div class="card card-body list-product-body">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date:</label>
                                        <input class="form-control" type="date" id="from_date" value="{{ date('Y-m-01') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date:</label>
                                        <input class="form-control" type="date" id="to_date" value="{{ date('Y-m-01') }}">
                                    </div>
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
                                    <th>Total Vch</th>
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
                                    <th style="text-align: right;"></th>
                                    <th style="text-align: right;"></th>
                                    <th style="text-align: right;"></th>
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
                // ordering: false,
                ajax: {
                    url: "{{ route('memberOutstanding.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.guid = "{{ $societyGuid }}";
                        d.group = "{{ $group }}";
                        d.from_date = $('#from_date').val() || "{{ date('Y-m-d') }}"; // Default to current date
                        d.to_date = $('#to_date').val() || "{{ date('Y-m-d') }}"; 
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
                                { data: 'voucher_details', name: 'voucher_details' , className: 'dt-body-center'},
                                { data: 'voucher_date', name: 'voucher_date', render: function(data, type, row) {
                                    return data ? moment(data).format('DD-MM-YY') : '';
                                } },
                                { data: 'opening_balance', name: 'opening_balance' , className: 'dt-body-right'},
                                { data: 'amount_billed', name: 'amount_billed' , className: 'dt-body-right'},
                                { data: 'amount_received', name: 'amount_received' , className: 'dt-body-right'},
                                {
                                    data: 'this_year_balance',
                                    name: 'this_year_balance',
                                    className: 'dt-body-right',
                                    render: function(data, type, row, meta) {
                                        // Invert the sign
                                        return (-parseFloat(data)).toFixed(2);
                                    }
                                },
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
                    // 'colvis',
                    //             {
                    //                 extend: 'searchBuilder',
                    //                 config: {
                    //                     columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    //                 },
                    //                 i18n: {
                    //                     conditions: {
                    //                         date: {
                    //                             '=': 'Equals',
                    //                             '!=': 'Not equal',
                    //                             'before': 'Before',
                    //                             'after': 'After'
                    //                         }
                    //                     },
                    //                     date: {
                    //                         format: 'YYYY-MM-DD'
                    //                     }
                    //                 }
                    //             }
                ],
                order: [[1, 'asc']],
                paging: false, // Remove pagination



                ordering: true,

                // Column definition for sorting
                columnDefs: [
                    {
                        targets: [0, 1, 2, 3, 4, 5, 6, 7], // Apply sorting to these columns (index starts from 0)
                        orderable: true // Allow ordering (click to sort) for these columns
                    }
                ],


                

                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Helper function to sum and parse float values
                    var sumColumnData = function(columnIndex) {
                        return api.column(columnIndex, { page: 'current' }).data().reduce(function(acc, val) {
                            var parsedVal = parseFloat(val.toString().replace(/,/g, '') || 0);
                            return acc + parsedVal;
                        }, 0);
                    };

                    // Calculate totals
                    var OpeningBalTotal = sumColumnData(4);
                    var BilledAmountTotal = sumColumnData(5);
                    var InstrumentAmountTotal = sumColumnData(6);
                    var DueAmtTotal = sumColumnData(7);

                    // var DueAmtTotal = OpeningBalTotal + BilledAmountTotal;

                    // Format the totals and update the footer
                    $(api.column(4).footer()).html(Math.abs(OpeningBalTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $(api.column(5).footer()).html(Math.abs(BilledAmountTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $(api.column(6).footer()).html(Math.abs(InstrumentAmountTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $(api.column(7).footer()).html(Math.abs(DueAmtTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                },



            });

                $('#from_date, #to_date').on('change', function () {
                    var fromDate = $('#from_date').val();
                    var toDate = $('#to_date').val();
                    table.ajax.reload(); // Reload DataTable on date change
                });

                // Clear filters button functionality
                $('#clear-filters').click(function () {
                    $('#from_date, #to_date').val('');
                    table.ajax.reload(); // Reload DataTable to clear filters
                });

                // Search button functionality (redirects to URL)
               // Search button functionality (redirects to URL)
                $('#search').click(function () {
                    var fromDate = $('#from_date').val();
                    var toDate = $('#to_date').val();
                    var url = "{{ route('memberOutstanding.index') }}?from_date=" + fromDate + "&to_date=" + toDate + "&guid=" + "{{ $societyGuid }}";
                    window.location.href = url; // Redirect to filtered URL
                });


    
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
