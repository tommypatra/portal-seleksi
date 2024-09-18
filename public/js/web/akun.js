const endpoint = base_url+'/api/akun';
const hakakses = [
    {id:1,grup:'Admin'},
    {id:2,grup:'Interviewer'},
    {id:3,grup:'Verifikator'},
    // {id:4,grup:'Peserta'},
];
var user_id;


$(document).ready(function() {
    // Menampilkan data awal
    dataLoad();
    dataJenis();

    $('#refresh').click(function(){
        dataLoad();
    });

    function dataLoad(page = 1, search = '') {
        var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            renderData(response);
        },true);
    }

    //untuk mengambil api data jenis
    function dataJenis() {
        fetchData(base_url+'/api/jenis', function(response) {
            renderJenis(response);
        });
    }

    //listener untuk btn-aksi baik itu ganti atau hapus langsung ke endpoint
    $(document).on('click', '.btn-aksi', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        if (action === 'hakakses') {
            user_id=id;
            showModalForm('modalAkses','formAkses');
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

    //validasi dan save, jika id ada maka PUT/edit jika tidak ada maka POST/simpan baru
    $("#form").validate({
        submitHandler: function(form) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? endpoint : endpoint + '/' + id;
            saveData(url, type, $(form).serialize(), function(response) {
                //jika berhasil maka
                $('#form').trigger('reset');
                $('#form input[type="hidden"]').val('');
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                dataLoad();
            });
        }
    });

    // $('#formAkses').submit(function(e){ 
    //     e.preventDefault();
    //     var formData = $(this).serialize();
    //     var url = base_url + '/api/akun-hakakses/'+user_id;
    //     // console.log(url);
    //     saveData(url, 'POST', formData, function(response) {
    //         //jika berhasil maka
    //         $('#formAkses').trigger('reset');
    //         $('#formAkses input[type="hidden"]').val('');
    //         toastr.success('operasi berhasil dilakukan!', 'berhasil');
    //         dataLoad();
    //     });
    // })


    $.validator.addMethod("checkCheckboxes", function(value, element) {
        return $('input[name="cekakses[]"]:checked').length > 0;
    }, "Pilih setidaknya satu akses.");

    // Menerapkan validasi pada form
    $("#formAkses").validate({
        rules: {
            "cekakses[]": {
                checkCheckboxes: true
            }
        },
        messages: {
            "cekakses[]": {
                checkCheckboxes: "Pilih setidaknya satu akses."
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "cekakses[]") {
                error.insertAfter(".form-check:last");
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            var formData = $(form).serialize();
            var url = base_url + '/api/akun-hakakses/'+user_id;
            saveData(url, 'POST', formData, function(response) {
                //jika berhasil maka
                $('#formAkses').trigger('reset');
                $('#formAkses input[type="hidden"]').val('');
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
    $(document).on('click', '.page-link', function() {
        var page = $(this).data('page');
        var search = $('#search-input').val();
        dataLoad(page, search);
    });


    $(document).on("click",".hapus-akses",function(){
        const id = $(this).data('id');
        deleteData(base_url+'/api/akun-akses', id, function() {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            dataLoad();
        });
    
    })    
});

//untuk show modal form
function showModalForm(modalId,formId) {
    $('#'+formId).trigger('reset');
    $('#'+formId+' input[type="hidden"]').val('');

    var fModal = new bootstrap.Modal(document.getElementById(modalId), {
        keyboard: false
    });
    fModal.show();
}
        
//untuk tambah form
function tambah() {
    showModalForm('modalForm','form');
}

//untuk form ganti
function formGanti(data) {
    showModalForm('modalForm','form');

    $('#id').val(data.id);
    $('#nama').val(data.nama);
    $('#jenis_id').val(data.jenis_id);
    $('#keterangan').val(data.keterangan);
    $('#daftar_mulai').val(data.daftar_mulai);
    $('#daftar_selesai').val(data.daftar_selesai);
    $('#verifikasi_mulai').val(data.verifikasi_mulai);
    $('#verifikasi_selesai').val(data.verifikasi_selesai);
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
            const row = `<tr>
                        <td>${no++}</td>
                        <td>
                            <figure>
                                <blockquote class="blockquote">
                                    <p>${dt.name}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer">${dt.email}</figcaption>
                            </figure>                            
                        </td>
                        <td>${grupUser(dt)}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item btn-aksi" data-id="${dt.id}" data-action="hakakses" href="javascript:;"><i class="bi bi-person-fill-gear"></i> Hakakses</a></li>
                                    <li><a class="dropdown-item btn-aksi" data-id="${dt.id}" data-action="edit" href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item btn-aksi" data-id="${dt.id}" data-action="delete" href="javascript:;"><i class="bi bi-trash3"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>`;
            dataList.append(row);
        });
        renderPagination(response, pagination);
    }
}

function grupUser(response) {
    // console.log(response);
    var grup=`<ul>`;
    $.each(response.role_user, function(index, dt) {
        grup+=`<li>${dt.role.nama} <a href="javascript:;" data-grup="${dt.role.id}" data-id="${dt.id}" class="hapus-akses"><i class="bi bi-trash"></i></a></li>`;
    });
    $.each(response.peserta, function(index, dt) {
        grup+=`<li>Peserta ${dt.noid} <a href="javascript:;" data-grup="0" data-id="${dt.id}" class="hapus-akses-peserta"><i class="bi bi-trash"></i></a></li>`;
    });
    grup+=`</ul>`;
    return grup;
}

//untuk render jenis pada select
function renderJenis(response) {
    const option = $('#jenis_id');
    option.empty();
    let no = 1;
    for (const item of response.data) {
        const row = `<option value='${item.id}'>${item.nama}</option>`;
        option.append(row);
    }
}

