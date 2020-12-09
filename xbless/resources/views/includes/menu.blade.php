<div class="sidenav-divider mt-0"></div>
<ul class="sidenav-inner py-1" id="sidebar-menu">
  <li class="sidenav-item">
    <a href="{{route('manage.beranda')}}" class="sidenav-link"><i class="sidenav-icon ion ion-md-home"></i>
      <div>Beranda</div>
    </a>
  </li>
  @can('master.index')
  <li class="sidenav-item">
    <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-albums"></i>
      <div>Master</div>
    </a>
    <ul class="sidenav-menu">        
      <li class="sidenav-item">
        <a href="#"  class="sidenav-link">
          <div>Testing</div>
        </a>
      </li>
    </ul>
  </li>
  @endcan
  
  @can('pengguna.index')
  <li class="sidenav-item">
    <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-contacts"></i>
      <div>Pengguna</div>
    </a>
    <ul class="sidenav-menu">
      @can('staff.index')
      <li class="sidenav-item">
        <a href="{{route('staff.index')}}" class="sidenav-link">
          <div>Staff</div>
        </a>
      </li>
      @endcan
      
    </ul>
  </li>
  @endcan
  
  @can('security.index')
  <li class="sidenav-item">
    <a href="javascript:void(0)" class="sidenav-link sidenav-toggle"><i class="sidenav-icon ion ion-md-key"></i>
      <div>Keamanan</div>
    </a>
    <ul class="sidenav-menu">
      @can('permission.index')
      <li class="sidenav-item">
        <a href="{{route('permission.index')}}" class="sidenav-link">
          <div>Modul</div>
        </a>
      </li>
      @endcan
      @can('role.index')
      <li class="sidenav-item">
        <a href="{{route('role.index')}}" class="sidenav-link">
          <div>Akses</div>
        </a>
      </li>
      @endcan
    </ul>
  </li>
  @endcan
  
</ul>