@extends('template_dashboard')

@section('head')
<title>Interviewer Peserta Seleksi</title>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
@endsection

@section('container')
<h1>Daftar Peserta Interview</h1>
<p>digunakan untuk melihat daftar Peserta interview</p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('interviewer') }}">Seleksi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Peserta Interview</li>
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
                <th scope="col">Nama/ Email</th>
                <th scope="col">No. Id</th>
                <th scope="col">Asal</th>
                <th scope="col">Status Wawancara</th>
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

{{-- modal proses Interviewer --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Interviewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="identitas_peserta"></div>
                    <hr>
                    <form id="form" class="mb-3">
                        <input type="hidden" name="id" id="pendaftar_id">
                        <div id="soal-container">
                            <h5 id="kategori"></h5>
                            <div id="soal-info"></div> 
                            <p id="soal"></p>
                            <p id="bobot"></p>
                        </div>
                        
                        <div class="btn btn-primary" id="prev">Soal Sebelumnya</div>
                        <div class="btn btn-primary" id="next">Soal Berikutnya</div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Simpan</button>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                </div>
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
<script src="{{ asset('js/web/daftar_peserta_interview.js') }}"></script>
<script>
    var seleksi_id = "{{$seleksi_id}}";
    var pendaftar = null;
    var topik_interviews = [];
    var currentIndex = 0;
</script>

@endsection