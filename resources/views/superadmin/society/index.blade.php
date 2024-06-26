@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 p-0">
                <h3>Society list</h3>
            </div>
            <div class="col-sm-6 p-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item active">Society list</li>
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
                    <div class="list-product-header">
                        <div>
                            <div class="light-box">
                                <a data-bs-toggle="collapse" href="#collapseProduct" role="button" aria-expanded="true" aria-controls="collapseProduct">
                                    <i class="filter-icon show" data-feather="filter"></i>
                                    <i class="icon-close filter-close hide"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card card-body list-product-body" id="collapseProduct">
                            <!-- Filter content here -->
                        </div>
                    </div>
                    <div class="">
                        <table id="society-datatable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SR. NO.</th>
                                    <th>Society Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Website</th>
                                    <th>Company Number</th>
                                    <th>Action</th>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
            <div class="modal-toggle-wrapper"> 
                <h4 class="text-sm-center">Do you really want to delete the file ?</h4>
                <div class="modal-img"> <img src="{{ asset('assets/images/delete.jpg') }}" alt="delete"></div>
                <p class="text-sm-center pt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel this time</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Yes delete the record</button>
                </p>
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
            var table = $('#society-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('society.get-data') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row, meta) {
                            var url = "{{ route('webpanel.index') }}?guid=" + row.guid;
                            return '<a href="' + url + '" style="color: #337ab7;">' + data + '</a>';
                        }
                    },
                    {data: 'address1', name: 'address1'},
                    {data: 'mobile_number', name: 'mobile_number'},
                    {data: 'website', name: 'website'},
                    {data: 'company_number', name: 'company_number'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return '<button class="border-0 delete-society" data-id="' + row.id + '" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="icon-trash" style="color:#F81F58;"></i></button>';
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
            });

            // Show DataTable buttons initially
            $('.dt-buttons').show();

            // Toggle DataTable buttons visibility when collapse section is shown/hidden
            $('#collapseProduct').on('shown.bs.collapse', function () {
                $('.dt-buttons').hide();
            }).on('hidden.bs.collapse', function () {
                $('.dt-buttons').show();
            });

            // Handle delete action
            var societyIdToDelete;
            $('#society-datatable').on('click', '.delete-society', function() {
                societyIdToDelete = $(this).data('id');
            });

            $('#confirmDelete').on('click', function() {
                var deleteUrl = "{{ route('society.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', societyIdToDelete);

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                            $('#society-datatable').DataTable().ajax.reload();
                            if(response.success) {
                                show_toastr('Success', response.success, 'success');
                            } else if(response.error) {
                                show_toastr('Error', response.error, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            show_toastr('Error', 'Failed to delete society.', 'error');
                        }
                });
            });
        });
    </script>
@endpush
