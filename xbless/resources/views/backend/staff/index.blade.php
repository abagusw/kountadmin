@extends('layouts.backend')
@section('pageTitle', "Data Staff | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
<div class="container-fluid flex-grow-1 container-p-y">

  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="{{route('manage.beranda')}}">Beranda</a>
    </li>
    <li class="breadcrumb-item active">
      <a href="javascript:void(0)">Staff</a>
    </li>
  </ol>

  <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
  <div>Staff</div>
  @can('staff.tambah')
  <a href="{{ route('staff.tambah')}}" class="btn btn-tambah d-block"><span class="ion ion-md-add"></span>&nbsp; Tambah Staff</a>
  @endcan
  </h4>

  @if(session('message'))
  <div class="alert alert-{{session('message')['status']}}">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ session('message')['desc'] }}
  </div>
  @endif

  <div class="ui-bordered px-4 pt-4 mb-4">
    <div class="form-row">
      <div class="col-md-3 mb-4">
        <label class="form-label">Status</label>
        <select class="custom-select" name="statusfilter" id="statusfilter">
           @foreach($statusfilter as $key => $row)
              <option value="{{$key}}" {{$selectedstatusfilter==$key? 'selected':''}}>{{$row}}</option>
            @endforeach
        </select>
      </div>
      <div class="col-md col-xl-2 mb-4">
        <label class="form-label d-none d-md-block">&nbsp;</label>
        <button type="button" id="filter" class="btn btn-simpan btn-block">Filter</button>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-datatable table-responsive">
      <table id="table1" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="1%">No</th>
            <th width="5%">Photo</th>
            <th>Nama</th>
            <th>Email</th>
            <th width="5%">No HP</th>
            <th width="5%">Status</th>
            <th></th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  
</div>
@endsection
@push('scripts')
<script type="text/javascript">
   var table,tabledata,table_index;
      $(document).ready(function(){
          $.ajaxSetup({
              headers: { "X-CSRF-Token" : $("meta[name=csrf-token]").attr("content") }
          });
          table= $('#table1').DataTable({
          "processing": true,
          "serverSide": true,
          "stateSave"  : true,
          "deferRender": true,
          "pageLength": 25,
          "select" : true,
          "ajax":{
                   "url": "{{ route("staff.getdata") }}",
                   "dataType": "json",
                   "type": "POST",
                   data: function ( d ) {
                     d._token= "{{csrf_token()}}";
                     d.statusfilter= $('#statusfilter').val();
                   }
                 },
          "columns": [
              { 
                "data": "no",
                "orderable" : false,
              },
              { "data": "photo" },
              { "data": "name" },
              { "data": "email" },
              { "data": "no_hp" },
              { "data": "status",
                "className" : "text-left",
              },
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
        $('#filter').click(function(){
            table.ajax.reload(null, false);
            
        });
        table.on('select', function ( e, dt, type, indexes ){
          table_index = indexes;
          var rowData = table.rows( indexes ).data().toArray();  

        });
      });
      
       
        function deleteStaff(e,enc_id){
          @cannot('staff.hapus')
              Swal.fire('Ups!', "Anda tidak memiliki HAK AKSES! Hubungi ADMIN Anda.",'error'); return false;
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
              url: '{{route("staff.hapus",[null])}}/' + enc_id,
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
      $(document.body).on("keydown", function(e){
        ele = document.activeElement;
          if(e.keyCode==38){
            table.row(table_index).deselect();
            table.row(table_index-1).select();
          }
          else if(e.keyCode==40){
              
            table.row(table_index).deselect();
            table.rows(parseInt(table_index)+1).select();
            console.log(parseInt(table_index)+1);
              
          }
          else if(e.keyCode==13){
           
          }
      });
</script>
@endpush
   