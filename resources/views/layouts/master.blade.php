
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>AdminLTE 3 | Starter</title>

  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">
{{-- sidebar --}}
  <side-bar></side-bar>
{{-- sidebar END --}}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
  
    <router-view></router-view>
    <vue-progress-bar></vue-progress-bar>

    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

@auth

<script>
  
  window.user = @json(auth()->user());    
  
</script>

@endauth
<script src="/js/app.js"></script>
<script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script>$(document).ready( function () {
  $('#myTable').DataTable();
} );</script>
</body>
</html>
