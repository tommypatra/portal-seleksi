// alert(seleksi_id);
var endpoint=base_url+'/api/institusi';
var opt_is_negeri=[
    { 'id':0,'nama':'Swasta' },
    { 'id':1,'nama':'Negeri' }
];
//load data institusi
loadData();

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
                <td>
                    <select class="form-select is_negeri" name="is_negeri[]">
                        <option value='1'>Negeri</option>
                        <option value='0'>Swasta</option>
                    </select>
                </td>
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

function loadData(page = 1, search = '') {
    var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderData(response);
    },true);
}

//untuk render respon institusi ke tabel
function renderData(response){
    const dataList = $('#data-list');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            var eid='is_negeri'+dt.id;

            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td><input type="text" class="form-control nama" name="nama[]" value="${dt.nama}"></td> 
                <td><select class="form-select is_negeri" name="is_negeri[]" id="${eid}"></select></td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-info institusi-detail" data-id="${dt.id}" >Detail</button>
                    <button type="button" class="btn btn-danger hapus" data-id="${dt.id}" >Hapus</button>
                    </div>                                        
                </td>
            </tr>`);
            renderSelect('#'+eid,opt_is_negeri,dt.is_negeri);
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
        nama: baris.find("input[name='nama[]']").val(),
        is_negeri: baris.find("select[name='is_negeri[]']").val(),
    };

    saveData(endpoint, 'POST', postData, function(response) {
        var dt = response.data;
        baris.find(".simpan-baris, .batal-baris").hide();
        baris.attr("data-id", dt.id);
        baris.find("td:eq(3)").html(`<div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-info institusi-detail" data-id="${dt.id}" >Detail</button>
                                        <button type="button" class="btn btn-danger hapus" data-id="${dt.id}" >Hapus</button>
                                    </div>`);
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
    });    
});

var oldValue = "";

$('#data-list').on('focusin', 'input, select, textarea', function() {
    oldValue = $(this).val();
});

$(document).on('click','.institusi-detail',function(){
    const id = $(this).attr('data-id');
    window.location.replace(base_url + '/institusi-detail/'+id);
})

$('#data-list').on('focusout', 'input, select, textarea', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '' && oldValue !== newValue){
        var url = endpoint + '/' + id;
        var postData = {
            nama: $(tr).find('.nama').val(),
            is_negeri: $(tr).find('.is_negeri').val(),
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