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
                    <li class="breadcrumb-item active">Member Ledgers list</li>
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
                            @foreach ($society as $company)
                                <h3><b>{{ $company->name }}</b></h3>
                                <h6>{{ $company->address1 }}</h6>
                            @endforeach
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
                                
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="ledger-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Alias</th>
                                    <th>Parent</th>
                                    <th>Primary Group</th>
                                    <th>Balance</th>
                                    <th>Total Voucher</th>
                                    <th>First Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">Total</th>
                                    <th></th>
                                    <th></th>
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
            var table = $('#ledger-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('members.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.guid = "{{ $societyGuid }}";
                        d.group = "{{ $group }}"; // Pass the group parameter
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
                                {data: 'alias1', name: 'alias1'},
                                {data: 'parent', name: 'parent'},
                                {data: 'primary_group', name: 'primary_group'},
                                {
                                    data: 'this_year_balance',
                                    name: 'this_year_balance',
                                    render: function(data, type, row, meta) {
                                        // Check if the data is a valid number
                                        if (data === null || data === "" || isNaN(data)) {
                                            return '0.00';
                                        }

                                        // Parse and format the balance
                                        var balance = parseFloat(data);
                                        balance = Math.abs(balance); // Ensure the balance is positive
                                        balance = balance.toFixed(2); // Format to 2 decimal places
                                        balance = balance.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas for thousands
                                        
                                        return balance; // Return formatted balance without currency symbol
                                    }
                                },

                                {data: 'vouchers_count', name: 'vouchers_count'},
                                {
                                    data: 'first_voucher_date',
                                    name: 'first_voucher_date',
                                    render: function(data, type, row, meta) {
                                        return new Date(data).toISOString();
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
                            columns: [0, 1, 2, 3, 4, 5], // Specify the searchable columns
                        }
                    }
                ],
                order: [[0, 'asc']],
                paging: false, // Remove pagination
                footerCallback: function (row, data, start, end, display) {
                        var api = this.api();

                        // Calculate the total balance for the current page
                        var balanceTotal = api.column(4, { page: 'current' }).data().reduce(function (acc, val) {
                            // Ensure the value is a valid number, otherwise treat it as 0
                            var value = parseFloat(val.replace(/,/g, ''));
                            return acc + (isNaN(value) ? 0 : value);
                        }, 0);
                        balanceTotal = Math.abs(balanceTotal); // Ensure the total balance is positive

                        // Calculate the total vouchers count for the current page
                        var vouchersTotal = api.column(5, { page: 'current' }).data().reduce(function (acc, val) {
                            // Ensure the value is a valid number, otherwise treat it as 0
                            var value = parseFloat(val);
                            return acc + (isNaN(value) ? 0 : value);
                        }, 0);
                        vouchersTotal = Math.abs(vouchersTotal); // Ensure the total vouchers count is positive

                        // Format the totals and update the footer
                        $(api.column(4).footer()).html(balanceTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $(api.column(5).footer()).html(vouchersTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }

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
