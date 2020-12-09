<div class="app-brand logo">
	<span class="app-brand-logo logo">
		<img src="{{isset($perusahaan)? url($perusahaan->logo) : 'https://ui-avatars.com/api/?name=R-T-I&background=ed4626&color=ffffff&rounded=true&length=3'}}" alt class="d-block ui-w-30 rounded-circle">
	</span>
	<a href="{{route('manage.beranda')}}" class="app-brand-text logo sidenav-text font-weight-normal ml-2">KOUNT ADMIN</a>
	<a href="javascript:void(0)" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
		<i class="ion ion-md-list align-middle"></i>
	</a>
</div>