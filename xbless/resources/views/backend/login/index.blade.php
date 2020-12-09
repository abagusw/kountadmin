<!DOCTYPE html>
<html lang="en" class="light-style">
<head>
    <title>Login </title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

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
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/logo/logo.png')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/modifikasi.css')}}">
    <script src="{{ asset('assets/js/material-ripple.js')}}"></script>
    <script src="{{ asset('assets/js/layout-helpers.js')}}"></script>
    <script src="{{ asset('assets/js/theme.js')}}"></script>
    <script src="{{ asset('assets/js/pace.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
</head>
<body style="background-color: #f9f9f9">
    <div class="page-loader">
        <div class="bg-background"></div>
    </div>
    <div class="authentication-wrapper authentication-3">
        <div class="authentication-inner justify-content-center">
            <div class="d-none d-lg-flex col-lg-7 mt-5 ui-bg-cover ui-bg-overlay-container p-5" style="background: url('{{ asset('assets/bg/bg-antri.svg') }}') no-repeat; background-position:bottom; background-size:85%;" alt='<a href=" http://www.freepik.com">Designed by pch.vector / Freepik</a>'>
                {{-- <div class="ui-bg-overlay bg-dark opacity-50"></div> --}}
                <div class="w-100 text-white px-5">
                    <h1 class="display-2 text-gray font-weight-bolder mb-4">KOUNT</h1>
                    <div class="text-large text-gray font-weight-light">
                        Admin Login
                    </div>
                </div>
            </div>
            <div class="d-flex bg-white col-4 align-items-center p-5">
                <div class="px-3 flex-fill">
                    <div class="w-100">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="ui-w-60">
                                <div class="w-100 position-relative" style="padding-bottom: 54%">

                                </div>
                            </div>
                        </div>
                        <h4 class="text-center text-primary font-weight-normal mt-5 mb-0">Login System</h4>
                        <br />
                        @if(session('message'))
                        <div class="alert alert-{{session('message')['status']}}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ session('message')['desc'] }}
                        </div>
                        @endif
                        <form class="my-5" action="{{route('manage.checklogin')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" required="" value="">
                            </div>
                            <div class="form-group">
                                <label class="form-label d-flex justify-content-between align-items-end">
                                    <div>Password</div>
                                </label>
                                <input type="password" class="form-control" name="password" required="">
                            </div>
                            <label class="form-label d-flex justify-content-between align-items-end">
                                <div></div>
                                <a href="javascript:void(0)" class="d-block small text-link">Lupa Password?</a>
                            </label>
                            <div class="d-flex justify-content-between align-items-center m-0">
                                <button type="submit" class="btn btn-block btn-simpan">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/popper.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/js/sidenav.js')}}"></script>
    <script src="{{ asset('assets/js/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatables.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/sweetalert2.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.validate.js')}}"></script>
    <script src="{{ asset('assets/js/additional-methods.js')}}"></script>

</body>
</html>
