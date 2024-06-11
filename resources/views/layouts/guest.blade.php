<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
        <title>Crocs - Premium Admin Template</title>
        <!-- Google font-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
       
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css') }}">
        <!-- ico-font-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
        <!-- Themify icon-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
        <!-- Flag icon-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
        <!-- Feather icon-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
        <!-- Plugins css start-->
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
        <!-- App css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            .h3login{
                font-size: 20px;
                font-weight: 400;
                letter-spacing: 0;
            }
            .plogin{
                margin-bottom: 25px;
                font-size: 14px;
                color: #9B9B9B;
            }
        </style>

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body>
        <div class="container-fluid p-0">

            <div class="row m-0">
              <div class="col-12 p-0">    
                <div class="login-card login-dark">
                  <div>
                    <div>
                        <a class="logo" href="index.html" >
                            <img class="img-fluid for-light" src="{{ asset('assets/images/logo/logo-1.png') }}" alt="looginpage"><img class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo.png') }}" alt="looginpage">
                        </a>
                    </div>
                    
                    {{ $slot }}

                  </div>
                </div>
              </div>
            </div>

            <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
            <!-- Bootstrap js-->
            <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
            <!-- feather icon js-->
            <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
            <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
            <!-- scrollbar js-->
            <!-- Sidebar jquery-->
            <script src="{{ asset('assets/js/config.js') }}"></script>
            <!-- Plugins JS start-->
            <!-- Plugins JS Ends-->
            <!-- Theme js-->
            <script src="{{ asset('assets/js/script.js') }}"></script>
            <!-- Plugin used-->
        </div>
    </body>
</html>
