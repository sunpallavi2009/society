<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />



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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
        <!-- Plugins css start-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
   
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
        <!-- Plugins css Ends-->
        <!-- Bootstrap css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
        <!-- App css-->

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/layout/_sidebar.scss') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
        <!-- Responsive css-->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/js-datatables/style.css') }}">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body>
        
          <!-- loader starts-->
        <div class="loader-wrapper">
            <div class="loader">    
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            </div>
        </div>
        <!-- loader ends-->

        @include('layouts.partials.topbar')

        @include('layouts.partials.sidebar')

            <div class="page-body">
                
                @yield('content')
            
            </div>

        @include('layouts.partials.footer')

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>
        
        <!-- latest jquery-->
        {{-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> --}}
        <!-- Bootstrap js-->
        <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <!-- feather icon js-->
        <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
        <!-- scrollbar js-->
        <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
        <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
        <!-- Sidebar jquery-->
        <script src="{{ asset('assets/js/config.js') }}"></script>
        <!-- Plugins JS start-->
        <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
        <script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>
        <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
        <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
        <script src="{{ asset('assets/js/header-slick.js') }}"></script>
        <!-- Plugins JS Ends-->
        <!-- Theme js-->
        <script src="{{ asset('assets/js/script.js') }}"></script>
        <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>

        
        <script src="{{ asset('assets/js/js-datatables/simple-datatables@latest.js') }}"></script>
        <!-- Plugin used-->

        @stack('javascript')
    </body>
</html>
