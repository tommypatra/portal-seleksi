// alert(seleksi_id);
var endpoint=base_url+'/api/sub-institusi';
loadData();
loadDataInstitusi();

function refresh(){
    loadData();
}

function resetNomorUrut() {
    var nomor = 1;
    $('#data-list tr').each(function(index) {
        $(this).find('td:first').text(nomor);
        nomor++;
    });``
}

//tombol tambah di klik
function tambah() {
    $('#data-list').prepend(`
            <tr data-id="">
                <td></td>
                <td><input type="text" class="form-control nama" name="nama[]"></td>
                <td><input type="text" class="form-control jenis" name="jenis[]"></td>
                <td><textarea class="form-control keterangan" name="keterangan[]"></textarea></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-success simpan-baris">Simpan</button>
                        <button type="button" class="btn btn-warning batal-baris">Batal</button>
                    </div>
                </td>
            </tr>
    `);
    resetNomorUrut();
}

function loadDataInstitusi() {
    var url = base_url+'/api/institusi?id='+ institusi_id;
    fetchData(url, function(response) {
        $('#detail_judul').html(response.data[0].nama);
        // console.log(response);
    });
}

function loadData(page = 1, search = '') {
    var url = endpoint + '?institusi_id='+ institusi_id +'&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderData(response);
    });
}

//untuk render respon institusi ke tabel
function renderData(response){
    const dataList = $('#data-list');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td><input type="text" class="form-control nama" name="nama[]" value="${dt.nama}"></td> 
                <td><input type="text" class="form-control jenis" name="jenis[]" value="${dt.jenis}"></td> 
                <td><textarea class="form-control keterangan" name="keterangan[]">${dt.keterangan}</textarea></td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus" data-id="${dt.id}" >Hapus</button>
                    </div>                                        
                </td>
            </tr>`);
        });
        renderPagination(response, pagination);
    }
}

// function batalBaris(button) {
$(document).on('click', '.batal-baris', function() {
    $(this).closest('tr').remove();
    resetNomorUrut();
});

// function simpanBaris(button) {
$(document).on('click', '.simpan-baris', function() {
    const baris = $(this).closest('tr');
    var postData = {
        institusi_id: institusi_id,
        nama: baris.find("input[name='nama[]']").val(),
        jenis: baris.find("input[name='jenis[]']").val(),
        keterangan: baris.find("textarea[name='keterangan[]']").val(),
    };

    saveData(endpoint, 'POST', postData, function(response) {
        var dt = response.data;
        baris.find(".simpan-baris, .batal-baris").hide();
        baris.attr("data-id", dt.id);
        baris.find("td:eq(4)").html(`<div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-danger hapus" data-id="${dt.id}" >Hapus</button>
                                    </div>`);
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
    });    
});

var oldValue = "";

$('#data-list').on('focusin', 'input, select, textarea', function() {
    oldValue = $(this).val();
});

$('#data-list').on('focusout', 'input, select, textarea', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '' && oldValue !== newValue){
        var url = endpoint + '/' + id;
        var postData = {
            nama: $(tr).find('.nama').val(),
            jenis: $(tr).find('.jenis').val(),
            keterangan: $(tr).find('.keterangan').val(),
        };
        // console.log(postData);
        saveData(url, 'PUT', postData, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            // loadData();
        });
    }
});


//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
$(document).on('click', '.hapus', function() {
    const id = $(this).data('id');
    // console.log(id);
    deleteData(endpoint, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        loadData();
    });
});