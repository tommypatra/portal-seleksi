@extends('template_dashboard')

@section('head')
<title>Pengaturan Seleksi</title>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('container')
<h1>Pengaturan Seleksi</h1>
<p>digunakan untuk mengatur seleksi pada website </p>

<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="tambah" onclick="tambah()"><i class="bi bi-plus-lg"></i> Tambah</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh" onclick="refresh()"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                <i class="bi bi-collection"></i> Paging
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging"></ul>

        </div>
    </div>
</div>

<div class="table-responsive">

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-0-tab" data-bs-toggle="tab" data-bs-target="#nav-0" type="button" role="tab" aria-controls="nav-0" aria-selected="true">Syarat</button>
            <button class="nav-link" id="nav-1-tab" data-bs-toggle="tab" data-bs-target="#nav-1" type="button" role="tab" aria-controls="nav-1" aria-selected="false">Institusi</button>
            <button class="nav-link" id="nav-2-tab" data-bs-toggle="tab" data-bs-target="#nav-2" type="button" role="tab" aria-controls="nav-2" aria-selected="false">Verifikator</button>
            <button class="nav-link" id="nav-3-tab" data-bs-toggle="tab" data-bs-target="#nav-3" type="button" role="tab" aria-controls="nav-3" aria-selected="false">Interviewer</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-0" role="tabpanel" aria-labelledby="nav-0-tab" tabindex="0">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Syarat</th>
                        <th scope="col">Wajib</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-syarat">
                    <!-- Data akan dimuat di sini -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination-syarat">
                </ul>
            </nav>

        </div>
        <div class="tab-pane fade" id="nav-1" role="tabpanel" aria-labelledby="nav-1-tab" tabindex="0">
            <form id="form-institusi">

            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Institusi/ Sub Institusi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-institusi">
                    <!-- Data akan dimuat di sini -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination-institusi">
                </ul>
            </nav>

        </div>
        <div class="tab-pane fade" id="nav-2" role="tabpanel" aria-labelledby="nav-2-tab" tabindex="0">
            Verifikator
        </div>
        <div class="tab-pane fade" id="nav-3" role="tabpanel" aria-labelledby="nav-3-tab" tabindex="0">
            Interviewer
        </div>
    </div>

</div>

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

<script>
    var seleksi_id = "{{$seleksi_id}}";
    tokenCek();

    function tambah() {
        var activeTabId = $('.tab-pane.active').attr('id');
        switch (activeTabId) {
            case 'nav-0':
                tambahSyarat();
                break;
            case 'nav-1':
                // Lakukan sesuatu jika tab Bidang Pendaftar yang aktif
                console.log('Tambah sesuai dengan tab Bidang Pendaftar');
                break;
            case 'nav-2':
                // Lakukan sesuatu jika tab Verifikator yang aktif
                console.log('Tambah sesuai dengan tab Verifikator');
                break;
            case 'nav-3':
                // Lakukan sesuatu jika tab Interviewer yang aktif
                console.log('Tambah sesuai dengan tab Interviewer');
                break;
            default:
                break;
        }
    }

    $('.nav-link').click(function() {
        var activeTabId = $('.tab-pane.active').attr('id');
        switch (activeTabId) {
            case 'nav-0':
                loadDataSyarat();
                break;
            case 'nav-1':
                loadDataInstitusi();
                break;
            case 'nav-2':
                // Lakukan sesuatu jika tab Verifikator yang aktif
                console.log('Tambah sesuai dengan tab Verifikator');
                break;
            case 'nav-3':
                // Lakukan sesuatu jika tab Interviewer yang aktif
                console.log('Tambah sesuai dengan tab Interviewer');
                break;
            default:
                break;
        }
    });

    function refresh() {
        var activeTabId = $('.tab-pane.active').attr('id');
        switch (activeTabId) {
            case 'nav-0':
                loadDataSyarat();
                break;
            case 'nav-1':
                // Lakukan sesuatu jika tab Bidang Pendaftar yang aktif
                console.log('Tambah sesuai dengan tab Bidang Pendaftar');
                break;
            case 'nav-2':
                // Lakukan sesuatu jika tab Verifikator yang aktif
                console.log('Tambah sesuai dengan tab Verifikator');
                break;
            case 'nav-3':
                // Lakukan sesuatu jika tab Interviewer yang aktif
                console.log('Tambah sesuai dengan tab Interviewer');
                break;
            default:
                break;
        }
    }
</script>
<script src="{{ asset('js/web/pengaturan-syarat.js') }}"></script>
<!-- <script src="{{ asset('js/web/pengaturan-institusi.js') }}"></script> -->

@endsection