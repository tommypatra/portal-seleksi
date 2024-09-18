// alert(seleksi_id);
var endpoint_institusi=base_url+'/api/seleksi-asal';
var opt_sub_institusi=[];

// sub-institusi-tersedia/{seleksi_id}
subInstitusiTersedia();
function subInstitusiTersedia() {
    var url = base_url + '/api/sub-institusi-tersedia/'+seleksi_id;
    fetchData(url, function(response) {
            // data_institut=response.data;
        $('#sub_institusi_id').empty();
        $.each(response.data, function(key, value) {
            $('#sub_institusi_id').append($('<option>', {
                value: value.id,
                text: value.nama,
                institusi: value.institusi.nama,
            }));
        });
        $('#sub_institusi_id').trigger('change');
        $('#sub_institusi_id').select2({
            theme: "bootstrap-5",
            templateResult: formatData,
        });
    });
}

loadSubInstitusi();
function loadSubInstitusi() {
    var url = base_url + '/api/sub-institusi';
    fetchData(url, function(response) {
        $.each(response.data, function(key, value) {
            opt_sub_institusi.push({id:value.id,nama:value.nama});
        });
    });
}

function loadDataInstitusi(page = 1, search = '') {
    var url = endpoint_institusi + '?seleksi_id=' + seleksi_id + '&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataInstitusi(response);
    },true);
}

//untuk render respon institusi ke tabel
function renderDataInstitusi(response){
    const dataList = $('#data-list-institusi');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            var eid='sub_institusi_id'+dt.id;

            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td>
                    <figure>
                        <blockquote class="blockquote">
                            <select class="form-select sub_institusi_id" name="sub_institusi_id[]" id="${eid}"></select>
                        </blockquote>
                        <figcaption class="blockquote-footer">${dt.institusi_nama}</figcaption>
                    </figure>                            
                </td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus-institusi" data-id="${dt.id})" >Hapus</button>
                    </div>                                        
                </td>
            </tr>`);
            renderSelect('#'+eid,opt_sub_institusi,dt.sub_institusi_id);
        });
        renderPagination(response, pagination);
    }
}

//validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
$('#formInstitusi').validate({
    submitHandler: function(form) {
        const sub_institusi_ids = $('#sub_institusi_id').val();
        $.each(sub_institusi_ids, function(key, value) {
            var data = {
                seleksi_id:seleksi_id,
                sub_institusi_id:value,
            };
    
            saveData(endpoint_institusi, 'POST', data, function(response) {
                //jika berhasil maka
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                loadDataInstitusi();
                subInstitusiTersedia();
            });
        });
    }
});

var oldValue = "";

$('#data-list-institusi').on('focusin', 'input, select, textarea', function() {
    oldValue = $(this).val();
});

$('#data-list-institusi').on('focusout', 'input, select, textarea', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '' && oldValue !== newValue){
        var url = endpoint_institusi + '/' + id;
        var postData = {
            seleksi_id:seleksi_id,
            sub_institusi_id: $(tr).find('.sub_institusi_id').val(),
        };
        // console.log(postData);
        saveData(url, 'PUT', postData, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            subInstitusiTersedia();
        });
    }
});


//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint_institusi
$(document).on('click', '.hapus-institusi', function() {
    const id = $(this).data('id');
    deleteData(endpoint_institusi, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        loadDataInstitusi();
        subInstitusiTersedia();
    });
});

$('#sub_institusi_id').select2({
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