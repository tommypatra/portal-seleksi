// alert(seleksi_id);
var endpoint=base_url+'/api/institusi';

//load data syarat
loadDataInstitusi();


function loadDataInstitusi(page = 1, search = '') {
    var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
    fetchData(url, function(response) {
        renderDataInstitusi(response);
    });
}

//untuk render respon syarat ke tabel
function renderDataInstitusi(response){
    const dataList = $('#data-list-institusi');
    const pagination = $('#pagination-institusi');
    let no = (response.current_page - 1) * response.per_page + 1;
    dataList.empty();
    if(response.data.length>0){
        $.each(response.data, function(index, dt) {
            dataList.append(`<tr data-id="${dt.id}"> 
                <td>${no++}</td> 
                <td>${dt.nama} ${dt.institusi.nama}</td> 
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

