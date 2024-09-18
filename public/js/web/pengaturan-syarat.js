// alert(seleksi_id);
var endpoint_syarat=base_url+'/api/syarat';
var opt_jenis=[
    { 'id':'pdf','nama':'PDF' },
    { 'id':'img','nama':'Gambar' }
];
var opt_is_wajib=[
    { 'id':0,'nama':'Pilihan' },
    { 'id':1,'nama':'Wajib' }
];
//load data institusi
loadDataSyarat();

// function refresh(){
//     loadDataSyarat();
// }

function resetNomorUrut() {
    var nomor = 1;
    $('#data-list-syarat tr').each(function(index) {
        $(this).find('td:first').text(nomor);
        nomor++;
    });``
}

//tombol tambah di klik
function tambahSyarat() {
    $('#data-list-syarat').prepend(`
            <tr data-id="">
                <td></td>
                <td><input type="text" class="form-control" name="nama[]"></td>
                <td>
                    <select class="form-select" name="jenis[]">
                        <option value='pdf'>PDF</option>
                        <option value='img'>Gambar</option>
                    </select>
                </td>
                <td>
                    <select class="form-select" name="is_wajib[]">
                        <option value='1'>Wajib</option>
                        <option value='0'>Tidak Wajib</option>
                    </select>
                </td>
                <td><textarea rows="3" class="form-control" name="keterangan[]"></textarea></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-success simpan-baris-syarat">Simpan</button>
                        <button type="button" class="btn btn-warning batal-baris-syarat">Batal</button>
                    </div>
                </td>
            </tr>
    `);
    resetNomorUrut();
}

function loadDataSyarat(page = 1, search = '') {
    var url = endpoint_syarat + '?seleksi_id=' + seleksi_id + '&page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataSyarat(response);
    },true);
}

//untuk render respon institusi ke tabel
function renderDataSyarat(response){
    const dataList = $('#data-list-syarat');
    const pagination = $('#pagination');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            var eid1='jenis'+dt.id;
            var eid2='is_wajib'+dt.id;

            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td><input type="text" class="form-control nama" name="nama[]" value="${dt.nama}"></td> 
                <td><select class="form-select jenis" name="jenis[]" id="${eid1}"></select></td> 
                <td><select class="form-select is_wajib" name="is_negeri[]" id="${eid2}"></select></td> 
                <td><textarea rows="3" class="form-control keterangan" name="keterangan[]">${dt.keterangan}</textarea></td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus-syarat" data-id="${dt.id})" >Hapus</button>
                    </div>                                        
                </td>
            </tr>`);
            renderSelect('#'+eid1,opt_jenis,dt.jenis);
            renderSelect('#'+eid2,opt_is_wajib,dt.is_wajib);
        });
        renderPagination(response, pagination);
    }
}

// function batalBaris(button) {
$(document).on('click', '.batal-baris-syarat', function() {
    $(this).closest('tr').remove();
    resetNomorUrut();
});

// function simpanBaris(button) {
$(document).on('click', '.simpan-baris-syarat', function() {
    const baris = $(this).closest('tr');
    var postData = {
        seleksi_id: seleksi_id,
        nama: baris.find("input[name='nama[]']").val(),
        jenis: baris.find("select[name='jenis[]']").val(),
        is_wajib: baris.find("select[name='is_wajib[]']").val(),
        keterangan: baris.find("textarea[name='keterangan[]']").val(),
    };

    saveData(endpoint_syarat, 'POST', postData, function(response) {
        var dt = response.data;
        baris.find(".simpan-baris-syarat, .batal-baris-syarat").hide();
        baris.attr("data-id", dt.id);
        baris.find("td:eq(1)").html(dt.nama);
        baris.find("td:eq(2)").html(dt.jenis_label);
        baris.find("td:eq(3)").html(dt.is_wajib_label);
        baris.find("td:eq(4)").html(dt.keterangan);
        baris.find("td:eq(5)").html(`<div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-danger hapus-syarat" data-id="${dt.id})" >Hapus</button>
                                    </div>`);
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
    });    
});

var oldValue = "";
$('#data-list-syarat').on('focusin', 'input, select, textarea', function() {
    oldValue = $(this).val();
});

$('#data-list-syarat').on('focusout', 'input, select, textarea', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '' && oldValue !== newValue){
        var url = endpoint_syarat + '/' + id;
        var postData = {
            seleksi_id: seleksi_id,
            nama: $(tr).find('.nama').val(),
            jenis: $(tr).find('.jenis').val(),
            is_wajib: $(tr).find('.is_wajib').val(),
            keterangan: $(tr).find('.keterangan').val(),
        };
        // console.log(postData);
        saveData(url, 'PUT', postData, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            // loadDataSyarat();
        });
    }
});


//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint_syarat
$(document).on('click', '.hapus-syarat', function() {
    const id = $(this).data('id');
    deleteData(endpoint_syarat, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        loadDataSyarat();
    });
});