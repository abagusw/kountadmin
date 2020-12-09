@extends('layouts.backend')
@section('pageTitle', "Manajemen Akses User | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
  <div class="container-fluid flex-grow-1 container-p-y">

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item">
      <a href="{{route('role.index')}}">Akses</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="javascript:void(0)">Akses User</a>
    </li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Akses</div>
  </h4>

  @if(session('message'))
  <div class="alert alert-{{session('message')['status']}}">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ session('message')['desc'] }}
  </div>
  @endif

  @can('role.user')
  <form class="form-inline" method="POST" action="{{ route('role.user',$dataSet->id) }}">
  @else
  <form class="form-inline" method="POST" action="#">
  @endcan

  {{ csrf_field() }}

  <div class="form-group mb-2 col-sm-2">
    <label>Pilih User</label>
  </div>
  <div class="form-group col-sm-2 mb-2">
    <select class="form-control" name="tambah_user" required>
       
        @foreach($userObj as $row)
        <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
  </div>

  @can('role.user')
    <button type="submit" class="btn btn-simpan mb-2">Simpan</button>
  @endcan
  </form>

  <div class="card">
    <div class="card-datatable table-responsive">
      <table id="table1" class="table table-striped table-bordered">
        <thead>
          <tr>
              <th class="text-left" width="5%">No</th>
              <th class="text-left">Nama</th>
              <th class="text-left"></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dataSet->user as $row)
              <tr>
                  <td class="text-left">{{$loop->iteration}}.</td>
                  <td>{{$row->name}}</td>
                  <td class="text-left">
                      @can('role.user')
                      <form class="formDelete" action="{{ route('role.user', $dataSet->id) }}" method="post">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          <input type="hidden" name="hapus_user" value="{{ $row->id }}">
                          <div class="btn-group">
                              <button type="submit" class="btn btn-danger btn-xs icon-btn md-btn-flat product-tooltip" title="Hapus"><i class="ion ion-md-close"></i></button>
                          </div>
                      </form>
                      @endcan
                  </td>
              </tr>
          @endforeach
      </tbody>
      </table>
    </div>
  </div>
</div>   

@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

<script type="text/javascript">
   var table,tabledata,table_index;
      $(document).ready(function(){
          
        $('#table1').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari data",
                }
            }); 
      });
</script>
@endpush
   


