// alert(seleksi_id);
var endpoint=base_url+'/api/syarat';

//load data syarat
loadDataSyarat();

function resetNomorUrut() {
    var nomor = 1;
    $('#data-list-syarat tr').each(function(index) {
        $(this).find('td:first').text(nomor);
        nomor++;
    });
}

//tombol tambah di klik
function tambahSyarat() {
    $('#data-list-syarat').prepend(`
            <tr data-id="">
                <td></td>
                <td><input type="text" class="form-control" name="nama[]"></td>
                <td>
                    <select class="form-control" name="is_wajib[]">
                        <option value='1'>Wajib</option>
                        <option value='0'>Tidak Wajib</option>
                    </select>
                </td>
                <td><textarea rows="3" class="form-control" name="keterangan[]"></textarea></td>
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

function loadDataSyarat(page = 1, search = '') {
    var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataSyarat(response);
    });
}

//untuk render respon syarat ke tabel
function renderDataSyarat(response){
    const dataList = $('#data-list-syarat');
    const pagination = $('#pagination-syarat');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td>${dt.nama}</td> 
                <td>${dt.is_wajib_label}</td> 
                <td>${dt.keterangan}</td> 
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-danger hapus-syarat" data-id="${dt.id})" >Hapus</button>
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
        seleksi_id: seleksi_id,
        nama: baris.find("input[name='nama[]']").val(),
        is_wajib: baris.find("select[name='is_wajib[]']").val(),
        keterangan: baris.find("textarea[name='keterangan[]']").val(),
    };

    saveData(endpoint, 'POST', postData, function(response) {
        var data = response.data;
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        baris.attr("data-id", data.id);
        baris.attr("data-is_wajib", data.is_wajib);
        baris.find("td:eq(1)").text(data.nama);
        baris.find("td:eq(2)").text(data.is_wajib_label);
        baris.find("td:eq(3)").text(data.keterangan);
        baris.find("td:eq(4)").html(`<div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-danger hapusDataSyarat" data-id="${data.id}">Hapus</button>
                                </div>`);

        //---------------- sembunyikan inputan -------------------
        baris.find("input[name='nama[]'], textarea[name='keterangan[]'], select[name='is_wajib[]'], .simpan-baris, .batal-baris").hide();
    });    
});

var oldValue = "";
$('#data-list-syarat').on('dblclick', 'td', function() {
    const $td = $(this);
    const $tr = $td.closest('tr');
    const index = $td.index();
    const content = $td.text().trim();
    oldValue = content;

    $tr.find('td').each(function(i, cell) {
        var $cell = $(cell);
        if (i === index) {
            switch (index) {
                case 1:
                    $cell.html(`<input type="text" class="form-control" value="${content}">`);
                    break;
                case 2:
                    $cell.html(`<select class="form-control">
                                    <option value=''>-pilih-</option>
                                    <option value='1'>Wajib</option>
                                    <option value='0'>Tidak Wajib</option>
                                </select>`);
                    break;
                case 3:
                    $cell.html(`<textarea rows="3" class="form-control">${content}</textarea>`);
                    break;
                default:
                    break;
            }
            $cell.find('input select textarea').first().focus();
        }
    });
});

$('#data-list-syarat').on('focusout', 'input, textarea', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '') {
        $(this).closest('td').html(newValue);
        if (oldValue !== newValue)
            var url = endpoint + '/' + id;
            var postData = {
                nama: $(tr).find('td:nth-child(2)').text(),
                keterangan: $(tr).find('td:nth-child(4)').text(),
            };
            saveData(url, 'PUT', postData, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                loadDataSyarat();
            });
    }
});

$('#data-list-syarat').on('focusout', 'select', function() {
    const tr = $(this).closest('tr');
    const id = $(tr).attr('data-id');
    var newValue = $(this).val();
    if (id !== '') {
        var url = endpoint + '/' + id;
        var postData = {
            is_wajib: newValue,
        };
        saveData(url, 'PUT', postData, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            loadDataSyarat();
        });
    }
});

//listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
$(document).on('click', '.hapus-syarat', function() {
    const id = $(this).data('id');
    deleteData(endpoint, id, function() {
        toastr.success('operasi berhasil dilakukan!', 'berhasil');
        loadDataSyarat();
    });
});