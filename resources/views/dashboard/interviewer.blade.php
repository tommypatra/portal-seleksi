@extends('template_dashboard')

@section('head')
<title>Interviewer Peserta Seleksi</title>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('container')
<h1>Interviewer Peserta Seleksi</h1>
<p>digunakan untuk mengelola interviewer Peserta seleksi</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Seleksi</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
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
                <th scope="col">Nama Seleksi/ Tahun</th>
                <th scope="col">Verifikasi</th>
                <th scope="col">Jumlah Data</th>
                <th scope="col">Memenuhi</th>
                <th scope="col">Tidak Memenuhi</th>
                <th scope="col">%</th>
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

{{-- modal proses verifikasi --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 id="judul_seleksi">Seleksi</h4>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-left pagination-verifikasi">
                        </ul>
                    </nav>

                    <div id="identitas_peserta"></div>

                    <div id="data-list-verifikasi"></div>
                    
                    <hr>
                    <div id="kesimpulan-verifikasi" style="display: none;">
                        <h5><i class="bi bi-clipboard-check"></i> Kesimpulan Verifikasi</h5>
                        <form id="formVerifikasi" class="mb-3">
                            <input type="hidden" name="id" id="pendaftar_id">
                            <div class="mb-2">
                                <select class="form-select" name="verifikasi_lulus" id="verifikasi_lulus" required>
                                    <option value="">--- Pilih ---</option>
                                    <option value="1">Memenuhi Syarat</option>
                                    <option value="0">Tidak Memenuhi Syarat</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control" name="verifikasi_keterangan" id="verifikasi_keterangan" rows="3"></textarea>
                            </div>       

                            <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Simpan</button>
                        </form>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-right pagination-verifikasi">
                        </ul>
                    </nav>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
        </div>
    </div>
</div>

{{-- modal hasil verifikasi --}}
<div class="modal fade" id="modaVerifikasi" tabindex="-1" aria-labelledby="modalFormVerifikasi" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="formVerifikasi">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormVerifikasi">Hasil Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-2">
                        <select class="form-select" name="verifikasi_valid" id="verifikasi_valid" required>
                            <option value="">--- Pilih ---</option>
                            <option value="1">Memenuhi Syarat</option>
                            <option value="0">Tidak Memenuhi Syarat</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <textarea class="form-control" name="verifikasi_keterangan" id="verifikasi_keterangan" rows="3"></textarea>
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
<script src="{{ asset('js/web/interviewer.js') }}"></script>


@endsection