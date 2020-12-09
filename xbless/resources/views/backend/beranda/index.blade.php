@extends('layouts.backend')
@section('pageTitle', "Halaman Utama | $perusahaan->nama")
@push('stylesheets')
@endpush
@section('main_container')
@if(session('message'))
<div class="alert alert-{{session('message')['status']}}">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {{ session('message')['desc'] }}
</div>
@endif
<div class="container-fluid flex-grow-1 container-p-y">
	<h4 class="media align-items-center font-weight-bold py-3 mb-4">
	<img src="{{session('profile')}}">
	<div class="media-body ml-3">
		Selamat Datang, {{auth()->user()->name}}!
		<div class="text-muted text-tiny mt-1"><small class="font-weight-normal">Tanggal {{$tgl}}</small></div>
	</div>
	</h4>
	<hr class="container-m-nx mt-0 mb-4">
	<div class="row">
		<div class="d-flex col-xl-6 align-items-stretch">
			<div class="card d-flex w-100 mb-4">
				<div class="row no-gutters row-bordered h-100">
					<div class="d-flex col-sm-6 col-md-4 col-lg-6 align-items-center">
						<a href="javascript:void(0)" class="card-body media align-items-center text-body">
							<i class="lnr lnr-user display-4 d-block text-primary"></i>
							<span class="media-body d-block ml-3">
								<span class="text-big font-weight-bolder">{{$jmluser}}</span><br>
								<small class="text-muted">Staff</small>
							</span>
						</a>
					</div>
					<div class="d-flex col-sm-6 col-md-4 col-lg-6 align-items-center">
						<a href="javascript:void(0)" class="card-body media align-items-center text-body">
							<i class="lnr lnr-book display-4 d-block text-primary"></i>
							<span class="media-body d-block ml-3">
								<span class="text-big"><span class="font-weight-bolder"></span><br>
								<small class="text-muted">Display 1</small>
							</span>
						</a>
					</div>
					<div class="d-flex col-sm-6 col-md-4 col-lg-6 align-items-center">
						<a href="javascript:void(0)" class="card-body media align-items-center text-body">
							<i class="lnr lnr-users display-4 d-block text-primary"></i>
							<span class="media-body d-block ml-3">
								<span class="text-big"><span class="font-weight-bolder"></span></span><br>
								<small class="text-muted">Display 2</small>
							</span>
						</a>
					</div>
					<div class="d-flex col-sm-6 col-md-4 col-lg-6 align-items-center">
						<a href="javascript:void(0)" class="card-body media align-items-center text-body">
							<i class="lnr lnr-picture display-4 d-block text-primary"></i>
							<span class="media-body d-block ml-3">
								<span class="text-big"><span class="font-weight-bolder"></span></span><br>
								<small class="text-muted">Display 3</small>
							</span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex col-xl-6 align-items-stretch">
			<div class="card w-100 mb-4">
				<div class="card-body">
					<button type="button" class="btn btn-sm btn-outline-primary icon-btn float-right"><i class="ion ion-md-sync"></i></button>
					<div class="text-muted small">Grafik</div>
					<div class="text-big">Ini Grafik</div>
				</div>
				<div class="px-2 mt-4">
					<div class="w-100" style="height: 120px;">
						<canvas id="statistics-chart-1"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
@endpush