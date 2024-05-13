<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials_head')
  @yield('head')
  <script>
    const base_url = "{{ url('/') }}";
  </script>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/dashboard') }}">Dashboard Website</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      </button>
      <ul class="navbar-nav menu-akun" style="display:none;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Halaman Depan Web</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item dropdown menu-peserta" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Peserta
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Jadwal</a></li>
            <li><a class="dropdown-item" href="#">Berkas</a></li>
            <li><a class="dropdown-item" href="#">Pengumuman</a></li>
            <li><a class="dropdown-item" href="#">Penetapan</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown menu-verifikator" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarEditor" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Verifikator
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarEditor">
            <li><a class="dropdown-item" href="#">Data</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown menu-interview" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarEditor" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Interview
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarEditor">
            <li><a class="dropdown-item" href="#">Data</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown menu-admin" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Admin
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarAdmin">
            <li><a class="dropdown-item" href="{{ route('seleksi') }}">Jadwal Seleksi</a></li>
            <li><a class="dropdown-item" href="#">Verifikator</a></li>
            <li><a class="dropdown-item" href="#">Interview</a></li>
            <li><a class="dropdown-item" href="#">Penetapan</a></li>
            <hr class="dropdown-divider">
            <li><a class="dropdown-item" href="#">Jenis</a></li>
            <li><a class="dropdown-item" href="#">Grup</a></li>
            <li><a class="dropdown-item" href="{{ route('institusi') }}">Institusi</a></li>
            <li><a class="dropdown-item" href="#">Akun</a></li>
          </ul>
        </li>
        <li class="nav-item menu-ganti-akses" style="display:none;">
          <a class="nav-link" href="javascript:;" onclick="gantiAkses()">Ganti Akses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:;" onclick="logoutWeb()">Keluar</a>
        </li>
      </ul>
    </div>
    </div>
  </nav>

  <!-- Main content -->
  <div class="main-content">
    <div class="container-fluid">
      @yield('container')
    </div>
  </div>

  <div class="modal fade" id="aksesModal" tabindex="-1" aria-labelledby="aksesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <ul class="nav-item dropdown daftar-akses">
            </li>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  @include('partials_footer')
  <script src="{{ asset('js/myapp.js') }}"></script>
  <script>
    var akses_grup = localStorage.getItem('akses_grup');
    var daftar_akses = JSON.parse(localStorage.getItem('daftar_akses'));
    // console.log(akses_grup);

    function setLoading(text = 'loading...') {
      return `<div class="d-flex align-items-center">
                <strong role="status">${text}</strong>
                <div class="spinner-border ms-auto" aria-hidden="true"></div>
              </div>`;
    }

    if (akses_grup) {
      $('.menu-akun').show();

      if (akses_grup == 1) {
        $('.menu-admin').show();
      } else if (akses_grup == 2) {
        $('.menu-interview').show();
      } else if (akses_grup == 3) {
        $('.menu-verifikator').show();
      } else if (akses_grup == 4) {
        $('.menu-peserta').show();
      }


      // console.log(daftar_akses);
      if (daftar_akses.length > 1) {
        $('.menu-ganti-akses').show();
        $.each(daftar_akses, function(index, item) {
          var listItem = `<li><a href='#' onclick="setAkses(${item.grup_id})">${item.nama}</a></li>`;
          $('.daftar-akses').append(listItem);
        });
      }
    }

    function setAkses(id) {
      localStorage.setItem('akses_grup', id);
      toastr.success('set akses berhasil, akan diarahkan ke halaman dashboard!', 'berhasil', {
        timeOut: 1000
      });
      var goUrl = `{{ url('/dashboard') }}`;
      window.location.replace(goUrl);
    }

    function gantiAkses() {
      var myModalAkses = new bootstrap.Modal(document.getElementById('aksesModal'), {
        keyboard: false
      });
      myModalAkses.show();
    }
  </script>
  <!-- <script src="{{ asset('js/loading.js') }}"></script> -->
  @yield('script')

</body>

</html>