@extends('template_dashboard')

@section('head')
<title>Jadwal Seleksi</title>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('container')
<h1>Jadwal Seleksi</h1>
<p>digunakan untuk mengelola jadwal seleksi</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Seleksi</li>
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
                <th scope="col">Nama Seleksi/ Pengelola (Tahun)</th>
                <th scope="col">Pendaftaran</th>
                <th scope="col">Verifikasi</th>
                <th scope="col">Jenis/ Keterangan</th>
                <th scope="col">Status Publikasi</th>
                <th scope="col">Jumlah Pendaftar</th>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Jadwal Seleksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 row">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="tahun" id="tahun" placeholder="tahun" value="{{ date('Y')}}" required>
                        </div>
                        <label for="jenis_id" class="col-sm-2 col-form-label">Jenis</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="jenis_id" id="jenis_id" required></select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Seleksi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="daftar_mulai" class="col-sm-2 col-form-label">Jadwal Pendaftaran</label>

                        <div class="col-sm-10">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control datepicker" id="daftar_mulai" name="daftar_mulai" placeholder="{{ date('Y-m-d')}}" value="{{ date('Y-m-d')}}" required>
                                        <label for="daftar_mulai">Mulai</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control datepicker" id="daftar_selesai" name="daftar_selesai" placeholder="{{ date('Y-m-d')}}" value="{{ date('Y-m-d')}}" required>
                                        <label for="daftar_selesai">Selesai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="daftar_mulai" class="col-sm-2 col-form-label">Jadwal Verifikasi</label>

                        <div class="col-sm-10">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control datepicker" id="verifikasi_mulai" name="verifikasi_mulai" placeholder="{{ date('Y-m-d')}}" value="{{ date('Y-m-d')}}" required>
                                        <label for="verifikasi_mulai">Mulai</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control datepicker" id="verifikasi_selesai" name="verifikasi_selesai" placeholder="{{ date('Y-m-d')}}" value="{{ date('Y-m-d')}}" required>
                                        <label for="verifikasi_selesai">Selesai</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="keterangan" id="keterangan" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="keterangan" class="col-sm-2 col-form-label">Publikasi</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="is_publish" id="is_publish" required>
                                <option value="0">Tidak Terpublikasi</option>
                                <option value="1">Terpublikasi</option>
                            </select>
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="{{ asset('plugins/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/web/seleksi.js') }}"></script>


@endsection