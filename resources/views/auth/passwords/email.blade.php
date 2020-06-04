@extends('layouts.client-layout')

@section('page', 'Reset Password')

@section('content')
<div class="container-fluid page" style="padding:10% 20%;">
  <div class="card text-center">
    <div class="card-body">
      <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="logo gambung" height="200" width="450">
      <h3 class="font-weight-bold my-2">Reset Password</h3>
      <p>Masukan email anda dibawah</p>
      <form class="d-inline" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group row justify-content-center">
          <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
        <button type="submit" class="btn btn-success align-baseline">Send Password Reset Link</button>
      </form>
    </div>
  </div>
</div>
@endsection
