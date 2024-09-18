const endpoint = base_url+'/api/seleksi';

$(document).ready(function() {
    // Menampilkan data awal
    dataLoad();
    dataJenis();

    $('#refresh').click(function(){
        dataLoad();
    });


    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
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
        if (action === 'config') {
            window.location.replace(base_url + '/pengaturan/' + id);
        } else if (action === 'wawancara') {
            window.location.replace(base_url + '/instrumen-wawancara/' + id);
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
});

//untuk show modal form
function showModalForm() {
    $('#form').trigger('reset');
    $('#form input[type="hidden"]').val('');

    var fModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
        keyboard: false
    });
    fModalForm.show();
}

//untuk tambah form
function tambah() {
    showModalForm();
}

//untuk form ganti
function formGanti(data) {
    showModalForm();

    $('#id').val(data.id);
    $('#nama').val(data.nama);
    $('#jenis_id').val(data.jenis_id);
    $('#keterangan').val(data.keterangan);
    $('#daftar_mulai').val(data.daftar_mulai);
    $('#daftar_selesai').val(data.daftar_selesai);
    $('#verifikasi_mulai').val(data.verifikasi_mulai);
    $('#is_publish').val(data.is_publish);
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
            const publish=(dt.is_publish==1)?'Terpublikasi':'Belum Terpublikasi';
            const bgdft = (dt.daftar_status) ? 'success' : 'danger';
            const bgver = (dt.verifikasi_status) ? 'success' : 'danger';
            const row = `<tr>
                        <td>${no++}</td>
                        <td>
                            <figure>
                                <blockquote class="blockquote">
                                    <p>${dt.nama}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer">${dt.user} <cite title="Source Title">(${dt.tahun})</cite></figcaption>
                            </figure>                            
                        </td>
                        <td><span class="badge text-bg-${bgdft}">${dt.daftar_mulai} sd ${dt.daftar_selesai}</span></td>
                        <td><span class="badge text-bg-${bgver}">${dt.verifikasi_mulai} sd ${dt.verifikasi_selesai}</span></td>
                        <td><div>${dt.jenis}</div>${dt.keterangan}</td>
                        <td>${publish}</td>
                        <td>${dt.pendaftar}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item btn-aksi" data-id="${dt.id}" data-action="edit" href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item" href="${base_url}/pengaturan/${dt.id}"><i class="bi bi-wrench-adjustable"></i> Pengaturan</a></li>
                                    <li><a class="dropdown-item" href="${base_url}/instrumen-wawancara/${dt.id}"><i class="bi bi-journal-text"></i> Instrumen Wawancara</a></li>
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