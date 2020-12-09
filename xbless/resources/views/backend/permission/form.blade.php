@extends('layouts.backend')
@section('pageTitle', "Manajemen Modul | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
<div class="container-fluid flex-grow-1 container-p-y">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{route('permission.index')}}">Modul</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="javascript:void(0)">{{isset($permission) ? 'Edit' : 'Tambah'}}</a>
    </li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Modul</div>
  </h4>

  @if(session('message'))
  <div class="alert alert-{{session('message')['status']}}">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ session('message')['desc'] }}
  </div>
  @endif

  <div class="nav-tabs-top">
    <div class="card">
      <div class="card-body pb-2">  
        <form class="form-horizontal" id="simpanDataModul" method="POSt" action="{{isset($permission)? route('permission.simpan',$enc_id) : route('permission.simpan')}}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Nama Modul <span>*</span></label>
              <input type="text" class="form-control mb-1" name="name" id="name" value="{{isset($permission)? $permission->name : ''}}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Slug <span>*</span></label>
              <input type="text" class="form-control" name="slug" id="slug" value="{{isset($permission)? $permission->slug : ''}}" required="">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Urutan <span>*</span></label>
              <input type="text" class="form-control" name="urutan" id="urutan" value="{{isset($permission)? $permission->nested : ''}}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Ikon (Ionicons) <span>*</span></label>
              <input type="text" class="form-control" name="icon" id="icon" value="{{isset($permission)? $permission->icon : ''}}">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Tampil di Menu Utama? </label>
              <select name="asmenu" class="custom-select" id="asmenu">
                @foreach($status as $key => $row)
                <option value="{{$key}}"{{ $selectedstatus == $key ? 'selected=""' : '' }}>{{ucfirst($row)}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <div class="text-right mt-3">
                <button type="submit" class="btn btn-simpan" id="simpan">Simpan</button>&nbsp;
                <a href="{{route('permission.index')}}"  class="btn btn-default">Kembali</a>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
   $('#simpanDataModul').validate({
     focusInvalid: false,
    rules: {
      name:{
        required: true
      },
      slug:{
        required: true
      },
      urutan:{
        required: true
      },
      icon:{
        required: true
      }
    },
    messages: {
      name: {
        required: "Nama Modul tidak boleh kosong"
      },
      slug: {
        required: "Nama slug / route tidak boleh kosong"
      },
      urutan: {
        required: "Urutan tidak boleh kosong"
      },
      icon: {
        required: "Ikon tidak boleh kosong"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
      console.log(element.closest('.form-group').append(error));
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
</script>
@endpush
   