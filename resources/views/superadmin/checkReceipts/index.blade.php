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
                    <li class="breadcrumb-item active">Check Receipts list</li>
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
                            <p>Check Receipts</p>
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
                    </div>
                    <div class="">
                        <table id="ledger-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Name</th>
                                    <th>Alias</th>
                                    <th>Vch No.</th>
                                    <th>Inst. No. / Check No.</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th colspan="5"></th>
                                    <th style="text-align:right"></th>
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
            var table = $('#ledger-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('checkReceipts.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.guid = "{{ $societyGuid }}";
                        d.group = "{{ $group }}"; // Pass the group parameter
                        d.from_date = $('#from_date').val() || "{{ date('Y-m-d') }}"; // Default to current date
                        d.to_date = $('#to_date').val() || "{{ date('Y-m-d') }}";
                    },
                     error: function(xhr, error, thrown) {
                        if (xhr.status == 404) {
                            $('#ledger-datatable').DataTable().clear().draw();
                            $('#ledger-datatable').DataTable().destroy();
                            $('#ledger-datatable tbody').html('<tr><td colspan="7" class="text-center">Data not found</td></tr>');
                        }
                    }
                },
                columns: [
                    // { data: 'ledger_guid', name: 'ledger_guid' },
                    { data: 'instrument_date', name: 'instrument_date',
                        render: function(data, type, row) {
                            if (data) {
                                return moment(data).format('DD-MM-YY');
                            }
                            return ''; 
                        }
                    },
                    { 
                        data: 'combined_field', // Use the combined_field property for sorting
                        render: function(data, type, row) {
                            var content = row.credit_ledger + '<br>' + row.narration;
                            if (row.NAR) {
                                content += '<br>' + row.NAR;
                            }
                            return content;
                        },
                        name: 'combined_field' // Use the same name for sorting
                    },
                    { data: 'ledger_name', name: 'ledger_name',
                        render: function(data, type, row, meta) {
                            var url = "{{ route('vouchers.index') }}?ledger_guid=" + row.guid;
                            return '<a href="' + url + '" style="color: #337ab7;">' + data + '</a>';
                        } 
                    },
                    { data: 'alias1', name: 'alias1' },
                    { data: 'voucher_number', name: 'voucher_number', className: 'dt-body-center' },
                    { data: 'instrument_number', name: 'instrument_number', className: 'dt-body-center'},
                    // { data: 'type', name: 'type' },
                    { data: 'instrument_amount', name: 'instrument_amount', className: 'dt-body-center', render: function(data) {
                        // Remove minus sign if present
                        var amount = parseFloat(data.replace('-', ''));
                        return amount.toLocaleString('en-US', { minimumFractionDigits: 2 }); // Format as currency or numeric
                    }},
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
                    // 'searchBuilder',
                    // {
                    //     text: 'Reset Column Order',
                    //     action: function () {
                    //         this.colReorder.reset();
                    //     }
                    // }
                ],
                order: [[0, 'asc']], // Default sorting
                paging: false, // Remove pagination

                footerCallback: function(row, data, start, end, display) {
                    var api = this.api(), data;
        
                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            parseFloat(i.replace(/[\$,()-]/g, '')) :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(6).footer()).html(
                        total.toLocaleString('en-US', { minimumFractionDigits: 2 })
                    );
                }
               
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
                    var url = "{{ route('checkReceipts.index') }}?from_date=" + fromDate + "&to_date=" + toDate + "&guid=" + "{{ $societyGuid }}";
                    window.location.href = url; // Redirect to filtered URL
                });


    
            // Show DataTable buttons initially
            $('.dt-buttons').show();
    
            // Toggle DataTable buttons visibility when collapse section is shown/hidden
            $('#collapseProduct').on('shown.bs.collapse', function() {
                $('.dt-buttons').show();
            }).on('hidden.bs.collapse', function() {
                $('.dt-buttons').hide();
            });
        });
    </script>
    
    
    
@endpush
