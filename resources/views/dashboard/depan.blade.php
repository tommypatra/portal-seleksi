@extends('template_dashboard')

@section('head')
<title>Dashboard Website</title>
@endsection

@section('container')

<h1>Selamat Datang</h1>
<p>Selamat datang dihalaman dashboard website</p>

@endsection

@section('script')
<script src="{{ asset('js/token.js') }}"></script>
<script>
    tokenCek();
</script>
@endsection