<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title')
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/custom-admin.css') }}" rel="stylesheet" type="text/css" />

    @yield('header-styles')

</head>

<body class="adminbody">

<div id="main">

    <!-- top bar navigation -->
    @include('layouts.admin._header')
    <!-- End Navigation -->


    <!-- Left Sidebar -->
    @include('layouts.admin._sidebar')
    <!-- End Sidebar -->


    <div class="content-page">

        <!-- Start content -->
        <div class="content">
            @yield('content')
        </div>
        <!-- END content -->

    </div>
    <!-- END content-page -->

    <footer class="footer">
		<span class="text-right">
		Copyright <a target="_blank" href="#">{{ config('app.name', 'Laravel') }}</a>
		</span>
        <span class="float-right">
		Powered by <a target="_blank" href="https://www.pikeadmin.com"><b>Pike Admin</b></a>
		</span>
    </footer>

</div>
<!-- END main -->

<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>

<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/detect.js') }}"></script>
<script src="{{ asset('assets/js/fastclick.js') }}"></script>
<script src="{{ asset('assets/js/jquery.blockUI.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/pikeadmin.js') }}"></script>

<!-- BEGIN Java Script for this page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Counter-Up-->
<script src="{{ asset('assets/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/plugins/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // data-tables
        $('#example1').DataTable();

        // counter-up
        $('.counter').counterUp({
            delay: 10,
            time: 600
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
        });
    } );
</script>

<script>
    // var ctx1 = document.getElementById("lineChart").getContext('2d');
    // var lineChart = new Chart(ctx1, {
    //     type: 'bar',
    //     data: {
    //         labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    //         datasets: [{
    //             label: 'Dataset 1',
    //             backgroundColor: '#3EB9DC',
    //             data: [10, 14, 6, 7, 13, 9, 13, 16, 11, 8, 12, 9]
    //         }, {
    //             label: 'Dataset 2',
    //             backgroundColor: '#EBEFF3',
    //             data: [12, 14, 6, 7, 13, 6, 13, 16, 10, 8, 11, 12]
    //         }]
    //
    //     },
    //     options: {
    //         tooltips: {
    //             mode: 'index',
    //             intersect: false
    //         },
    //         responsive: true,
    //         scales: {
    //             xAxes: [{
    //                 stacked: true,
    //             }],
    //             yAxes: [{
    //                 stacked: true
    //             }]
    //         }
    //     }
    // });
    //
    //
    // var ctx2 = document.getElementById("pieChart").getContext('2d');
    // var pieChart = new Chart(ctx2, {
    //     type: 'pie',
    //     data: {
    //         datasets: [{
    //             data: [12, 19, 3, 5, 2, 3],
    //             backgroundColor: [
    //                 'rgba(255,99,132,1)',
    //                 'rgba(54, 162, 235, 1)',
    //                 'rgba(255, 206, 86, 1)',
    //                 'rgba(75, 192, 192, 1)',
    //                 'rgba(153, 102, 255, 1)',
    //                 'rgba(255, 159, 64, 1)'
    //             ],
    //             label: 'Dataset 1'
    //         }],
    //         labels: [
    //             "Red",
    //             "Orange",
    //             "Yellow",
    //             "Green",
    //             "Blue"
    //         ]
    //     },
    //     options: {
    //         responsive: true
    //     }
    //
    // });
    //
    //
    // var ctx3 = document.getElementById("doughnutChart").getContext('2d');
    // var doughnutChart = new Chart(ctx3, {
    //     type: 'doughnut',
    //     data: {
    //         datasets: [{
    //             data: [12, 19, 3, 5, 2, 3],
    //             backgroundColor: [
    //                 'rgba(255,99,132,1)',
    //                 'rgba(54, 162, 235, 1)',
    //                 'rgba(255, 206, 86, 1)',
    //                 'rgba(75, 192, 192, 1)',
    //                 'rgba(153, 102, 255, 1)',
    //                 'rgba(255, 159, 64, 1)'
    //             ],
    //             label: 'Dataset 1'
    //         }],
    //         labels: [
    //             "Red",
    //             "Orange",
    //             "Yellow",
    //             "Green",
    //             "Blue"
    //         ]
    //     },
    //     options: {
    //         responsive: true
    //     }
    //
    // });



</script>
<!-- END Java Script for this page -->
@yield('footer-script')
</body>
</html>