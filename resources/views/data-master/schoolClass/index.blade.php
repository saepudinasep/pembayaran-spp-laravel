@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Kelas</h1>
        <div class="d-flex">
            <a href="{{ route('kelas.export') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Excel
            </a>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#kelasModal"
                data-action="{{ route('kelas.store') }}" data-method="POST" data-title="Add Data Kelas">
                <i class="fas fa-plus"></i> Add Data
            </a>
        </div>
    </div>

    <!-- DataTales Kelas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Kelas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 30%;">Nama Kelas</th>
                            <th style="width: 40%;">Jurusan</th>
                            <th style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolClass as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($schoolClass->currentPage() - 1) * $schoolClass->perPage() }}
                                </td>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>
                                    @if ($item->jurusan == 'AK')
                                        Akuntansi
                                    @elseif ($item->jurusan == 'AP')
                                        Administrasi Perkantoran
                                    @elseif ($item->jurusan == 'RPL')
                                        Rekayasa Perangkat Lunak
                                    @elseif ($item->jurusan == 'TITL')
                                        Teknik Industri Teknik Listrik
                                    @elseif ($item->jurusan == 'TKR')
                                        Teknik Kendaraan Ringan
                                    @elseif ($item->jurusan == 'TSM')
                                        Teknik Sepeda Motor
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#kelasModal"
                                        data-action="{{ route('kelas.update', $item->id) }}" data-method="PUT"
                                        data-title="Update Data Kelas" data-nama="{{ $item->nama_kelas }}"
                                        data-jurusan="{{ $item->jurusan }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="deleteForm-{{ $item->id }}"
                                        action="{{ route('kelas.destroy', $item->id) }}" method="POST"
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
                    <li class="page-item {{ $schoolClass->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $schoolClass->previousPageUrl() }}">Previous</a>
                    </li>
                    <!-- Pagination Links -->
                    @for ($i = 1; $i <= $schoolClass->lastPage(); $i++)
                        <li class="page-item {{ $schoolClass->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $schoolClass->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <!-- Next Page Link -->
                    <li class="page-item {{ $schoolClass->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $schoolClass->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="kelasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Data Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="kelasForm" method="POST">
                        @csrf
                        <input type="hidden" id="methodField" name="_method" value="POST">

                        <div class="form-group">
                            <label for="nama">Nama Kelas</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan Nama Kelas...." required>
                        </div>
                        <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <select class="form-control" id="jurusan" name="jurusan" required>
                                <option value="0">Pilih Jurusan</option>
                                <option value="AK">Akuntansi</option>
                                <option value="AP">Administrasi Perkantoran</option>
                                <option value="RPL">Rekayasa Perangkat Lunak</option>
                                <option value="TITL">Teknik Industri Teknik Listrik</option>
                                <option value="TKR">Teknik Kendaraan Ringan</option>
                                <option value="TSM">Teknik Sepeda Motor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveButton" type="submit" form="kelasForm">Save
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
            $('#kelasModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button yang memicu modal
                var action = button.data('action'); // Route dari action
                var method = button.data('method'); // Method untuk form (POST/PUT)
                var title = button.data('title'); // Title untuk modal
                var nama = button.data('nama'); // Nama jika update
                var jurusan = button.data('jurusan'); // Jurusan jika update

                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('#kelasForm').attr('action', action);

                if (method === 'PUT') {
                    modal.find('#methodField').val('PUT');
                } else {
                    modal.find('#methodField').val('POST');
                }

                // Jika Update, isi field dengan data
                if (nama) {
                    modal.find('#nama').val(nama);
                    modal.find('#jurusan').val(jurusan);
                } else {
                    modal.find('#kelasForm')[0].reset(); // Reset form jika Add
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
