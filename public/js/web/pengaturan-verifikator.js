// alert(seleksi_id);
var endpoint_verifikator=base_url+'/api/role-seleksi';
var opt_sub_verifikator=[];

verifikatorTersedia();
function verifikatorTersedia() {
    var url = base_url + '/api/role-tersedia/'+seleksi_id+'/verifikator';
    fetchData(url, function(response) {
            // data_institut=response.data;
        $('#verifikator_id').empty();
        $.each(response.data, function(key, value) {
            $('#verifikator_id').append($('<option>', {
                value: value.id,
                text: value.role_user.user.name,
            }));
        });

        $('#verifikator_id').trigger('change');
    });
    
}

$('.bagi-peserta-verifikator').on('click', function() {
    var jumlahBelumVerifikator = parseInt($('#belum-ada-verifikator').text(), 10);
    if (jumlahBelumVerifikator > 0) {
        var url = base_url +'/api/generate-pembagian-verifikator/'+seleksi_id;
        var selectedValues = [];
        $('#data-list-verifikator .cek-item:checked').each(function() {
            selectedValues.push($(this).val());
        });

        var dataPost = {
            role_seleksi_id : selectedValues,
        };
        saveData(url, 'POST', dataPost, function(response) {
            console.log(response);
            if (confirm("Apakah anda yakin?")) {
                simpanPembagianVerifikator(response);
                refresh();
                loadPesertaVerifikatorSelect2();
            }
        });

    }
});

function simpanPembagianVerifikator(data){
    var url = base_url +'/api/pemeriksa-syarat';
    $.each(data, function(index, dt) {
        var dataPost = {
            pendaftar_id : dt.pendaftar_id,
            role_seleksi_id : dt.role_seleksi_id,
        };
        saveData(url, 'POST', dataPost, function(response) {
            console.log(response);
        });
    });
}

function loadDataVerifikator(page = 1, search = '') {
    var url = endpoint_verifikator + '?seleksi_id='+seleksi_id+'&role=verifikator&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataVerifikator(response);
    },true);
}

//untuk render respon institusi ke tabel
function renderDataVerifikator(response){
    const dataList = $('#data-list-verifikator');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {

            var peserta=`<span class="badge text-bg-danger rounded-circle" id="jumlah-interviewer">0</span>`;
            if(dt.jumlah_peserta>0){

                var listpeserta=`<table>`;
                var nopes=1;
                $.each(dt.list_peserta, function(key, value) {
                    $akun=value.pendaftar.peserta;
                    listpeserta+=` <tr id="row-${value.id}"> 
                                        <td width="10%">${nopes++}.</td>
                                        <td width="70%">${$akun.user.name}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-warning btn-sm tukar-peserta-verifikator" data-id="${value.id}"><i class="bi bi-arrow-left-right"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm hapus-peserta-verifikator" data-id="${value.id}"><i class="bi bi-trash"></i></button>
                                            </div>                     
                                        </td>
                                    </tr>`;
                });
                listpeserta+=`</table>`;

                peserta=`<span class="badge text-bg-success rounded-circle">${dt.jumlah_peserta}</span>                        
                            <div class="card mt-2" style="width: 18rem;">
                                <div class="card-body">
                                ${listpeserta}
                                </div>                                
                            </div>`;            
            }

            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td><input type="checkbox" class="form-check-input cek-item" name="cek-item[]" value="${dt.id}"></td> 
                <td>
                    <figure>
                        <blockquote class="blockquote">
                            ${dt.user_nama}
                        </blockquote>
                        <figcaption class="blockquote-footer">${dt.user_email}</figcaption>
                    </figure>                            
                </td> 
                <td>${peserta}</td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus-verifikator" data-id="${dt.id}" ><i class="bi bi-trash"></i></button>
                    </div>                                        
                </td>
            </tr>`);
        });
        renderPagination(response, pagination);
    }
}

//validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
$('#formVerifikator').validate({
    submitHandler: function(form) {
        const verifikator_ids = $('#verifikator_id').val();
        $.each(verifikator_ids, function(key, value) {
            var data = {
                seleksi_id:seleksi_id,
                role_user_id:value,
            };
    
            saveData(endpoint_verifikator, 'POST', data, function(response) {
                //jika berhasil maka
                console.log(data);
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
            });
        });
        refresh();
        verifikatorTersedia();
    }
});


//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint_verifikator
$(document).on('click', '.hapus-verifikator', function() {
    const id = $(this).data('id');
    deleteData(endpoint_verifikator, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        refresh();
        verifikatorTersedia();
    });
});

$('#verifikator_id').select2({
    theme: "bootstrap-5",
});

$(document).on('click','.hapus-peserta-verifikator',function(){
    var id = $(this).data('id');
    var url = base_url + '/api/pemeriksa-syarat';
    deleteData(url, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        $('#row-' + id).remove();
        refresh();
        loadPesertaVerifikatorSelect2();
    });
})

$('#pemeriksa_syarat_tujuan_id').select2({
    theme: "bootstrap-5",
});

$(document).on('click','.tukar-peserta-verifikator',function(){
    var id = $(this).data('id');
    var modalElement = document.getElementById('modalTukar');
    var modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });    
    $('#peserta_asal_id').val(id).trigger('change');
    modal.show();
})

function loadPesertaVerifikatorSelect2(){
    var url = base_url + '/api/pemeriksa-syarat?seleksi_id='+seleksi_id;
    fetchData(url, function(response) {
        console.log(response);
        renderPesertaSelect2(response,'#peserta_asal_id','#modalTukar');
        renderPesertaSelect2(response,'#peserta_tujuan_id','#modalTukar');
    });    
}

//untuk validasi tambahan
$.validator.addMethod("differentValues", function(value, element, param) {
    console.log(value);
    console.log($(param).val());
    return value !== $(param).val();
}, "Asal dan Tujuan tidak boleh sama.");

$("#formTukar").validate({
    rules: {
        peserta_tujuan_id: {
            differentValues: "#peserta_asal_id"
        }
    },
    messages: {
        peserta_tujuan_id: {
            differentValues: "Asal dan Tujuan tidak boleh sama."
        }
    },
    submitHandler: function(form) {
        var url = base_url + '/api/tukar-peserta-verifikasi';
        saveData(url, 'PUT', $(form).serialize(), function(response) {
            var modalElement = document.getElementById('modalTukar');
            var mymodal = bootstrap.Modal.getInstance(modalElement);
            mymodal.hide();


            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            // simpanPembagianVerifikator(response);
            refresh();
            loadPesertaVerifikatorSelect2();
        });    
    }
});

