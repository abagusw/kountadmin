@extends('layouts.backend')
@section('pageTitle', "Manajemen Staff | $perusahaan->nama")
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
      <li class="breadcrumb-item active">{{isset($staff) ? 'Edit' : 'Tambah'}} </li>
    </ol>

    <h4 class="font-weight-bold py-3 mb-4">
    {{isset($staff) ? 'Edit' : 'Tambah'}}  <span class="text-muted">Staff</span>
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
          <form class="form-horizontal" id="submitData">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="enc_id" id="enc_id" value="{{isset($staff)? $enc_id : ''}}">
            
            <div class="media align-items-center">
            <img src="{{isset($staff)? $profile : 'https://ui-avatars.com/api/?name=Dwi-Prasetyo&background=ed4626&color=ffffff&rounded=true'}}" alt="" id="ShowAvatar" class="d-block ui-w-80">
              <div class="media-body ml-3">
                <label class="form-label d-block mb-2">Photo Profil</label>
                <label class="btn btn-tambah btn-sm">
                  Ubah
                  <input type="file" class="user-edit-fileinput" id="avatar" name="avatar">
                </label>
                  <input type="hidden" name="image" value="" id="image">
              </div>
            </div>

            <br/>
            <hr class="border-light m-0">
            <br/>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">Nama Lengkap <span>*</span></label>
                <input type="text" class="form-control mb-1" name="name" id="name" value="{{isset($staff)? $staff->name : ''}}">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Jenis Kelamin <span>*</span></label>
              <div class="custom-controls-stacked">
                 <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jk" id="jk" value="L" {{isset($staff)? $staff->jk=='L' ? 'checked':'' : 'checked'}}>
                    <span class="form-check-label">
                      Laki-Laki
                    </span>
                  </label>

                  <label class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jk" id="jk" value="P" {{isset($staff)? $staff->jk=='P' ? 'checked':'' : ''}}>
                    <span class="form-check-label">
                      Perempuan
                    </span>
                  </label>
              </div>
            </div>
           
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">Email <span>*</span></label>
                <input type="email" class="form-control" name="email" id="email" value="{{isset($staff)? $staff->email : ''}}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12" style="{{isset($staff)? 'display:none':''}}">
                <label class="form-label">Password <span>*</span></label>
                 <input type="password" class="form-control" id="password" name="password">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">No HP <span>*</span></label>
                <input type="text" class="form-control" name="no_hp" id="no_hp" value="{{isset($staff)? $staff->no_hp : ''}}">
              </div>
            </div>

            <div class="form-row">
            <div class="form-group col-md-12">
              <label class="form-label">Status </label>
              <select name="status" class="custom-select" id="status">
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
                <a href="{{route('staff.index')}}"  class="btn btn-default">Kembali</a>
              </div>
            </div>
          </div>

          </form>

          <div class="modal modal-fill-in" id="modals-fill-in">
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

           <div id="modalUploadProfil" class="modal" role="dialog">
            <div class="modal-dialog modal-sm">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title">Upload</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12 text-center">
                      <div id="image_demo" style="width:250px; margin-top:30px"></div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-simpan crop_image">Proses</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
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
      name:{
        required: true
      },
      email:{
        required: true,
        email:true
      },
      password: {
        required: true,
        minlength: 5
      },
      no_hp: {
        required: true,
        number: true,
        minlength:10
      }
    },
    messages: {
      name: {
        required: "Nama Lengkap tidak boleh kosong"
      },
      email: {
        required: "Email tidak boleh kosong",
        email :"Hanya menerima email contoh demo@gmail.com",
      },
      password: {
        required: "Password wajib diisi.",
        minlength: "Password minimal 5 karakter"
      },
      no_hp: {
        required: "No HP tidak boleh kosong",
        number :"Hanya menerima inputan angka",
        minlength:"Minimal 10 angka"
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
         var enc_id         =$('#enc_id').val();
         var name           =$('#name').val();
         var image          =$('#image').val();
         var jk             =$('.form-check-input:checked').val();
       
         var email          =$('#email').val();
         var password       =$('#password').val();
         var no_hp          =$('#no_hp').val();
         var status         =$('#status').val();
      
         var dataFile = new FormData()
       
         dataFile.append('image', image);
         dataFile.append('enc_id', enc_id);
         dataFile.append('name', name);
      
         dataFile.append('jk', jk);
         dataFile.append('email', email);
         dataFile.append('password', password);
         dataFile.append('no_hp', no_hp);
         dataFile.append('status', status);
      
        $.ajax({
          type: 'POST',
          url : "{{route('staff.simpan')}}",
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
               window.location.reload();
            } else {
               Swal.fire('Ups',data.message,'info'); 
            }
          },
          complete: function () { 
            $('#modals-fill-in').modal('hide');
            $('#simpan').removeClass("disabled");
          },
          error: function(data){
            $('#simpan').removeClass("disabled");
            $('#modals-fill-in').modal('hide');
            console.log(data);
          }
        });
    }
   function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#ShowAvatar').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
   }
   $(document).ready(function(){
   $('#tgl_lahir').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
    format : 'DD-MM-YYYY',
    clearButton: true
  });
   $image_crop = $('#image_demo').croppie({
      enableExif: true,
      mouseWheelZoom: true,
      viewport: {
        width:200,
        height:200,
        type:'circle'
      },
      boundary:{
        width:300,
        height:300
      }
    });
    $('.crop_image').click(function(event){
      $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function(response){
        $('#modalUploadProfil').modal('hide');
        $('#ShowAvatar').attr('src', response);
        document.getElementById("image").value = response;
      })
    });

   $('#avatar').on('change', function(){
      var reader = new FileReader();
      reader.onload = function (event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function(){
          console.log('jQuery bind complete');
        });
      }
      reader.readAsDataURL(this.files[0]);
      console.log(this.files[0]);
      $('#modalUploadProfil').modal('show');
    });

 });
</script>
@endpush
   