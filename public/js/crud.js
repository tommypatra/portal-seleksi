
    // Fungsi fetchData
    function fetchData(endpoint, callback, showModal=false) {
        ajaxRequest(endpoint, 'GET', null, showModal,
            function(response) {
                callback(response);
            }
        );
    }

    // Fungsi showDataById
    function showDataById(endpoint, id, callback, showModal=false) {
        ajaxRequest(endpoint + '/' + id, 'GET', null, showModal,
            function(response) {
                callback(response);
            }
        );
    }

    // Fungsi saveData baik post maupun put
    function saveData(endpoint, type, data, callback, showModal=false) {
        ajaxRequest(endpoint, type, data, showModal,
            function(response) {
                callback(response);
            }
        );
    }


    // Fungsi deleteData
    function deleteData(endpoint, id, callback, showModal=false) {
        if(confirm('apakah anda yakin?'))
            ajaxRequest(endpoint + '/' + id, 'DELETE', null, showModal,
                function(response) {
                    callback(response);
                }
            );
    }