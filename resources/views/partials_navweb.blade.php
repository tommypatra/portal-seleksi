<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ url('images/logo.png') }}" id="logo-web" alt="Logo" height="30">
            <span id="nama-web">Website</span>
        </a>      
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul id="dynamicMenu" class="navbar-nav me-auto"></ul>
            <!-- Kotak Pencarian -->
            <div class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Cari..." id="search-input" aria-label="Search">
                <button class="btn btn-outline-success cari-data" type="button">Cari</button>
            </div>
        </div>
    </div>
</nav>