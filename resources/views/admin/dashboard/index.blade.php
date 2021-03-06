<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Styles -->
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('plugin/DataTables/datatables.css')}}">
        <link rel="stylesheet" href="{{asset('plugin/DataTables/dataTables.bootstrap.min.css')}}">
        @yield('css')

        <!-- Scripts -->
        <script src="{{asset('plugin/fontawesome5/svg-with-js/js/fontawesome-all.js')}}"></script>
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div id="wrapper">
            <!-- Sidebar -->
            @include('admin.dashboard.sidebar')
            <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <!-- SubHeader -->
                <!-- NAV -->
                @include('admin.dashboard.header')
                <!-- END NAV -->
                
                <!-- @include('admin.dashboard.subheader') -->
                <!-- /#subHeader-wrapper -->
                <div class="container-fluid xyz" id="content">
                    @yield('breadcrums')
                    @yield('content')
                </div>

            @include('admin.dashboard.footer')    
            </div>
            
        </div>

        <!-- Scripts -->
        
        <script src="{{asset('js/app.js')}}"></script>
        <script src="{{asset('plugin/DataTables/datatables.min.js')}}"></script>
        <script src="{{asset('plugin/DataTables/dataTables.bootstrap.min.js')}}"></script>
        @yield('js')

    </body>
</html>