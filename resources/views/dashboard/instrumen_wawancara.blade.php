@extends('template_dashboard')

@section('head')
<title>Instrumen Wawancara</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .form-control {
        min-width: 175px;
    }
    .form-select {
        min-width: 150px;
    }
</style>
@endsection

@section('container')
<h1 id="detail_judul">Instrumen Wawancara</h1>
<p>digunakan untuk mengatur instrumen wawancara pada website </p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('seleksi') }}">Seleksi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Instrumen Wawancara</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="tambah" ><i class="bi bi-plus-lg"></i> Tambah</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh" ><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                <i class="bi bi-collection"></i> Paging
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging"></ul>

        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" width="30px;">#</th>
                <th scope="col">Kategori</th>
                <th scope="col">Instrumen</th>
                <th scope="col">Bobot</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list">
            <!-- Data akan dimuat di sini -->
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center" id="pagination">
        </ul>
    </nav>

</div>

<div class="modal fade" id="modalForm" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form">
                <input type="hidden" name="id" id="id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Formulir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-12 row">
                        <label for="bank_soal_id" class="col-sm-2 col-form-label">Soal</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="bank_soal_id[]" multiple="multiple" id="bank_soal_id" required></select>
                        </div>
                    </div>
                    <div class="mb-12 row mt-2">
                        <label for="bobot" class="col-sm-2 col-form-label">Bobot</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="bobot" multiple="multiple" id="bobot" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')

<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

<script>
    var seleksi_id = "{{$seleksi_id}}";
</script>

<script src="{{ asset('js/web/instrumen-wawancara.js') }}"></script>

@endsection