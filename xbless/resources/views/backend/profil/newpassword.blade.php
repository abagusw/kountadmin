@extends('layouts.backend')
@section('pageTitle', "Reset Password | $perusahaan->nama")
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
      <li class="breadcrumb-item active">Ubah Password</li>
    </ol>
    <h4 class="font-weight-bold py-3 mb-4">Ubah Password</h4>
    @if(session('message'))
    <div class="alert alert-{{session('message')['status']}}">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {{ session('message')['desc'] }}
    </div>
    @endif
    <div class="nav-tabs-top">
      
      <div class="card">
        <div class="card-body pb-2">
          <form class="form-horizontal" id="submitData">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row no-gutters row-bordered row-border-light">
              
              <div class="col-md-9 pt-0">
                <div class="tab-content">
                  <div class="card-body pb-2">
                    <div class="form-group">
                      <label class="form-label">Password Lama</label>
                      <input type="password" class="form-control" id="password_old" name="password_old">
                    </div>
                    <div class="form-group">
                      <label class="form-label">Password Baru</label>
                      <input type="password" class="form-control" id="password_new" name="password_new">
                    </div>
                    <div class="form-group">
                      <label class="form-label">Konfirmasi Password Baru</label>
                      <input type="password" class="form-control" id="confirm_password_new" name="confirm_password_new">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="text-right mt-3">
                  <button type="submit" class="btn btn-simpan" id="simpan">Simpan</button>&nbsp;
                  <a href="{{route('manage.beranda')}}"  class="btn btn-default">Kembali</a>
                </div>
              </div>
            </div>
          </form>
          <div class="modal modal-fill-in fade" id="modals-fill-in">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="card-body text-center" id="loadingbro">
                    <div class="demo-inline-spacing">
                      <div class="spinner-grow" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-success" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-info" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-light" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  @endsection
@push('scripts')
<script type="text/javascript">
 
  $('#submitData').validate({
    rules: {
      password_old:{
        required: true
      },
      password_new: {
        required: true,
        minlength: 5
      },
      confirm_password_new : {
          required: true,
          minlength : 5,
          equalTo : "#password_new"
      }
    },
    messages: {
      password_old: {
        required: "Password Lama tidak boleh kosong"
      },
      password_new: {
        required: "Password Baru wajib diisi.",
        minlength: "Password minimal 5 karakter"
      },
      confirm_password_new: {
        required: "Konfirmasi Password Baru wajib diisi.",
        minlength: "Konfirmasi Password Baru minimal 5 karakter",
        equalTo:"Konfirmasi Password Baru tidak sama dengan Password Baru."
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      // console.log(error.);
      element.closest('.form-group').append(error);
      console.log(element.closest('.form-group').append(error));
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    },
    submitHandler: function(form) {
      $('#modals-fill-in').modal('show');
      SimpanData();
    }
  });
   function SimpanData(){    
      $('#simpan').addClass("disabled");
         var password_old   =$('#password_old').val();
         var password_new   =$('#password_new').val();
         var dataFile = new FormData()
         dataFile.append('password_new', password_new);
         dataFile.append('password_old', password_old);
        $.ajax({
          type: 'POST',
          url : "{{route('profil.simpanpassword')}}",
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data:dataFile,
          processData: false,
          contentType: false,
          dataType: "json",
          beforeSend: function () {
              $('#modals-fill-in').modal('show');
          },
          success: function(data){
            if (data.success) {
               Swal.fire('Yes',data.message,'info');
            } else {
               Swal.fire('Ups',data.message,'info'); 
            }
            
          },
          complete: function () { 
            $('#modals-fill-in').modal('hide');
            $('#simpan').removeClass("disabled");
            window.location.reload();
          },
          error: function(data){
               $('#simpan').removeClass("disabled");
               $('#modals-fill-in').modal('hide');
              console.log(data);
          }
        });
  }
</script>
@endpush
   