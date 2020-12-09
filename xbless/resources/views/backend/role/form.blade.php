@extends('layouts.backend')
@section('pageTitle', "Manajemen Akses | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
<div class="container-fluid flex-grow-1 container-p-y">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="{{route('role.index')}}">Akses</a>
    </li>
    <li class="breadcrumb-item active">{{ ($dataSet) ? 'Ubah' : 'Tambah Baru'}}</li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Menu Akses</div>
  </h4>
  
  <div class="card mb-4">
    <div class="card-body">

      @if(session('message'))
      <div class="alert alert-{{session('message')['status']}}">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ session('message')['desc'] }}
      </div>
      @endif

      <div class="box-body">
        @if($dataSet)
        @can('role.ubah')
        <form class="form-horizontal" method="POST" action="{{route('role.ubah',$dataSet->id)}}">
          @endcan
          @elsecan('role.tambah')
          <form class="form-horizontal" method="POST" action="{{route('role.tambah')}}">
            @else
            <form class="form-horizontal" method="POST" action="#">
              @endcan
              {{ csrf_field() }}
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-3">
                    <label for="inputMC" class="control-label ">Nama Akses <span> *</span></label>
                    <p class="subtitle">Wajib diisi</p>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="{{ $dataSet ? $dataSet->name : ''}}" required>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="col-sm-3">
                    <label for="inputStatus" class="control-label">Permission <span> * </span></label>
                    <p class="subtitle">Wajib diisi.</p>
                  </div>
                  <div class="col-sm-6">
                    <div class="check-box">
                      <?php echo $checkbox_loop; ?>
                    </div>
                  </div>
                </div>
                 <div class="form-row">
                    <div class="form-group col-md-12">
                    <div class="text-right mt-3">
                      @if($dataSet)
                      @can('role.ubah')
                      <button type="submit" class="btn btn-simpan" >Simpan</button>&nbsp;
                      @endcan
                      @elsecan('role.tambah')
                      <button type="submit" class="btn btn-simpan">Simpan</button>&nbsp;
                      @endcan
                      <a href="{{route('role.index')}}"  class="btn btn-default">Kembali</a>
                    </div>
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
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/icheck.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/all.css" rel="stylesheet"/>
<script type="text/javascript">
  $(document).ready(function(){
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
   
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })
});
    $(function() {
      

        $('.check_tree').on('ifClicked', function(e){
            var $this     = $(this),
                checked   = $this.prop("checked"),
                container = $this.closest("li"),
                parents   = container.parents("li").first().find('.check_tree').first(),
                all       = true;

            // Centang juga anak2nya
            container.find('.check_tree').each(function() {
                if(checked) {
                    $(this).iCheck('uncheck');
                }else{
                    $(this).iCheck('check');
                }
            });

            // Cek sodaranya
            container.siblings().each(function() {
                return all = ($(this).find('.check_tree').first().prop("checked") === false);
            });

            // Cek bapaknya
            if(checked) {
                parents.iCheck('check');
            }
        });

        $('.check_tree').on('ifChanged', function(e){
                var $this     = $(this),
                    checked   = $this.prop("checked"),
                    parents   = $this.closest("li").parents("li").first().find('.check_tree').first(),
                    all       = true;
            
                // Cek bapaknya
                if(checked) {
                    parents.iCheck('check');
                }
        });
    });
    </script>

@endpush
   
