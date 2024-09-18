@extends('template_dashboard')

@section('head')
<title>Akun</title>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('container')
<h1>Akun</h1>
<p>digunakan untuk mengelola akun aplikasi</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Akun</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="tambah" onclick="tambah()"><i class="bi bi-plus-lg"></i> Tambah</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                <i class="bi bi-collection"></i> Paging
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging"></ul>

        </div>
    </div>
</div>

<div class="table-responsive" style="min-height: 300px;">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama/ Email</th>
                <th scope="col">Akses Grup</th>
                <th scope="col">Aksi</th>
        </tr>
        </thead>
        <tbody id="data-list">
            <!-- Data akan dimuat di sini -->
        </tbody>
    </table>
</div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination">
    </ul>
</nav>

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" id="password" name="password">
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

<div class="modal fade" id="modalAkses" tabindex="-1" aria-labelledby="modalAksesLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formAkses">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAksesLabel">Hakakses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cekakses[]" value="1" id="cekadmin">
                        <label class="form-check-label" for="cekadmin">
                            Admin
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cekakses[]" value="2" id="cekinterviewer">
                        <label class="form-check-label" for="cekinterviewer">
                            Interviewer
                        </label>
                    </div>                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="cekakses[]" value="3" id="cekverifikator">
                        <label class="form-check-label" for="cekverifikator">
                            Verifikator
                        </label>
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="{{ asset('plugins/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/web/akun.js') }}"></script>


@endsection