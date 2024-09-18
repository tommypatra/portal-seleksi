// alert(seleksi_id);
var endpoint_interviewer=base_url+'/api/role-seleksi';
var opt_sub_interviewer=[];

// interviewer-tersedia/{seleksi_id}
interviewerTersedia();
function interviewerTersedia() {
    var url = base_url + '/api/role-tersedia/'+seleksi_id+'/interviewer';
    fetchData(url, function(response) {
        console.log(response);
        $('#interviewer_id').empty();
        $.each(response.data, function(key, value) {
            $('#interviewer_id').append($('<option>', {
                value: value.id,
                text: value.user.name,
            }));
        });
        $('#interviewer_id').trigger('change');
    });
}

function loadDataInterviewer(page = 1, search = '') {
    var url = endpoint_interviewer + '?seleksi_id='+seleksi_id+'&role=interviewer&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataInterviewer(response);
    },true);
}

//untuk render respon institusi ke tabel
function renderDataInterviewer(response){
    const dataList = $('#data-list-interviewer');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            var wawancara=`<span class="badge text-bg-danger rounded-circle" id="jumlah-interviewer">0</span>`;
            if(dt.jumlah_wawancara>0){
                var listwawancara=`<table>`;
                $.each(dt.list_wawancara, function(key, value) {
                    var akun=value.pendaftar.peserta;
                    listwawancara+=` <tr id="row-${value.id}"> 
                                        <td width="10%">${key+1}.</td>
                                        <td width="70%">${akun.user.name}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-warning btn-sm tukar-peserta-interviewer" data-id="${value.id}"><i class="bi bi-arrow-left-right"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm hapus-peserta-interviewer" data-id="${value.id}"><i class="bi bi-trash"></i></button>
                                            </div>                     
                                        </td>
                                    </tr>`;
                });
                listwawancara+=`</table>`;

                wawancara=`<span class="badge text-bg-success rounded-circle">${dt.jumlah_wawancara}</span>                        
                            <div class="card mt-2" style="width: 18rem;">
                                <div class="card-body">
                                ${listwawancara}
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
                <td>${wawancara}</td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus-interviewer" data-id="${dt.id}" ><i class="bi bi-trash"></i></button>
                    </div>                                        
                </td>            
            </tr>`);
        });
        renderPagination(response, pagination);
    }
}

//validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
$('#formInterviewer').validate({
    submitHandler: function(form) {
        const interviewer_ids = $('#interviewer_id').val();
        $.each(interviewer_ids, function(key, value) {
            var data = {
                seleksi_id:seleksi_id,
                role_user_id:value,
            };
    
            saveData(endpoint_interviewer, 'POST', data, function(response) {
                //jika berhasil maka
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
            });
        });
        refresh();
        interviewerTersedia();
    }
});


//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint_interviewer
$(document).on('click', '.hapus-interviewer', function() {
    const id = $(this).data('id');
    deleteData(endpoint_interviewer, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        refresh();
        interviewerTersedia();
    });
});

$('#interviewer_id').select2({
    theme: "bootstrap-5",
});

function formatData(data) {
    if (!data.id) {
        return data.text;
    }
    var institusi = $(data.element).attr('institusi');
    var institusi = $('<span>', { style: 'font-size: 13px; color: #888' }).text(institusi);
    var wrapper = $('<div>').append(data.text).append('<br>').append(institusi);
    return wrapper;
}

$('.cek-semua').on('click', function() {
    var isChecked = $(this).prop('checked');
    $('.cek-item').prop('checked', isChecked);
});

$('.bagi-peserta-interviewer').on('click', function() {
    var jumlahBeluminterviewer = parseInt($('#belum-ada-interviewer').text(), 10);
    if (jumlahBeluminterviewer > 0) {
        var url = base_url +'/api/generate-pembagian-interviewer/'+seleksi_id;
        var selectedValues = [];
        $('#data-list-interviewer .cek-item:checked').each(function() {
            selectedValues.push($(this).val());
        });

        var dataPost = {
            role_seleksi_id : selectedValues,
        };
        saveData(url, 'POST', dataPost, function(response) {
            // console.log(response);
            if (confirm("Apakah anda yakin?")) {
                simpanPembagianinterviewer(response);
                refresh();
                loadPesertaInterviewerSelect2();
            }
        });

    }
});

function simpanPembagianinterviewer(data){
    var url = base_url +'/api/wawancara-peserta';
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

$(document).on('click','.hapus-peserta-interviewer',function(){
    var id = $(this).data('id');
    var url = base_url + '/api/wawancara-peserta';
    deleteData(url, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        $('#row-' + id).remove();
        refresh();
        loadPesertaInterviewerSelect2();
    });
})

$('#pemeriksa_syarat_tujuan_id').select2({
    theme: "bootstrap-5",
});

$(document).on('click','.tukar-peserta-interviewer',function(){
    var id = $(this).data('id');
    var modalElement = document.getElementById('modalTukar2');
    var modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });    
    $('#peserta_asal_id2').val(id).trigger('change');
    modal.show();
})

function loadPesertaInterviewerSelect2(){
    var url = base_url + '/api/wawancara-peserta?seleksi_id='+seleksi_id;
    fetchData(url, function(response) {
        console.log(response);
        renderPesertaSelect2(response,'#peserta_asal_id2','#modalTukar2');
        renderPesertaSelect2(response,'#peserta_tujuan_id2','#modalTukar2');
    });    
}

//untuk validasi tambahan
$.validator.addMethod("differentValues", function(value, element, param) {
    console.log(value);
    console.log($(param).val());
    return value !== $(param).val();
}, "Asal dan Tujuan tidak boleh sama.");

$("#formTukar2").validate({
    rules: {
        peserta_tujuan_id2: {
            differentValues: "#peserta_asal_id2"
        }
    },
    messages: {
        peserta_tujuan_id2: {
            differentValues: "Asal dan Tujuan tidak boleh sama."
        }
    },
    submitHandler: function(form) {
        var url = base_url + '/api/tukar-peserta-wawancara';
        saveData(url, 'PUT', $(form).serialize(), function(response) {
            var modalElement = document.getElementById('modalTukar2');
            var mymodal = bootstrap.Modal.getInstance(modalElement);
            mymodal.hide();
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            // simpanPembagianVerifikator(response);
            refresh();
            loadPesertaInterviewerSelect2();
        });    
    }
});

