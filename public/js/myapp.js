function myFormatDate(dateString){
    var date = new Date(dateString);
    // Periksa apakah objek Date valid
    if (isNaN(date.getTime())) {
        return 'Invalid Date';
    }    
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function myLabel(tmpVar){
    var tmp='';
    if (tmpVar!==null) {
        tmp=tmpVar;
    }
    return tmp;
}

function ajaxRequest(url, method, data, successCallback, errorCallback) {
    // var requestData = (data!==null)?JSON.stringify(data):null;   
    $.ajax({
        url: url,
        type: method,
        data: data,
        success: function(response) {
            successCallback(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                forceLogout('Akses ditolak! login kembali');
            } else {
                if(jqXHR.status === 422){
                    const errors = jqXHR.responseJSON.errors;
                    $.each(errors, function(index, dt) {
                        alert(dt);
                    });
                }else{
                    alert(jqXHR.responseJSON.message);
                }
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        }
    });
}

function renderSelect(elm,opt,id=null){
    const select = $(elm);
    select.empty();
    let no = 1;
    $.each(opt, function(index, dt) {
        var selected = '';
        if(dt.id==id)
            selected = 'selected';
        var row = `<option value='${dt.id}' ${selected}>${dt.nama}</option>`;
        select.append(row);
    });
}

function tokenCek(){
    var akses_grup = localStorage.getItem('akses_grup');
    ajaxRequest(base_url + '/api/token-cek/' + akses_grup, 'GET', null,
        function(response) {
            console.log(response);
        },
        function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    );
}