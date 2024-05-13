<footer class="footer">
    <div class="container">
        &copy; 2024 Admin Dashboard
    </div>
</footer>


<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('images/loading.gif') }}" height="150px" alt="Loading..." />
                <p>Sabar... lagi proses <span class='proses-berjalan'></span></p>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('plugins/toastr/build/toastr.min.js') }}"></script>

<script>
    toastr.options.closeButton = true;

    function logoutWeb() {
        forceLogout();
        toastr.success('logout berhasil, akan diarahkan ke halaman login!', 'logout berhasil', {
            timeOut: 1000
        });
    }

    function forceLogout(pesan) {
        $.ajax({
            url: base_url + '/api/logout',
            type: 'post',
            success: function(response) {
                console.log(respose);
            },
        });

        localStorage.removeItem('access_token');
        localStorage.removeItem('daftar_akses');
        localStorage.removeItem('akses_grup');
        if (pesan)
            alert(pesan);
        window.location.replace(base_url + '/login');
    }
</script>