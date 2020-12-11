@extends('layouts.backend')
@section('pageTitle', "Manajemen Goals | $perusahaan->nama")
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
        <a href="{{route('goals.index')}}">Goals</a>
      </li>
      <li class="breadcrumb-item active">{{isset($goals) ? 'Edit' : 'Tambah'}} </li>
    </ol>

    <h4 class="font-weight-bold py-3 mb-4">
    {{isset($goals) ? 'Edit' : 'Tambah'}}  <span class="text-muted">Goals</span>
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
            <input type="hidden" name="enc_id" id="enc_id" value="{{isset($goals)? $enc_id : ''}}">

            <br/>
            <hr class="border-light m-0">
            <br/>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">Goal Name<span>*</span></label>
                <input type="text" class="form-control mb-1" name="goal_name" id="goal_name" value="{{isset($goals)? $goals->goal_name : ''}}">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label class="form-label">Upper Limit<span>*</span></label>
                <input type="number" max="1000" min="0" class="form-control mb-1" name="upper_limit" id="upper_limit" value="{{isset($goals)? $goals->upper_limit : '0'}}">
              </div>
              <div class="form-group col-md-3">
                <label class="form-label">Lower Limit<span>*</span></label>
                <input type="number" max="1000" min="0" class="form-control mb-1" name="lower_limit" id="lower_limit" value="{{isset($goals)? $goals->lower_limit : '0'}}">
              </div>
              <div class="form-group col-md-3">
                <label class="form-label">Weight<span>*</span></label>
                <input type="number" max="1000" min="0" class="form-control mb-1" name="weight" id="weight" value="{{isset($goals)? $goals->weight : '0'}}">
              </div>
              <div class="form-group col-md-3">
                <label class="form-label">Type<span>*</span></label>
                <select name="type" id="type" class="form-control mb-1" >
                  @foreach ($type as $key => $item)
                  <option value="{{ $key }}" 
                  @if (isset($goals))
                  @if ($goals->type == $key)
                  selected
                  @endif
                  @endif>{{ $item }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="text-right mt-3">
                  <button type="submit" class="btn btn-simpan" id="simpan">Simpan</button>&nbsp;
                  <a href="{{route('goals.index')}}"  class="btn btn-default">Kembali</a>
                </div>
              </div>
            </div>

          </form>

          <div class="modal modal-fill-in" id="Loading">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="card-body text-center" id="loadingbro">
                    <div class="demo-inline-spacing">
                      <div class="spinner-grow" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                      <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

           <div id="modalUpload" class="modal" role="dialog">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title">Upload Gambar</h6>
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
    ignore: ":hidden:not(.editor)",
    rules: {
      title:{
        required: true
      },
      description:{
        required: true
      }
    },
    messages: {
      title: {
        required: "Judul Goals tidak boleh kosong"
      },
      description: {
        required: "Deskripsi goals tidak boleh kosong",
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
    },
    submitHandler: function(form) {
      SimpanData();
    }
  });
   function SimpanData(){
        $('#simpan').addClass("disabled");
         var enc_id         =$('#enc_id').val();
         var goal_name          =$('#goal_name').val();

         var dataFile = new FormData()

         dataFile.append('enc_id', enc_id);
         dataFile.append('goal_name', goal_name);

        $.ajax({
          type: 'POST',
          url : "{{route('goals.simpan')}}",
          headers: {'X-CSRF-TOKEN': $('[name="_token"]').val()},
          data: $('#submitData').serialize(),
          dataType: "json",
          beforeSend: function () {
              $('#Loading').modal('show');
          },
          success: function(data){
            if (data.success) {
               Swal.fire('Yes',data.message,'success');
               setTimeout(function(){ 
               window.location.href = '{{ route('goals.index') }}';
                }, 3000);
            } else {
               Swal.fire('Ups',data.message,'info');
            }

          },
          complete: function () {
             $('#simpan').removeClass("disabled");
             $('#Loading').modal('hide');
          },
          error: function(data){
               $('#simpan').removeClass("disabled");
               $('#Loading').modal('hide');
              console.log(data);
          }
        });
    }
</script>
@endpush
