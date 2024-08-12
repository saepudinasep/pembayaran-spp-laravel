@extends('layouts.app')

@section('title', 'Data Admin')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Admin</h1>
        <div class="d-flex">
            <a href="{{ route('admin.export') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Excel
            </a>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#adminModal"
                data-action="{{ route('admin.store') }}" data-method="POST" data-title="Add Data Admin">
                <i class="fas fa-plus"></i> Add Data
            </a>
        </div>
    </div>

    <!-- DataTales Admin -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Admin</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 20%;">Username</th>
                            <th style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admin as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($admin->currentPage() - 1) * $admin->perPage() }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->user->email }}</td>
                                <td>{{ $item->user->username }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#adminModal"
                                        data-action="{{ route('admin.update', $item->id) }}" data-method="PUT"
                                        data-title="Update Data Admin" data-nama="{{ $item->nama }}"
                                        data-email="{{ $item->user->email }}" data-username="{{ $item->user->username }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="deleteForm-{{ $item->id }}"
                                        action="{{ route('admin.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete('{{ $item->id }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    <li class="page-item {{ $admin->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $admin->previousPageUrl() }}">Previous</a>
                    </li>
                    <!-- Pagination Links -->
                    @for ($i = 1; $i <= $admin->lastPage(); $i++)
                        <li class="page-item {{ $admin->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $admin->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <!-- Next Page Link -->
                    <li class="page-item {{ $admin->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $admin->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Data Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="adminForm" method="POST">
                        @csrf
                        <input type="hidden" id="methodField" name="_method" value="POST">

                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama...." required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukkan Email...." required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Masukkan Username...." required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveButton" type="submit" form="adminForm">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
    @if (session('status'))
        <script>
            toastr.success('{{ session('status') }}');
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif

    @if ($errors->any())
        <script>
            toastr.error('{{ $errors->first() }}');
        </script>
    @endif

    <script>
        // Modal
        $(document).ready(function() {
            $('#adminModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button yang memicu modal
                var action = button.data('action'); // Route dari action
                var method = button.data('method'); // Method untuk form (POST/PUT)
                var title = button.data('title'); // Title untuk modal
                var nama = button.data('nama'); // Nama jika update
                var email = button.data('email'); // Nama jika update
                var username = button.data('username'); // Username jika update

                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('#adminForm').attr('action', action);

                if (method === 'PUT') {
                    modal.find('#methodField').val('PUT');
                } else {
                    modal.find('#methodField').val('POST');
                }

                // Jika Update, isi field dengan data
                if (nama) {
                    modal.find('#nama').val(nama);
                    modal.find('#email').val(email);
                    modal.find('#username').val(username);
                } else {
                    modal.find('#adminForm')[0].reset(); // Reset form jika Add
                }
            });
        });

        // End


        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form delete
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        }
    </script>
@endsection
