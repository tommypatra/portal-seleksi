const endpoint = base_url+'/api/interviewer';
const role_user_id = localStorage.getItem('role_user_id');
var ver_status = false;

function sudahVerifikasi() {
    // Menghitung jumlah upload_berkas_id yang > 0
    var is_verif = false;
    var jumlah_upload = $('.syarat-accordian').filter(function() {
        var berkas_id =$(this).attr('data-upload_berkas_id'); 
        return berkas_id > 0;
    }).length;

    // Menghitung jumlah verifikasi_valid yang >= 0
    var jumlah_verifikasi = $('.syarat-accordian').filter(function() {
        var verifikasi_cek = $(this).attr('data-verifikasi_valid'); 
        return verifikasi_cek >= 0 && verifikasi_cek!== null && verifikasi_cek !=='';
    }).length;

    // console.log(jumlah_verifikasi+' >= '+jumlah_upload);

    if((jumlah_verifikasi>0 && jumlah_verifikasi>=jumlah_upload) || (jumlah_upload==0))
        is_verif = true;
    return is_verif;

}

function updateDOMVerifikasi(kesimpulan,keterangan){
    var status = `Belum Diverifikasi`;
    var label_ket = (keterangan!=null)?` (${keterangan})`:``;
    if(kesimpulan==1){
        status = `Memenuhi Syarat`;
    }else if(kesimpulan==0){
        status = `Tidak Memenuhi Syarat`;
    }
    return status+label_ket;
}


$(document).ready(function() {
    // Menampilkan data awal
    dataLoad();

    $('#refresh').click(function(){
        dataLoad();
    });


    //listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
    $(document).on('click', '.btn-aksi', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        if (action === 'config') {
            window.location.replace(base_url + '/pengaturan/' + id);
        } else if (action === 'edit') {
            showDataById(endpoint, id, function(response) {
                //jika tidak ada erorr eksekusi form ganti dengan response
                formGanti(response.data);
            });
        } else if (action === 'delete') {
            deleteData(endpoint, id, function() {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                dataLoad();
            });
        }
    });

    // Handle item-paging limit change
    $('.item-paging').on('click', function() {
        vLimit = $(this).data('nilai');
        dataLoad();
    })

    // Handle page change
    $(document).on('click', '#pagination .page-link', function() {
        var page = $(this).data('page');
        var search = $('#search-input').val();
        dataLoad(page, search);
    });

    // Handle page change
    $(document).on('click', '.pagination-verifikasi .page-link', function() {
        var page = $(this).data('page');
        verifikasiPeserta(page);
    });

    //untuk tambah form
    $(document).on('click','.verifikasi-peserta',function(){
        seleksi_id=$(this).data('seleksi_id');
        ver_status=$(this).data('ver_status');
        jum_pendaftar=$(this).data('jum_pendaftar');
        if(jum_pendaftar>0){
            showModalForm();
            verifikasiPeserta();
        }else{
            toastr.error('belum ada peserta yang akan diverifikasi!', 'gagal');
        }
    });    

    $("#formVerifikasi").validate({
        submitHandler: function(form) {
            const id = $('#pendaftar_id').val();
            const type = 'PUT';
            const url = base_url+'/api/kesimpulan-verifikasi-dokumen/' + id;
            if(sudahVerifikasi()){
                if(confirm('apakah anda yakin?'))
                    saveData(url, type, $(form).serialize(), function(response) {
                        // console.log(response);
                        var status=updateDOMVerifikasi(response.data.verifikasi_lulus,response.data.verifikasi_keterangan);
                        $('#kolom-status-verifikasi').html(status);

                        toastr.success(response.message, 'berhasil');
                    });
            }else{
                toastr.error('masih ada dokumen yang belum diverifikasi!', 'gagal');
            }
        }
    });

    // validasi dan save, menggunakan PUT/edit karena sekedar update validasi dari dokumen yg diupload
    $(document).on('submit','.dtVerifikasi',function(event){
        event.preventDefault();
        var $form = $(this);
        var dtPost = $(this).serialize();

        // Parse the serialized data
        var params = new URLSearchParams(dtPost);
        var id = params.get('id');
        var verifikasiValid = params.get('verifikasi_valid');
        var verifikasiKeterangan = params.get('verifikasi_keterangan');

        // Validasi
        var isValid = true;
        var errorMessage = '';

        if (!id) {
            isValid = false;
            errorMessage += 'dokumen id upload tidak boleh kosong.\n';
        }
        if (!verifikasiValid) {
            isValid = false;
            errorMessage += 'status verifikasi tidak boleh kosong.\n';
        }
        if (verifikasiValid === '0' && !verifikasiKeterangan) {
            isValid = false;
            errorMessage += 'keterangan harus diisi jika status verifikasi tidak memenuhi syarat.\n';
        }

        if (!isValid) {
            alert(errorMessage);
            return; // Hentikan eksekusi jika tidak valid
        }

        const url = base_url+'/api/verifikasi-dokumen/' + id;
        saveData(url, 'PUT', dtPost, function(response) {
            // console.log(response);

            //untuk label icon status verifikasi
            var status_verifikasi=`<span class="badge rounded-circle bg-success"><i class="bi bi-check2"></i></span>`;
            if(response.data.verifikasi_valid==0){
                status_verifikasi=`<span class="badge rounded-circle bg-danger"><i class="bi bi-x"></i></span>`;
            }


            $('.syarat-accordian').filter(function() {
                return $(this).data('upload_berkas_id') == response.data.id;
            }).each(function() {
                $(this).attr('data-verifikasi_valid', response.data.verifikasi_valid);
                $(this).find('.status-verifikasi').html(status_verifikasi);
            });

            // $('#status-verifikasi-'+response.data.id).html(status_verifikasi);
            toastr.success(response.message, 'berhasil');
        });

    });

    $(document).on('click', '.syarat-accordian', function () {
        var accordionButton = $(this);
        var accordionItem = accordionButton.closest('.accordion-item');
        
        accordionItem.one('shown.bs.collapse', function () {
            var url = accordionButton.data('url');
            var id = accordionButton.data('id');
            var upload_berkas_id = accordionButton.data('upload_berkas_id');
            var verifikasi_valid = accordionButton.data('verifikasi_valid');
            var verifikasi_keterangan = accordionButton.data('verifikasi_keterangan');
            var jenis = accordionButton.data('jenis');
        
            var fileViewer = accordionItem.find('#file-viewer-'+id); // Ambil elemen file viewer
            var ms=(verifikasi_valid==1)?"selected":"";
            var tms=(verifikasi_valid==0)?"selected":"";
            var konten=`<span class="badge text-bg-danger">tidak ada</span>`;
            if(url !== ''){
                konten=`<iframe src="${url}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                        <div><a href="${url}" target="_blank">Download</a></div>`; 
                if(ver_status)
                    konten+=`
                        <h5 class="mt-2">Hasil Verifikasi</h5>
                        <form class='dtVerifikasi' id="dtVerifikasi-${upload_berkas_id}">
                            <input type="hidden" name="id" value="${upload_berkas_id}">
                            <div class="mb-2">
                                <select class="form-select" name="verifikasi_valid" id="verifikasi_valid" required>
                                    <option value="">--- Pilih ---</option>
                                    <option value="1" ${ms}>Memenuhi Syarat</option>
                                    <option value="0" ${tms}>Tidak Memenuhi Syarat</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control" name="verifikasi_keterangan" id="verifikasi_keterangan" rows="3">${myLabel(verifikasi_keterangan)}</textarea>
                            </div>       
                            <button type="submit" class="btn btn-primary mb-3">simpan</button>                                   
                        </form>
                    `;
            }
            // console.log(konten);
            fileViewer.empty();
            fileViewer.html(konten);
            console.log(fileViewer.html());
        }).on('hidden.bs.collapse', function () {
            var id = accordionButton.data('id');
            var fileViewer = accordionItem.find('#file-viewer-'+id); // Ambil elemen file viewer
            fileViewer.empty(); // Hapus konten saat accordion disembunyikan
        });
    });
    
});

//untuk show modal form
function showModalForm() {
    var fModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
        keyboard: false
    });

    if(ver_status){
        $('#kesimpulan-verifikasi').show();
    }
    fModalForm.show();
}

//load data interviewer pada jadwal tersedia
function dataLoad(page = 1, search = '') {
    var url = base_url+'/api/peserta-interviewer' + '?seleksi_id='+seleksi_id+'&role_user_id='+role_user_id+'&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderData(response);
    },true);
}

function hasilInterview(wawancara){
    var data=wawancara[0];
    var bgver = (data.nilai!=null) ? 'success' : 'danger';
    var status_interview=`<span class="badge rounded-circle bg-${bgver}"><i class="bi bi-x"></i></span>`;
    if(bgver=='success'){
        status_interview=`<span class="badge rounded-circle bg-${bgver}"><i class="bi bi-check2"></i></span>`;
    }

    return status_interview;
}

//untuk render dari database
function renderData(response) {
    const dataList = $('#data-list');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if (response.data.length > 0) {
        $.each(response.data, function(index, dt) {
            // const publish=(dt.is_publish==1)?'Terpublikasi':'Belum Terpublikasi';
            // const bgdft = (dt.daftar_status) ? 'success' : 'danger';
            const row = `<tr>
                        <td>${no++}</td>
                        <td>
                            <figure>
                                <blockquote class="blockquote">
                                    <p>${dt.peserta_user_name}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer">${dt.peserta_user_email}</figcaption>
                            </figure>                            
                        </td>
                        <td>${dt.noid}</td>
                        <td>${dt.institusi_nama}/ ${dt.sub_institusi_nama} - ${dt.sub_institusi_jenis}</td>
                        <td>${hasilInterview(dt.wawancara)}</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onClick="penilaianPeserta(${dt.id})" type="button" data-jum_pendaftar="${dt.jumlah_pendaftar}" data-ver_status="${dt.verifikasi_status}" data-role_user_id="${dt.id}" data-seleksi_id="${dt.id}"><i class="bi bi-journal-check"></i></button>
                        </td>
                    </tr>`;
            dataList.append(row);
        });
        renderPagination(response, pagination);
    }
}

function penilaianPeserta(pendaftar_id){
    var url = base_url + '/api/penilaian-interview/'+pendaftar_id;
    fetchData(url, function(response) {
        currentIndex = 0;        
        topik_interviews=response.topik_interviews;
        // pendaftar=response.pendaftar;
        // renderFormVerifikasi(response);
        displayPendaftar(response.pendaftar);
        displaySoal(currentIndex);
        showModalForm();
    },false);
}

function displayPendaftar(dt){
    $('#identitas_peserta').html(`
        <div class="row">
            <div class="col-lg-12">
                <h4>${dt.seleksi_nama.toUpperCase()} (${dt.seleksi_tahun})</h4>
            </div>
        </div>


        <div class="mb-3" style="max-width: 540px;">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="${base_url+'/'+dt.identitas_foto}" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col-md-8">
                <h5 class="card-title">${dt.user_name.toUpperCase()}</h5>
                <div class="card-text">Nomor ID : ${dt.peserta_noid}</div>
                <div class="card-text">${dt.sub_institusi_nama} ${dt.institusi_nama}</div>
                <div class="card-text"><small class="text-muted">${dt.identitas_alamat} - ${dt.identitas_hp}</small></div>
            </div>
        </div>
        </div>
    `);
}

function displaySoal(index) {
    var soal = topik_interviews[index];
    $('#soal').text(soal.bank_soal);
    $('#kategori').text("Kategori: " + soal.kategori_soal);
    $('#bobot').text("Bobot: " + soal.bobot);
    $('#soal-info').text("Soal ke " + (index + 1) + " dari " + topik_interviews.length + " total soal");

    // Logika untuk hide/show tombol "Soal Sebelumnya" dan "Soal Berikutnya"
    if (index === 0) {
        $('#prev').hide(); 
    } else {
        $('#prev').show(); 
    }

    if (index === topik_interviews.length - 1) {
        $('#next').hide(); 
    } else {
        $('#next').show();
    }    
}

$('#prev').click(function() {
    if (currentIndex > 0) {
        currentIndex--;
        displaySoal(currentIndex);
    } else {
        alert("Ini adalah soal pertama!");
    }
});

// Event handler untuk tombol 'Soal Berikutnya'
$('#next').click(function() {
    if (currentIndex < topik_interviews.length - 1) {
        currentIndex++;
        displaySoal(currentIndex);
    } else {
        alert("Ini adalah soal terakhir!");
    }
});

//untuk render syarat, dok upload dan verifikasi
function renderSyaratDokUpload(syarat) {
    var jumlah_upload=0;
    var jumlah_upload_verifikasi=0;
    ret = ``;
    if(syarat.length>0){
        // ret+=`<div class="row">`;
        ret+=`<div class="accordion mb-3" id="accordionFlushSyarat">`
        $.each(syarat, function(index, dt) {
            var link_dok='<span class="badge text-bg-danger">tidak ada</span>';
            var url_file='';
            var wajib = (dt.is_wajib)?"(wajib)":"(optional)";
            var keterangan = myLabel(dt.keterangan);
            // console.log(index);
            var upload_berkas_id;
            var verifikasi_keterangan="";
            var verifikasi_valid="";

            var status_upload=`<i class="bi bi-file-earmark-excel"></i>`;
            var status_verifikasi='';
            if(dt.upload){
                jumlah_upload++;
                url_file=base_url+'/'+dt.upload.path;
                upload_berkas_id=dt.upload.id;
                verifikasi_keterangan=dt.upload.verifikasi_keterangan;
                verifikasi_valid=dt.upload.verifikasi_valid;
    
                status_upload=`<i class="bi bi-file-earmark-check"></i>`;
                if(verifikasi_valid==1){
                    jumlah_upload_verifikasi++;
                    status_verifikasi=`<span class="badge rounded-circle bg-success"><i class="bi bi-check2"></i></span>`;
                }else if(verifikasi_valid==0){
                    jumlah_upload_verifikasi++;
                    status_verifikasi=`<span class="badge rounded-circle bg-danger"><i class="bi bi-x"></i></span>`;
                }
            }
            ret+=`  <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button syarat-accordian collapsed" data-upload_berkas_id="${upload_berkas_id}" data-verifikasi_valid="${verifikasi_valid}" data-verifikasi_keterangan="${verifikasi_keterangan}" data-jenis="${dt.jenis}" data-url="${url_file}" data-id="${index}" type="button" data-bs-toggle="collapse" data-bs-target="#flush-${index}" aria-expanded="false" aria-controls="flush-${index}">
                            ${status_upload}<span class="m-1">${dt.nama} ${wajib}</span><span class="m-1 status-verifikasi" id="status-verifikasi-${upload_berkas_id}">${status_verifikasi}</span>
                            </button>
                        </h2>
                        <div id="flush-${index}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushSyarat">
                            <div class="accordion-body">
                                <blockquote class="blockquote mb-0">
                                    <p>${dt.nama}</p>
                                    <footer class="blockquote-footer">${keterangan}</footer>
                                </blockquote>
                            
                                <div id="file-viewer-${index}"></div>
                            </div>
                        </div>
                    </div>`;
        });
        ret+=`</div>`;
    }
    if(jumlah_upload_verifikasi>0 && jumlah_upload_verifikasi==jumlah_upload)
        sudah_verifikasi=true;

    return ret;
}

//untuk render dari database
function renderFormVerifikasi(response) {
    const dataList = $('#data-list-verifikasi');
    const pagination = $('.pagination-verifikasi');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if (response.data.length > 0) {
        $.each(response.data, function(index, dt) {
            var status = updateDOMVerifikasi(dt.pendaftar_verifikasi_lulus,dt.pendaftar_verifikasi_keterangan);

            $('#judul_seleksi').html(dt.seleksi_tahun+' - '+dt.seleksi_nama);

            $('#pendaftar_id').val(dt.pendaftar_id);
            $('#verifikasi_keterangan').val(dt.pendaftar_verifikasi_keterangan);
            $('#verifikasi_lulus').val(dt.pendaftar_verifikasi_lulus);

            $('#identitas_peserta').html(`
                <h5><i class="bi bi-person-vcard"></i> Data Identitas</h5>
                <table class="table">
                    <tr>
                        <td width='20%'>Nama</td>
                        <td width='3%'>:</td>
                        <td width='77%'>${dt.user_name}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>${dt.user_email}</td>
                    </tr>
                    <tr>
                        <td>No. ID</td>
                        <td>:</td>
                        <td>${dt.peserta_noid}</td>
                    </tr>
                    <tr>
                        <td>Institusi</td>
                        <td>:</td>
                        <td>${dt.institusi_nama}/ ${dt.subinstitusi_nama}</td>
                    </tr>
                    <tr>
                        <td>Status Verifikasi</td>
                        <td>:</td>
                        <td id="kolom-status-verifikasi">${status}</td>
                    </tr>
                </table>
                
                <h5><i class="bi bi-card-checklist"></i> Syarat Dokumen Upload</h5>
                ${renderSyaratDokUpload(dt.syarat)}
            `);
        });
        renderPagination(response, pagination);
    }
}
