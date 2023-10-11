<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="{{asset('')}}" />
  @yield('title')
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('layout.navbar')
  @include('layout.menu')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content_header')
    <section class="content">
    @yield('content')
    </section>
  </div>
  <!-- Content Wrapper. Contains page content -->
  @include('layout.footer')
  @include('layout.quickbar')
</div>
<!-- ./wrapper -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>
@yield('script')
</body>
</html>
