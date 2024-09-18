@extends('template_dashboard')

@section('head')
<title>Pengaturan Seleksi</title>
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
<h1 id="detail_judul">Pengaturan Seleksi</h1>
<p>digunakan untuk mengatur seleksi pada website </p>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('seleksi') }}">Seleksi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-8">
        <div class="alert alert-info" role="alert">
            Pendaftar <span id="jumlah-pendaftar">0</span>
            Verifikator <span id="jumlah-pendaftar">0</span>
            Interviewer <span id="jumlah-pendaftar">0</span>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="input-group justify-content-end">
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
        <div class="nav nav-tabs tab-menu" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-0-tab" data-bs-toggle="tab" data-bs-target="#nav-0" type="button" role="tab" aria-controls="nav-0" aria-selected="true">Syarat</button>
            <button class="nav-link" id="nav-1-tab" data-bs-toggle="tab" data-bs-target="#nav-1" type="button" role="tab" aria-controls="nav-1" aria-selected="false">Filter Asal</button>
            <button class="nav-link" id="nav-2-tab" data-bs-toggle="tab" data-bs-target="#nav-2" type="button" role="tab" aria-controls="nav-2" aria-selected="false">Verifikator 
                <span class="badge text-bg-primary rounded-circle" id="jumlah-verifikator">0</span>
            </button>
            <button class="nav-link" id="nav-3-tab" data-bs-toggle="tab" data-bs-target="#nav-3" type="button" role="tab" aria-controls="nav-3" aria-selected="false">Interviewer
                <span class="badge text-bg-primary rounded-circle" id="jumlah-interviewer">0</span>
            </button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-0" role="tabpanel" aria-labelledby="nav-0-tab" tabindex="0">
            <div class="input-group mt-3">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="tambah" onclick="tambahSyarat()"><i class="bi bi-plus-lg"></i> Tambah</button>
            </div>
    
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="30px;">#</th>
                        <th scope="col">Syarat</th>
                        <th scope="col">Jenis Dokumen</th>
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
            <form id="formInstitusi" >
                <div class="row" style="max-width: 99%">
                    <div class="col-md-2 mt-2">
                        <label for="sub_institusi_id" class="col-form-label">Institusi</label>
                    </div>
                    <div class="col-md-8 mt-2">
                        <select class="form-control" id="sub_institusi_id" name="sub_institusi_id[]" multiple="multiple" required></select>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-floppy"></i> Simpan</button>
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="30px;">#</th>
                        <th scope="col" width="80%;">Institusi</th>
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
            <form id="formVerifikator" >
                <div class="row" style="max-width: 99%">
                    <div class="col-md-2 mt-2">
                        <label for="verifikator_id" class="col-form-label">Verifikator</label>
                    </div>
                    <div class="col-md-8 mt-2">
                        <select class="form-control" id="verifikator_id" name="verifikator_id[]" multiple="multiple" required></select>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-floppy"></i> Simpan</button>
                    </div>
                </div>
            </form>

            <button type="button" class="btn btn-warning btn-sm tukar-peserta-verifikator">
                <i class="bi bi-arrow-left-right"></i> Tukar Peserta
            </button>

            <button type="button" class="btn btn-secondary btn-sm position-relative bagi-peserta-verifikator">
                <i class="bi bi-person-lines-fill"></i> Bagi Peserta
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <span id="belum-ada-verifikator">0</span>
                    <span class="visually-hidden">unread messages</span>
                </span>
            </button>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="30px;">#</th>
                        <th scope="col" width="20px;"><input type="checkbox" class="form-check-input cek-semua"></th>
                        <th scope="col" width="50%;">Verifikator</th>
                        <th scope="col" width="30%;">Peserta</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-verifikator">
                    <!-- Data akan dimuat di sini -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination-verifikator">
                </ul>
            </nav>        
        </div>
        <div class="tab-pane fade" id="nav-3" role="tabpanel" aria-labelledby="nav-3-tab" tabindex="0">
            <form id="formInterviewer" >
                <div class="row" style="max-width: 99%">
                    <div class="col-md-2 mt-2">
                        <label for="interviewer_id" class="col-form-label">Interviewer</label>
                    </div>
                    <div class="col-md-8 mt-2">
                        <select class="form-control" id="interviewer_id" name="interviewer_id[]" multiple="multiple" required></select>
                    </div>
                    <div class="col-md-2 mt-2">
                        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-floppy"></i> Simpan</button>
                    </div>
                </div>
            </form>
            <button type="button" class="btn btn-warning btn-sm tukar-peserta-interviewer">
                <i class="bi bi-arrow-left-right"></i> Tukar Peserta
            </button>

            <button type="button" class="btn btn-secondary btn-sm position-relative bagi-peserta-interviewer">
                <i class="bi bi-person-lines-fill"></i> Bagi Peserta
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <span id="belum-ada-interviewer">0</span>
                    <span class="visually-hidden">unread messages</span>
                </span>
            </button>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" width="30px;">#</th>
                        <th scope="col" width="20px;"><input type="checkbox" class="form-check-input cek-semua"></th>
                        <th scope="col" width="70%;">Interviewer</th>
                        <th scope="col">Peserta</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-interviewer">
                    <!-- Data akan dimuat di sini -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center" id="pagination-interviewer">
                </ul>
            </nav>
        </div>
    </div>

</div>

<div class="modal fade" id="modalTukar" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTukar" class="formTukar">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Tukar Peserta Verifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="peserta_asal_id" class="col-sm-2 col-form-label">Asal</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peserta_asal_id" id="peserta_asal_id" required></select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="peserta_tujuan_id" class="col-sm-2 col-form-label">Tujuan</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peserta_tujuan_id" id="peserta_tujuan_id" required></select>
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

<div class="modal fade" id="modalTukar2" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTukar2">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">Tukar Peserta Interviewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="peserta_asal_id2" class="col-sm-2 col-form-label">Asal</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peserta_asal_id" id="peserta_asal_id2" required></select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="peserta_tujuan_id2" class="col-sm-2 col-form-label">Tujuan</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="peserta_tujuan_id" id="peserta_tujuan_id2" required></select>
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
    var data_institut;

    $(document).ready(function() {
        loadSeleksi();
        refresh();    
    });

    function renderPesertaSelect2(response,idSelect2,idModal){
        $(idSelect2).empty();
        $.each(response.data, function(key, value) {
            $(idSelect2).append($('<option>', {
                value: value.id,
                text: `${value.user_name} (${value.peserta_noid})`,
                // verifikator: value.role_user_name,
            }));
        });
        $(idSelect2).trigger('change');
        $(idSelect2).select2({
            theme: "bootstrap-5",
            templateResult: formatDataPeserta,
            dropdownParent: $(idModal),
        });
    }

    function formatDataPeserta(data) {
        // var rowDt = $(data.element).attr('verifikator');
        var rowDt = $('<span>', { style: 'font-size: 13px; color: #888' }).text(rowDt);
        var wrapper = $('<div>').append(data.text).append('<br>').append(rowDt);
        return wrapper;
    }


    function jumlahDataSeleksi() {
        var url = base_url + '/api/jumlah-data-seleksi?seleksi_id='+seleksi_id;
        $('#belum-ada-verifikator').text(0);
        $('#belum-ada-interviewer').text(0);
        fetchData(url, function(response) {
            data=response[0];
            $('#belum-ada-verifikator').text(data.jumlah.belumAdaVerifikator);
            $('#belum-ada-interviewer').text(data.jumlah.belumAdaPewawancara);
            $('#jumlah-verifikator').text(data.role.verifikator);
            $('#jumlah-interviewer').text(data.role.interviewer);
            
            // response.log(response);
        });
    }

    function loadSeleksi() {
        var url = base_url+'/api/seleksi?id='+ seleksi_id;
        fetchData(url, function(response) {
            $('#detail_judul').html(response.data[0].nama);
            console.log(response);
        });
    }

    $('.tab-menu').click(function() {
        refresh();
    });

    function refresh() {
        var activeTabId = $('.tab-pane.active').attr('id');
        showData(activeTabId);
        jumlahDataSeleksi();
    };

    function showData(activeTabId) {
        switch (activeTabId) {
            case 'nav-0':
                loadDataSyarat();
                break;
            case 'nav-1':
                loadDataInstitusi();
                break;
            case 'nav-2':
                loadDataVerifikator();
                loadPesertaVerifikatorSelect2();
                break;
            case 'nav-3':
                loadDataInterviewer();
                loadPesertaInterviewerSelect2();
                break;
            default:
                break;
        }
    }
</script>
<script src="{{ asset('js/web/pengaturan-syarat.js') }}"></script>
<script src="{{ asset('js/web/pengaturan-institusi.js') }}"></script>
<script src="{{ asset('js/web/pengaturan-verifikator.js') }}"></script>
<script src="{{ asset('js/web/pengaturan-interviewer.js') }}"></script>

@endsection