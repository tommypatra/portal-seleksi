var authToken=localStorage.getItem('access_token');
$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + authToken,
        // 'Content-Type': 'application/json'
    }
});
