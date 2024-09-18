const endpoint = base_url+'/api/jadwal-seleksi';
const role_user_id = localStorage.getItem('role_user_id');


$(document).ready(function() {
    // Menampilkan data awal
    dataLoad();
    // dataJenis();

    $('#refresh').click(function(){
        dataLoad();
    });

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });

    function dataLoad(page = 1, search = '') {
        var url = endpoint + '?role_user_id='+role_user_id+'&page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            // console.log(response);
            renderData(response);
        });
    }

    // Handle item-paging limit change
    $('.item-paging').on('click', function() {
        vLimit = $(this).data('nilai');
        dataLoad();
    })

    // Handle page change
    $(document).on('click', '.page-link', function() {
        var page = $(this).data('page');
        var search = $('#search-input').val();
        dataLoad(page, search);
    });

    $(document).on('click','.daftar-sekarang',function(){
        if(confirm('Anda yakin akan mendaftar?')){
            var dataPost = {
                tahun:$(this).data('tahun'),
                seleksi_id:$(this).data('seleksi_id'),
                peserta_id:localStorage.getItem('role_user_id'),
            };
            saveData(base_url+'/api/pendaftar', 'POST', dataPost, function(response) {
                //jika berhasil maka
                $('#form').trigger('reset');
                $('#form input[type="hidden"]').val('');
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                dataLoad();
            });
            
        }
    })

    //listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
    $(document).on('click', '.hapus', function() {
        const id = $(this).data('pendaftar_id');
        deleteData(base_url+'/api/pendaftar', id, function() {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            dataLoad();
        });
    });

    $(document).on('change', '.upload-file', function() {
        var seleksi_id = $(this).data('seleksi_id');
        var pendaftar_id = $(this).data('pendaftar_id');
        var syarat_id = $(this).data('syarat_id');
        var file = $(this)[0].files[0];

        if (!file) {
            // toastr.error('Tidak ada file yang dipilih!', 'Gagal');
            return;
        }

        var formData = new FormData();
        formData.append('pendaftar_id', pendaftar_id);
        formData.append('syarat_id', syarat_id);
        formData.append('file', file);

        saveData(base_url+'/api/upload-berkas', 'POST', formData, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            syaratUpload(seleksi_id);                
            // modal.hide();
        });
    });    



    $(document).on('click', '.hapus-berkas', function() {
        var id = $(this).data('id');
        var seleksi_id = $(this).data('seleksi_id');
        deleteData(base_url+'/api/upload-berkas', id, function() {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            // dataLoad();
            syaratUpload(seleksi_id)
        });
    });     


    //load syarat dan uploadnya
    function syaratUpload(seleksi_id) {
        fetchData(endpoint+'?role_user_id='+role_user_id+'&seleksi_id='+seleksi_id, function(response) {
            // console.log(response);
            $('#list-group-'+seleksi_id).empty();
            $('#list-group-'+seleksi_id).html(listSyarat(response.data[0]));
        });
    }

    //menampilkan list syarat
    function listSyarat(dataRespon){
        var responseSyarat=dataRespon.syarat;
        var responsePendaftar=dataRespon.pendaftar;
        var listSyarat = "";
        var is_terdaftar = (responsePendaftar.length>0)?true:false;

        if(responseSyarat.length>0){
            listSyarat=`<ol class="list-group list-group-numbered" id="list-group-${dataRespon.id}">`;
            $.each(responseSyarat, function(index, dt) {
                var label_wajib=(dt.is_wajib==1)?'Wajib':'Tidak Wajib';
                var upload="";
                var uploadBerkas=dt.upload;
                console.log(uploadBerkas);
                if(is_terdaftar){                
                    var pendaftar_id= responsePendaftar[0].id;
                    if(dataRespon.daftar_status){
                        upload=`<input type="file" style="font-size:12px;" class="form-control upload-file" data-seleksi_id="${dataRespon.id}" data-syarat_id="${dt.id}" data-pendaftar_id="${pendaftar_id}" accept="${(dt.jenis === 'pdf')?'application/pdf':'image/*'}">`;
                        if(uploadBerkas!==null){
                            if(dt.jenis==='pdf')
                                upload=`<a href="${base_url}/${uploadBerkas.path}" target="_blank"><i class="bi bi-box-arrow-down"></i> ${dt.nama}</a>`;
                            else
                                upload=`<a href="${base_url}/${uploadBerkas.path}" target="_blank"><img src="${base_url}/${uploadBerkas.path}" height="150px"></a> <div><i class="bi bi-image"></i> ${dt.nama}</div>`;

                            if(dataRespon.daftar_status)
                                upload+=`<div class="mt-2"><button class="btn btn-sm btn-danger hapus-berkas" data-seleksi_id="${dataRespon.id}" data-id="${uploadBerkas.id}" ><i class="bi bi-trash"></i></button></div>`;
                        }
                    }
                }
                listSyarat+=`<li class="list-group-item d-flex justify-content-between align-items-start">

                                <div class="ms-2 me-auto">
                                <div class="fw-bold">${dt.nama}</div>                                
                                    <div style="font-size:12px">${myLabel(dt.keterangan)}</div>
                                    ${upload}
                                </div>
                                <span class="badge text-bg-primary rounded-pill">${dt.jenis}</span>                            
                            </li>`;
            });
            listSyarat+=`</ol>`;
        }
        return listSyarat;
    }

    //untuk render dari database
    function renderData(response) {
        const dataList = $('#data-list');
        const pagination = $('#pagination');
        let no = (response.current_page - 1) * response.per_page + 1;
        dataList.empty();
        if (response.data.length > 0) {
            $.each(response.data, function(index, dt) {
                const bgdft = (dt.daftar_status) ? 'success' : 'danger';
                const bgver = (dt.verifikasi_status) ? 'success' : 'danger';
                var pendaftar_id;
                var btnDaftar=``;
                if(dt.daftar_status)
                    btnDaftar =`<a href="javascript:;" class="btn btn-primary btn-sm daftar-sekarang" data-tahun="${dt.tahun}" data-seleksi_id="${dt.id}">Daftar Sekarang</a>`;
                
                if(dt.pendaftar.length>0){
                    pendaftar_id = dt.pendaftar[0].id;
                    var btnBatal=``;
                    if(dt.daftar_status)
                        btnBatal=`<a href="javascript:;" class="btn btn-danger btn-sm hapus" data-pendaftar_id="${pendaftar_id}">Batalkan</a>`;
                    
                    btnDaftar =` <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-secondary btn-sm upload-berkas" data-pendaftar_id="${pendaftar_id}">Finalisasi</a>
                                        ${btnBatal}                                    
                                    </div>`;
                }

                const row = `<div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="card">
                                    <div class="card-header">
                                        Jenis : ${dt.jenis}
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">${dt.nama}</h5>
                                        <p class="card-text">${dt.keterangan}</p>

                                        <p class="d-inline-flex gap-1">
                                            <a data-bs-toggle="collapse" href="#syarat-${dt.id}" role="button" aria-expanded="false" aria-controls="syarat-${dt.id}">
                                                Dokumen Syarat
                                            </a>
                                        </p>
                                        <div class="collapse" id="syarat-${dt.id}">
                                            ${listSyarat(dt)}
                                        </div>                                    

                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            Jadwal Pendaftaran : <span class="badge text-bg-${bgdft}">${dt.daftar_mulai} sd ${dt.daftar_selesai}</span>
                                        </li>
                                        <li class="list-group-item">
                                            Jadwal Verifikasi : <span class="badge text-bg-${bgver}">${dt.verifikasi_mulai} sd ${dt.verifikasi_selesai}</span>
                                        </li>
                                    </ul>                                    
                                    <div class="card-footer text-body-secondary">
                                        ${btnDaftar}
                                        ${timeAgo(dt.created_at)}
                                    </div>                                
                                </div>
                            </div>`;
                dataList.append(row);
            });
            renderPagination(response, pagination);
        }
    }

});
