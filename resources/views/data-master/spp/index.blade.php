@extends('layouts.app')

@section('title', 'Data SPP')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data SPP</h1>
        <div class="d-flex">
            <a href="{{ route('spp.export') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Excel
            </a>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#sppModal"
                data-action="{{ route('spp.store') }}" data-method="POST" data-title="Add Data SPP">
                <i class="fas fa-plus"></i> Add Data
            </a>
        </div>
    </div>

    <!-- DataTales SPP -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables SPP</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 20%;">Tahun</th>
                            <th style="width: 40%;">Nominal</th>
                            <th style="width: 30%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spp as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($spp->currentPage() - 1) * $spp->perPage() }}</td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>
                                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#sppModal"
                                        data-action="{{ route('spp.update', $item->id) }}" data-method="PUT"
                                        data-title="Update Data SPP" data-tahun="{{ $item->tahun }}"
                                        data-nominal="{{ $item->nominal }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="deleteForm-{{ $item->id }}" action="{{ route('spp.destroy', $item->id) }}"
                                        method="POST" style="display:inline;">
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
                    <li class="page-item {{ $spp->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $spp->previousPageUrl() }}">Previous</a>
                    </li>
                    <!-- Pagination Links -->
                    @for ($i = 1; $i <= $spp->lastPage(); $i++)
                        <li class="page-item {{ $spp->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $spp->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <!-- Next Page Link -->
                    <li class="page-item {{ $spp->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $spp->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="sppModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Data SPP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="sppForm" method="POST">
                        @csrf
                        <input type="hidden" id="methodField" name="_method" value="POST">

                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <select class="form-control" id="tahun" name="tahun" required>
                                @for ($i = now()->year; $i >= now()->year - 10; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="text" class="form-control" id="nominal" name="nominal"
                                placeholder="Masukkan Nominal" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="saveButton" type="submit" form="sppForm">Save changes</button>
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
            $('#sppModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button yang memicu modal
                var action = button.data('action'); // Route dari action
                var method = button.data('method'); // Method untuk form (POST/PUT)
                var title = button.data('title'); // Title untuk modal
                var tahun = button.data('tahun'); // Tahun jika update
                var nominal = button.data('nominal'); // Nominal jika update

                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('#sppForm').attr('action', action);

                if (method === 'PUT') {
                    modal.find('#methodField').val('PUT');
                } else {
                    modal.find('#methodField').val('POST');
                }

                // Jika Update, isi field dengan data
                if (tahun) {
                    modal.find('#tahun').val(tahun);
                    modal.find('#nominal').val(nominal);
                } else {
                    modal.find('#sppForm')[0].reset(); // Reset form jika Add
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
                    document.getElementById('deleteForm-' + id).submit();
                    // Optionally, you can add a delay before showing the toastr notification
                    setTimeout(function() {
                        toastr.success('Data SPP berhasil dihapus!', 'Success', {
                            closeButton: true,
                            progressBar: true,
                            timeOut: 5000
                        });
                    }, 1000); // Delay to allow form submission to complete
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            $('#nominal').on('keyup', function() {
                // Ambil nilai dari input dan hilangkan karakter selain angka
                var input = $(this).val().replace(/[^,\d]/g, '').toString();

                // Pecah menjadi bagian ribuan
                var split = input.split(',');
                var sisa = split[0].length % 3;
                var nominal = split[0].substr(0, sisa);
                var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // Tambahkan titik jika ada bagian ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    nominal += separator + ribuan.join('.');
                }

                // Gabungkan hasil
                $(this).val(nominal);
            });
        });
    </script>
@endsection
