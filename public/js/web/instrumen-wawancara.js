// alert(seleksi_id);
var endpoint=base_url+'/api/instrumen-wawancara';
var opt_kategori_id=[];

function loadKategori() {
    // console.log(vLimit);
    var url = base_url+'/api/kategori';
    fetchData(url, function(response) {
        if(response.data.length>0)
            $.each(response.data, function(index, dt) {
                opt_kategori_id.push({
                    'id': dt.id,
                    'nama': dt.nama
                });            
            });
        // console.log(response);
    },true);
}

$(document).ready(function() {
    //load data institusi
    loadKategori();
    dataLoad();
    $('#refresh').click(function(){
        dataLoad();
    });

    $('#tambah').click(function(){
        $('#form').trigger('reset');
        $('#form input[type="hidden"]').val('');

        $('#bank_soal_id').empty();
        $('#bank_soal_id').trigger('change');

        showModalForm();
    });
    

    $(document).on('click', '.ganti-soal', function() {
        const id=$(this).attr('data-id');
        showDataById(endpoint,id, function(response) {
            // console.log(response);
            $("#id").val(response.data.id);
            $("#bobot").val(response.data.bobot);
            //nilai defaul select2 soal
            $('#bank_soal_id').empty();
            $('#bank_soal_id').append($('<option>', {
                value: response.data.bank_soal_id,
                text: response.data.bank_soal,
            }));
            $('#bank_soal_id').val(response.data.bank_soal_id).trigger('change');
            
        },true);

        showModalForm();
    });


    //untuk show modal form
    function showModalForm() {

        var fModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
            keyboard: false
        });
        fModalForm.show();
    }

    $('#bank_soal_id').select2({
        theme: "bootstrap-5",
    });
    
    $('#bank_soal_id').select2({
        width: '100%',
        dropdownParent: $("#modalForm"),
        ajax: {
            url: base_url+'/api/bank-soal',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, 
                    page: 1, 
                    limit: 20, 
                    seleksi_id:seleksi_id
                };
            },
            processResults: function (respon) {
                return {
                    results: respon.data.map(function(item) {
                        return {
                            id: item.id, 
                            text: item.soal
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 4
    });    

    function dataLoad(page = 1, search = '') {
        // console.log(vLimit);
        var url = endpoint + '?seleksi_id=' + seleksi_id + '&page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            renderData(response);
        },true);
    }


    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $('#form').validate({
        submitHandler: function(form) {
            var bank_soal_ids = $('#bank_soal_id').val();
            var bobot = $('#bobot').val();
            var id = $('#id').val();
            var url= (!id)?endpoint:endpoint+'/'+id;
            var type= (!id)?'post':'put';
            // alert(url);
            $.each(bank_soal_ids, function(key, value) {
                var data = {
                    seleksi_id:seleksi_id,
                    bobot:bobot,
                    bank_soal_id:value,
                };
                saveData(url, type, data, function(response) {
                    //jika berhasil maka
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
                });
                if(type=='put')
                    return false;
            });
            dataLoad();
            // interviewerTersedia();
        }
    });    
    
    //untuk render respon institusi ke tabel
    function renderData(response){
        const dataList = $('#data-list');
        const pagination = $('#pagination');
        let no = (response.current_page - 1) * response.per_page + 1;
        dataList.empty();
        if(response.data.length>0){
            $.each(response.data, function(index, dt) {
                var vkategori_id='kategori_id'+dt.id;
                
                dataList.append(`<tr data-id="${dt.id}" data-bank_soal_id="${dt.bank_soal_id}"> 
                    <td>${no++}</td> 
                    <td>${dt.kategori_nama}</td> 
                    <td>${dt.bank_soal}</td> 
                    <td><input type="number" class="form-control bobot w-25" name="bobot" value="${dt.bobot}"></td> 
                    <td>${myLabel(dt.keterangan)}</td> 
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-primary ganti-soal" data-id="${dt.id}" >Ganti</button>
                            <button type="button" class="btn btn-danger hapus-soal" data-id="${dt.id}" >Hapus</button>
                        </div>                                        
                    </td>
                </tr>`);
                renderSelect('#'+vkategori_id,opt_kategori_id,dt.kategori_id);
            });
            renderPagination(response, pagination);
        }
        // console.log(opt_kategori_id);
    }
    
    // // function simpanBaris(button) {
    // $(document).on('click', '.simpan-baris-syarat', function() {
    //     const baris = $(this).closest('tr');
    //     var postData = {
    //         seleksi_id: seleksi_id,
    //         nama: baris.find("input[name='nama[]']").val(),
    //         jenis: baris.find("select[name='jenis[]']").val(),
    //         is_wajib: baris.find("select[name='is_wajib[]']").val(),
    //         keterangan: baris.find("textarea[name='keterangan[]']").val(),
    //     };
    
    //     saveData(endpoint, 'POST', postData, function(response) {
    //         var dt = response.data;
    //         baris.find(".simpan-baris-syarat, .batal-baris-syarat").hide();
    //         baris.attr("data-id", dt.id);
    //         baris.find("td:eq(1)").html(dt.nama);
    //         baris.find("td:eq(2)").html(dt.jenis_label);
    //         baris.find("td:eq(3)").html(dt.is_wajib_label);
    //         baris.find("td:eq(4)").html(dt.keterangan);
    //         baris.find("td:eq(5)").html(`<div class="btn-group btn-group-sm" role="group">
    //                                         <button type="button" class="btn btn-danger hapus-soal" data-id="${dt.id})" >Hapus</button>
    //                                     </div>`);
    //         toastr.success('operasi berhasil dilakukan!', 'berhasil');
    //     });    
    // });
    
    
    //listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
    $(document).on('click', '.hapus-soal', function() {
        const id = $(this).data('id');
        deleteData(endpoint, id, function() {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            dataLoad();
        });
    });    

    // Handle page change
    $(document).on('click', '.page-link', function() {
        var page = $(this).data('page');
        var search = $('#search-input').val();
        dataLoad(page, search);
    });

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

    // Handle item-paging limit change
    $('.item-paging').on('click', function() {
        vLimit = $(this).data('nilai');
        dataLoad();
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
                seleksi_id: seleksi_id,
                bobot: $(tr).find('.bobot').val(),
                bank_soal_id: $(tr).attr('data-bank_soal_id'),
                kategori_id: $(tr).find('.kategori_id').val(),
                // is_wajib: $(tr).find('.is_wajib').val(),
                // keterangan: $(tr).find('.keterangan').val(),
            };
            // console.log(postData);
            saveData(url, 'PUT', postData, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                // loadDataSyarat();
            });
        }
    });

})