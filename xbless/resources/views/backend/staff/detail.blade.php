@extends('layouts.backend')
@section('pageTitle', "Detail Staff | $perusahaan->nama")
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/users.css')}}">
@endpush
@section('main_container')
<div class="layout-content">
  <div class="container-fluid flex-grow-1 container-p-y">

    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="{{route('manage.beranda')}}">Beranda</a>
      </li>
      <li class="breadcrumb-item">
        <a href="{{route('staff.index')}}">Staff</a>
      </li>
      <li class="breadcrumb-item active">Detail </li>
    </ol>

    <h4 class="font-weight-bold py-3 mb-4">
     <span class="text-muted">Detail Staff</span>
    </h4>

    <div class="nav-tabs-top">
     
      <div class="card">
        <div class="card-body pb-2">
          <form class="form-horizontal" id="submitData">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="media align-items-center">
            <img src="{{isset($staff)? $profile : 'https://ui-avatars.com/api/?name=Dwi-Prasetyo&background=ed4626&color=ffffff&rounded=true'}}" alt="" id="ShowAvatar" class="d-block ui-w-80">
              <div class="media-body ml-3">
                <label class="form-label d-block mb-2">{{ $staff->name}}</label>
                <label class="btn btn-tambah btn-sm">
                 {{ $staff->email}}
                </label>
              </div>
            </div>

            <br/>
            <hr/>
            <br>

            <table class="table user-view-table m-0">  
              <tbody>
                <tr>
                  <td>Tgl Registrasi</td>
                  <td>: {{$tgl_registrasi}}</td>
                </tr>
                <tr>
                  <td>Login terakhir:</td>
                  <td>: {{$tgl_last_login}}</td>
                </tr>
                <tr>
                  <td>No HP</td>
                  <td>: {{$staff->no_hp}}</td>
                </tr>
                <tr>
                  <td>Jenis Kelamin</td>
                  <td>: {{$staff->jk=='L'?'Laki-laki':'Perempuan'}}</td>
                </tr>
                <tr>
                  <td>Status:</td>
                  <td>: {!!$status!!}</td>
                </tr>
              </tbody>
            </table>   

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')

@endpush
   