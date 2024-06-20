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
                    <li class="breadcrumb-item active">Receipts list</li>
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
                            <p>Day Book</p>
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
                        <table id="dayBook-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Voucher Date</th>
                                    <th>Acc</th>
                                    <th>Type</th>
                                    <th>Voucher Number</th>
                                    <th>Debit Total</th>
                                    <th>Credit Total</th>
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
            // Initialize DataTable
            var table = $('#dayBook-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dayBook.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val() || "{{ date('Y-m-d') }}"; // Default to current date
                        d.to_date = $('#to_date').val() || "{{ date('Y-m-d') }}";
                    },
                    error: function(xhr, error, thrown) {
                        if (xhr.status == 404) {
                            $('#dayBook-datatable').DataTable().clear().draw();
                            $('#dayBook-datatable').DataTable().destroy();
                            $('#dayBook-datatable tbody').html('<tr><td colspan="3" class="text-center">Data not found</td></tr>');
                        }
                    }
                },
                columns: [
                    { data: 'voucher_date', name: 'voucher_date',
                        render: function(data, type, row) {
                            if (data && data !== 'Invalid date') {
                                return moment(data, 'DD-MM-YYYY').format('DD-MM-YYYY');
                            }
                            return 'Invalid date'; 
                        }
                    },
                    { data: 'ledger', name: 'ledger'},
                    { data: 'type', name: 'type' },
                    { data: 'voucher_number', name: 'voucher_number', className: 'dt-body-center' },
                    { data: 'debit_total', name: 'debit_total', className: 'dt-body-right' },
                    { data: 'credit_total', name: 'credit_total', className: 'dt-body-right' },
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
                    }
                ],
                order: [[0, 'asc']], // Default sorting by voucher_date
                paging: false // Remove pagination
            });

            // Reload DataTable on date change
            $('#from_date, #to_date').on('change', function () {
                table.ajax.reload();
            });

            // Clear filters button functionality
            $('#clear-filters').click(function () {
                $('#from_date, #to_date').val('');
                table.ajax.reload();
            });

            // Search button functionality (redirects to URL)
            $('#search').click(function () {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var url = "{{ route('receipts.index') }}?from_date=" + fromDate + "&to_date=" + toDate;
                window.location.href = url;
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
