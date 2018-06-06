<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Portal</title>
{{--<link rel="stylesheet" href="{{asset('css/app.css')}}">--}}

<!-- Bootstrap core CSS-->
    <link href="{{asset('sb-admin/vendor/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{asset('sb-admin/vendor/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="{{asset('sb-admin/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{asset('sb-admin/css/sb-admin.css')}}">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    {{--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">--}}
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<!-- Navigation-->
<div id="app">
    <admin-navbar></admin-navbar>
    <div class="content-wrapper">
        <router-view></router-view>
        <!-- /.content-wrapper-->
        <admin-footer></admin-footer>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>
</div>
{{--VUE--}}
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
{{--Vuetify--}}
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<!-- Bootstrap core JavaScript-->
<script src="{{asset('sb-admin/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Core plugin JavaScript-->
<script src="{{asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
<!-- Page level plugin JavaScript-->
{{--<script src="{{asset('sb-admin/vendor/chart.js/Chart.min.js')}}"></script>--}}
{{--<script src="{{asset('sb-admin/vendor/datatables/jquery.dataTables.js')}}"></script>--}}
{{--<script src="{{asset('sb-admin/vendor/datatables/dataTables.bootstrap4.js')}}"></script>--}}
<!-- Custom scripts for all pages-->
<script src="{{asset('sb-admin/js/sb-admin.min.js')}}"></script>
<!-- Custom scripts for this page-->
{{--<script src="{{asset('sb-admin/js/sb-admin-datatables.min.js')}}"></script>--}}
{{--<script src="{{asset('sb-admin/js/sb-admin-charts.min.js')}}"></script>--}}

</body>
</html>