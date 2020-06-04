
@extends('layouts.client-layout')

@section('page', 'Aw Snap')

@section('content')
<div class="container-fluid page" style="padding:10% 20%;">
  <div class="card text-center">
    <div class="card-body">
      <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="logo gambung" height="200" width="450">
      <h3 class="font-weight-bold my-2">Aw Snap!</h3>
      <p>Anda belum melakukan verifikasi email!<br>Silahkan buka email anda, karena kami sudah mengirimkan email verifikasi</p>
      <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-success align-baseline">Click Disini Untuk Mengirim Verifikasi Ulang</button>
      </form>
    </div>
  </div>
</div>
@endsection
