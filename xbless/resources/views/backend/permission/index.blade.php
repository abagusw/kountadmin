@extends('layouts.backend')
@section('pageTitle', "Data Modul | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
<div class="container-fluid flex-grow-1 container-p-y">

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="javascript:void(0)">Modul</a>
    </li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Modul</div>
  @can('permission.tambah')
  <a href="{{ route('permission.tambah')}}" class="btn btn-tambah  d-block"><span class="ion ion-md-add"></span>&nbsp; Tambah Modul</a>
  @endcan
  </h4>

  @if(session('message'))
  <div class="alert alert-{{session('message')['status']}}">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ session('message')['desc'] }}
  </div>
  @endif

  <div class="card">
    <div class="card-datatable table-responsive">
      <table id="table1" class="table table-striped table-bordered">

        <thead>
          <tr>
              <th width="5%">#</th>
              <th>ID</th>
              <th>Nama</th>
              <th>Slug</th>
              <th>Ikon</th>
              <th>Menu?</th>
              <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          @if(count($dataObj) > 0)
                @foreach($dataObj as $n=>$data)
                <tr>
                    <th scope="row">{{ ++$n }}</th>
                    <td>{{ $data->nested }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->slug }}</td>
                    <td><i class="fa {{ $data->icon }}"></i>&nbsp;&nbsp;&nbsp;{{ $data->icon }}</td>
                    <td>{{ ($data->asmenu) ? 'Yes' : 'No' }}</td>
                    <td> @can('permission.ubah')<a href="{{route('permission.ubah',Crypt::encrypt($data->id))}}" class="btn btn-warning btn-xs icon-btn md-btn-flat product-tooltip" title="Edit"><i class="ion ion-md-create"></i></a>&nbsp;@endcan</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center">no records</td>
                </tr>
            @endif
        </tbody>
      </table>

    </div>
  </div>
</div>    
@endsection
@push('scripts')
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
   