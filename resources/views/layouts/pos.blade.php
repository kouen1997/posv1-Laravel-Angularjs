<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') | Ecommercev1</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ URL::asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/components.css') }}">
  <!-- Start GA -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');
  </script>
  <!-- /END GA -->
  @yield('header_scripts')

</head>

<body>
  <div id="app">
    <div class="main-wrapper">
        
      @yield('content')

    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ URL::asset('assets/modules/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('assets/modules/popper.js') }}"></script>
  <script src="{{ URL::asset('assets/modules/tooltip.js') }}"></script>
  <script src="{{ URL::asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
  <script src="{{ URL::asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ URL::asset('assets/modules/moment.min.js') }}"></script>
  <script src="{{ URL::asset('assets/js/stisla.js') }}"></script>
    
  <!-- JS Libraies -->

  <!-- Page Specific JS File -->
    
  <!-- Template JS File -->
  <script src="{{ URL::asset('assets/js/scripts.js') }}"></script>
  <script src="{{ URL::asset('assets/js/custom.js') }}"></script>

  @yield('footer_scripts')

</body>

</html>