<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Portal Seleksi">
<meta name="author" content="portal seleksi">
<meta name="keywords" content="website resmi untuk seleksi">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="{{ asset('plugins/toastr/build/toastr.min.css') }}" rel="stylesheet" />
<style>
  /* Styles for footer */
  .footer {
    width: 100%;
    background-color: #f8f9fa;
    /* Ubah warna latar belakang */
    color: #6c757d;
    /* Ubah warna teks */
    text-align: center;
    padding: 20px 0;
    /* Tambahkan padding atas dan bawah */
    position: fixed;
    bottom: 0;
    left: 0;
  }

  /* Styles for main content */
  .main-content {
    min-height: calc(100vh - 70px);
    /* Menentukan tinggi konten agar footer tetap di bawah browser */
    padding-bottom: 70px;
    /* Menambahkan ruang bawah agar konten tidak tertutupi oleh footer */
  }

  /* .form-control {
    min-width: 150px; */
  /* Atur lebar minimum di sini */
  /* width: auto; */
  /* Biarkan lebar menyesuaikan isi */
  /* } */

  .w-100 {
    width: 100%;
  }

  .btn-vsm {
    --bs-btn-padding-y: .20rem;
    --bs-btn-padding-x: .5rem;
    --bs-btn-font-size: .50rem;
  }

  .font-12 {
    font-size: 12px;
  }

  .daftar-akses li a {
    text-decoration: none;
    color: black;
  }
</style>