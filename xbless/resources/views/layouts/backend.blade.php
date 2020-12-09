<!DOCTYPE html>
<html lang="en" class="light-style">
<head>
    <title>@yield('pageTitle')</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/ionicons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/linearicons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/open-iconic.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/pe-icon-7-stroke.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/ui.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/modifikasi.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{isset($perusahaan)? url($perusahaan->fav) : 'https://ui-avatars.com/api/?name=R-T-I&background=ed4626&color=ffffff&rounded=true&length=3'}}">
    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/croppie.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/summernote.css')}}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css')}}">
    <script src="{{ asset('assets/js/material-ripple.js')}}"></script>
    <script src="{{ asset('assets/js/layout-helpers.js')}}"></script>
    <script src="{{ asset('assets/js/theme.js')}}"></script>
    <script src="{{ asset('assets/js/pace.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <style>
        .select2-selection__rendered {
            line-height: 35px !important;
        }
        .select2-container .select2-selection--single {
            height: 37px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>
    @stack('stylesheets')
</head>
<body>
    <div class="page-loader">
        <div class="bg-background"></div>
    </div>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <div class="bg-grad">
                <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-menu" style="background: url('{{ asset('assets/bg/pattern-circular.png') }}') no-repeat; background-position:bottom; background-size:100%">
                    @include('includes/header')
                    @include('includes/menu')
                </div>
            </div>
            <div class="layout-container">
                <nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center bg-white container-p-x" id="layout-navbar">
                    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center">
                        <a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">
                            <i class="ion ion-md-menu text-large align-middle"></i>
                        </a>
                    </div>
                    <a href="{{route('manage.beranda')}}" class="navbar-brand app-brand logo d-lg-none py-0 mr-4">
                        <span class="app-brand-text logo font-weight-normal ml-2">KOUNT ADMIN</span>
                    </a>
                    <div class="navbar-nav align-items-lg-center ml-auto bless">
                        
                        <div class="demo-navbar-user nav-item dropdown">

                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                                    <img src="{{session('profile')}}" alt class="d-block ui-w-30 rounded-circle">
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{route('profil.index')}}" class="dropdown-item"><i class="ion ion-ios-person text-lightest"></i> &nbsp; Profil</a>
                                <a href="{{route('profil.password')}}" class="dropdown-item"><i class="ion ion-ios-mail text-lightest"></i> &nbsp; Ganti Password</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{route('manage.logout')}}" class="dropdown-item"><i class="ion ion-ios-log-out text-danger"></i> &nbsp; Log Out</a>
                            </div>
                        </div>
                    </div>
                </nav>
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        @yield('main_container')
                    </div>
                    <nav class="layout-footer footer bg-footer-theme">
                        <div class="container-fluid d-flex flex-wrap justify-content-between text-center container-p-x pb-3">
                            <div class="pt-3">
                                <span class="footer-text font-weight-bolder">2020</span> © {{$perusahaan->nama}} Support By <a href="https://rapiertechnology.co.id/" target="_blank">PT. Rapier Technology International</a>
                            </div>

                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <div class="layout-overlay layout-sidenav-toggle"></div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/js/popper.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/js/sidenav.js')}}"></script>
    <script src="{{ asset('assets/js/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/menu_active.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('assets/js/croppie.js')}}"></script>
    <script src="{{ asset('assets/js/datatables.js')}}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/sweetalert2.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap-material-datetimepicker.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.validate.js')}}"></script>
    <script src="{{ asset('assets/js/summernote.js')}}"></script>
    <script src="{{ asset('assets/js/additional-methods.js')}}"></script>
        <!-- Select2 -->
    <script src="{{ asset('assets/js/select2.full.min.js')}}"></script>
    <script>
            $(function () {
                $('.select2').select2()
            })
        </script>
    @stack('scripts')
  </body>
</html>
