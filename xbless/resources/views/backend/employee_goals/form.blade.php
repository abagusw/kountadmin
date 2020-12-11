@extends('layouts.backend')
@section('pageTitle', "Manajemen Employee Goals | $perusahaan->nama")
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
        <a href="{{route('employee_goals.index')}}">Employee Goals</a>
      </li>
      <li class="breadcrumb-item active">{{isset($employee_goals) ? 'Edit' : 'Tambah'}} </li>
    </ol>

    <h4 class="font-percentage-bold py-3 mb-4">
    {{isset($employee_goals) ? 'Edit' : 'Tambah'}}  <span class="text-muted">Employee Goals</span>
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
            <input type="hidden" name="enc_id" id="enc_id" value="{{isset($employee_goals)? $enc_id : ''}}">

            <br/>
            <hr class="border-light m-0">
            <br/>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">Employee<span>*</span></label>
                <select name="employee_id" id="employee_id" class="select2 form-control mb-1" >
                  <option value="0" selected disabled>Select Employee</option>
                  @foreach ($employees as $key => $item)
                  <option value="{{ $item->id }}" 
                  @if (isset($employee_goals))
                  @if ($employee_goals->employee_id == $item->id)
                  selected
                  @endif
                  @endif>{{ $item->fullname }} | {{ $item->username }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label class="form-label">Goal<span>*</span></label>
                <select name="goal_id" id="goal_id" class="select2 form-control mb-1" >
                  <option value="0" selected disabled>Select Goal</option>
                  @foreach ($goals as $key => $item)
                  <option value="{{ $item->id }}" 
                  @if (isset($employee_goals))
                  @if ($employee_goals->goal_id == $item->id)
                  selected
                  @endif
                  @endif>{{ $item->goal_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label class="form-label">Current Progress<span>*</span></label>
                <input type="number" max="1000" min="0" class="form-control mb-1" name="current_progress" id="current_progress" value="{{isset($employee_goals)? $employee_goals->current_progress : '0'}}">
              </div>
              <div class="form-group col-md-6">
                <label class="form-label">Percentage<span>*</span></label>
                <input type="number" max="1000" min="0" class="form-control mb-1" name="percentage" id="percentage" value="{{isset($employee_goals)? $employee_goals->percentage : '0'}}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <div class="text-right mt-3">
                  <button type="submit" class="btn btn-simpan" id="simpan">Simpan</button>&nbsp;
                  <a href="{{route('employee_goals.index')}}"  class="btn btn-default">Kembali</a>
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
        required: "Judul EmployeeGoals tidak boleh kosong"
      },
      description: {
        required: "Deskripsi employee_goals tidak boleh kosong",
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
         var employee_id          =$('#employee_id').val();

         var dataFile = new FormData()

         dataFile.append('enc_id', enc_id);
         dataFile.append('employee_id', employee_id);

        $.ajax({
          type: 'POST',
          url : "{{route('employee_goals.simpan')}}",
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
               window.location.href = '{{ route('employee_goals.index') }}';
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
