@extends('layouts.backend')
@section('pageTitle', "Data Akses | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
<div class="container-fluid flex-grow-1 container-p-y">

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="javascript:void(0)">Akses</a>
    </li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Akses</div>
  @can('role.tambah')
  <a href="{{ route('role.tambah')}}" class="btn btn-tambah  d-block"><span class="ion ion-md-add"></span>&nbsp; Tambah Akses</a>
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
            <th width="5%">No</th>
            <th>Nama Akses</th>
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js')}}"></script>
<script type="text/javascript">
   var table,tabledata,table_index;
      $(document).ready(function(){
          table= $('#table1').DataTable({
          "processing": true,
          "serverSide": true,
          "stateSave"  : true,
          "deferRender": true,
          "pageLength": 10,
          "select" : true,
          "ajax":{
                   "url": "{{ route("role.getdata") }}",
                   "dataType": "json",
                   "type": "POST",
                   "data":{ 
                     _token: "{{csrf_token()}}",
                     }
                 },
          "columns": [
              { 
                "data": "no",
                "orderable" : false,
              },
              { "data": "name" },
             
              { "data" : "action",
                "orderable" : false,
                "className" : "text-center",
              },
          ],
          responsive: true,
          language: {
              search: "_INPUT_",
              searchPlaceholder: "Cari data",
              emptyTable: "Belum ada data",
              info: "Menampilkan data _START_ sampai _END_ dari _MAX_ data.",
              infoEmpty: "Menampilkan 0 sampai 0 dari 0 data.",
              lengthMenu: "Tampilkan _MENU_ data per halaman",
              loadingRecords: "Loading...",
              processing: "Mencari...",
              paginate: {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Sesudah",
                "previous": "Sebelum"
              },
          }
        });
        tabledata = $('#orderData').DataTable({
          dom     : 'lrtp',
          paging    : false,
          columnDefs: [ {
                "targets": 'no-sort',
                "orderable": false,
          } ]
        });
      });
      function deleteData(e,enc_id){
          @cannot('role.hapus')
              swal('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi ADMIN Anda.",'error'); return false;
          @else
          var token = '{{ csrf_token() }}';
          Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data akan terhapus!",
          
            icon: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya",
            cancelButtonText:"Batal",
            confirmButtonColor: "#ec6c62",
            closeOnConfirm: false
          }).then(function(result) {
          if (result.value) {
            $.ajaxSetup({
              headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
            });
             $.ajax({
              type: 'DELETE',
              url: '{{route("role.hapus",[null])}}/' + enc_id,
              headers: {'X-CSRF-TOKEN': token},
              success: function(data){
                if (data.status=='success') {
                    Swal.fire('Yes',data.message,'success');
                    table.ajax.reload(null, true);
                 }else{
                   Swal.fire('Ups',data.message,'info');
                 }
              },
              error: function(data){
                console.log(data);
                Swal.fire("Ups!", "Terjadi kesalahan pada sistem.", "error");
              }
            });

           
          } else {
           
          }
         });
          @endcannot
      }
</script>
@endpush
   
   