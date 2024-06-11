<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crocs admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Crocs admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>Crocs - Premium Admin Template</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/calendar.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/vector-map.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/fullcalender.css') }}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <!-- App css-->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/layout/_sidebar.scss') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">


   <!-- DataTables CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
   <!-- DataTables Buttons CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
   <!-- DataTables SearchBuilder CSS -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css">

    <style>
        
  </style>
  </head>
  <body > 
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

     <!-- latest jquery-->
     <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
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
     <script src="{{ asset('assets/js/clock.js') }}"></script>
     <script src="{{ asset('assets/js/calendar/fullcalendar.min.js') }}"></script>
     <script src="{{ asset('assets/js/calendar/fullcalendar-custom.js') }}"></script>
     <script src="{{ asset('assets/js/calendar/fullcalender.js') }}"></script>
     <script src="{{ asset('assets/js/calendar/custom-calendar.js') }}"></script>
     <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
     <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
     <script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
     <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/jquery-jvectormap-2.0.2.min.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-au-mill.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-in-mill.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-asia-mill.js') }}"></script>
     <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
     <script src="{{ asset('assets/js/notify/index.js') }}"></script>
     <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
     <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
     <script src="{{ asset('assets/js/datatable/datatables/datatable.custom1.js') }}"></script>
     <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
     <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
     <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
     <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
     <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
     <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
     <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
     <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
     <script src="{{ asset('assets/js/vector-map/map-vector.js') }}"></script>
     <!-- Plugins JS Ends-->
     <!-- Theme js-->
     <script src="{{ asset('assets/js/script.js') }}"></script>
     <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
     <!-- Plugin used-->

     @stack('javascript')
   </body>
 </html>