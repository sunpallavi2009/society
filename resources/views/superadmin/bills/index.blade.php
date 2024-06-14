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
                    <li class="breadcrumb-item active">Bill Ledgers list</li>
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
                            <p> Bills </p>
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
                                        <label for="bill_date">Bill Date:</label>
                                        <input class="form-control" type="date" id="bill_date" onchange="reloadDataTable()" value="{{ date('Y-m-01') }}" onfocus="focused(this)" onfocusout="defocused(this)" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <table id="bill-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Alias</th>
                                    <th>Vch No</th>
                                    <th>Bill Date</th>
                                    <th>Opening Bal</th>
                                    <th>Bill Amt</th>
                                    <th>Outstanding</th>
                                    <th>Received</th>
                                    <th>Due Amt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by DataTables -->
                            </tbody>
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
            var table = $('#bill-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bills.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.guid = "{{ $societyGuid }}";
                        d.group = "{{ $group }}";
                        d.bill_date = $('#bill_date').val() || "{{ date('Y-m-d') }}"; 
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
                    { data: 'voucher_number', name: 'voucher_number' , className: 'dt-body-center'},
                    { data: 'voucher_date', name: 'voucher_date', render: function(data, type, row) {
                        return data ? moment(data).format('DD-MM-YY') : '';
                    } },
                    { data: 'opening_balance', name: 'opening_balance' , className: 'dt-body-right'},
                    { data: 'amount_billed', name: 'amount_billed' , className: 'dt-body-right'},
                    
                    { data: 'outstanding', name: 'outstanding' , className: 'dt-body-right'},

                    { data: 'amount_received', name: 'amount_received' , className: 'dt-body-right'},
                    {
                        data: 'this_year_balance',
                        name: 'this_year_balance',
                        className: 'dt-body-right',
                        render: function(data, type, row, meta) {
                            return (-parseFloat(data)).toFixed(2);
                        }
                    },
                ],
                dom: 'Blfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ],
                order: [[0, 'asc']],
                paging: false,
                ordering: true,
                columnDefs: [
                    {
                        targets: [0, 1, 2, 3, 4, 5, 6, 7],
                        orderable: true
                    }
                ]
            });
    
            // Function to reload DataTable on bill_date change
            function reloadDataTable() {
                table.ajax.reload();
            }
    
            // Clear filters button functionality
            $('#clear-filters').click(function () {
                $('#bill_date').val('');
                table.ajax.reload();
            });
    
            // Search button functionality (redirects to URL)
            $('#search').click(function () {
                var billDate = $('#bill_date').val();
                var url = "{{ route('bills.index') }}?date=" + billDate + "&guid=" + "{{ $societyGuid }}";
                window.location.href = url;
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
