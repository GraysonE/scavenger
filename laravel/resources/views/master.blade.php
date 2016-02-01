<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
  <!--<![endif]-->

  <!-- start: HEAD -->
  <head>
    <title>Scavenger</title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <!-- end: META -->
    <!-- start: GOOGLE FONTS -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <!-- end: GOOGLE FONTS -->
    <!-- start: CSS -->
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/jquery-ui/themes/base/jquery-ui.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/bootstrap/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/fontawesome/css/font-awesome.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/themify-icons/themify-icons.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/animate.css/animate.min.css') !!}" media="screen">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/sweetalert/sweet-alert.css') !!}" media="screen">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/perfect-scrollbar/perfect-scrollbar.min.css') !!}" media="screen">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/switchery/switchery.min.css') !!}" media="screen">
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') !!}" id="skin_color" />
    <link rel="stylesheet" href="{!! asset('admin-files/vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css') !!}"/>
    <link rel="stylesheet" href="{!! asset('admin-files/assets/css/plugins.css') !!}">
    <link rel="stylesheet" href="{!! asset('admin-files/assets/css/themes/theme-stx.css') !!}" id="skin_color" />
    <link rel="stylesheet" href="{!! asset('admin-files/assets/css/styles.css') !!}" media="all" type="text/css">
    <link rel="stylesheet" href="{!! asset('admin-files/assets/css/scavenger.css') !!}" media="all" type="text/css">
    <link rel="stylesheet" href="{!! asset('admin-files/assets/css/grayson.css') !!}" media="all" type="text/css">

    <!-- end: CSS -->
  </head>
  <!-- end: HEAD --> 
  <body>
    <div id="app" class="app-navbar-fixed app-sidebar-fixed">
      
      <!-- sidebar -->
      <div class="sidebar app-aside" id="sidebar">
        <div class="sidebar-container perfect-scrollbar">
          <nav>
            @include('elements.sidebar')
          </nav>
        </div>
      </div>
      <!-- / sidebar -->

      <div class="app-content">
        <div id="siteWrap">
          <header class="navbar navbar-default navbar-static-top">
            @include('elements.header')  
          </header>

          <div class="main-content">
            <div class="wrap-content container" id="container">

              <div class="errors">
                @include ('errors.list')
              </div>
              
              @yield('content')

            </div> <!-- #container -->

          </div> <!-- .main-content -->
        </div>
      </div> <!-- .app-content -->

      <footer class="footerContainer">
        @include('elements.footer')    
      </footer>

    </div> <!-- #app -->

    <!-- start: MAIN JAVASCRIPTS -->
    <script src="{!! asset('admin-files/vendor/jquery/jquery.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/bootstrap/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/jquery-ui/jquery-ui.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/modernizr/modernizr.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/jquery-cookie/jquery.cookie.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/perfect-scrollbar/perfect-scrollbar.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/switchery/switchery.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/sweetalert/sweet-alert.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/ckeditor/ckeditor.js') !!}"></script>
    <script src="{!! asset('admin-files/vendor/ckeditor/adapters/jquery.js') !!}"></script>
    <script src="{!! asset('admin-files/assets/js/main.js') !!}"></script>	
    <script src="{!! asset('admin-files/assets/js/stx.js') !!}"></script>
    <script src="{!! asset('admin-files/assets/js/grayson.js') !!}"></script>

  </body>
</html>