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

function ajaxRequest(url, method, data=null, showModal=false, successCallback, errorCallback) {
    var hasFile = false;

    if (data instanceof FormData) {
        data.forEach(function(value, key) {
            if (value instanceof File) {
                hasFile = true;
            }
        });
    }

    var modalElement = document.getElementById('loadingModal');
    var modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });
    
    if(showModal){
        modal.show();
    }

    var ajaxOptions = {
        url: url,
        type: method,
        success: function(response) {
            if (successCallback) {
                successCallback(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                forceLogout('Mohon login kembali');
            } else {
                if (jqXHR.status === 422) {
                    const errors = jqXHR.responseJSON.errors;
                    $.each(errors, function(index, dt) {
                        // alert(dt);
                        toastr.error(dt, 'terjadi kesalahan');
                    });
                } else {
                    // alert(jqXHR.responseJSON.message);
                    toastr.error(jqXHR.responseJSON.message, 'terjadi kesalahan');
                }
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
            if (errorCallback) {
                errorCallback(jqXHR, textStatus, errorThrown);
            }
        }
    };

    if (data !== null) {
        ajaxOptions.data = data;
    }

    if (hasFile) {
        ajaxOptions.contentType = false;
        ajaxOptions.processData = false;
    }
    

    $.ajax(ajaxOptions);

    modalElement.addEventListener('shown.bs.modal', function () {
        modal.hide();
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
    ajaxRequest(base_url + '/api/token-cek/' + akses_grup, 'GET', null, false,
        function(response) {
            console.log(response);
        },
        function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    );
}

function timeAgo(datetime) {
    const now = new Date();
    const time = new Date(datetime);
    const secondsPast = (now.getTime() - time.getTime()) / 1000;
    
    if (secondsPast < 60) {
        return `${Math.floor(secondsPast)} detik lalu`;
    }
    if (secondsPast < 3600) {
        return `${Math.floor(secondsPast / 60)} menit lalu`;
    }
    if (secondsPast < 86400) {
        return `${Math.floor(secondsPast / 3600)} jam lalu`;
    }
    if (secondsPast < 2592000) { // 30 hari
        return `${Math.floor(secondsPast / 86400)} hari lalu`;
    }
    if (secondsPast < 31536000) { // 12 bulan
        return `${Math.floor(secondsPast / 2592000)} bulan lalu`;
    }
    return `${Math.floor(secondsPast / 31536000)} tahun lalu`;
}