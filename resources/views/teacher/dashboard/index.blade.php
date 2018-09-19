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

        <link href="{{asset('css/app.css?v=1.12')}}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('plugin/DataTables/datatables.css')}}">
        <link rel="stylesheet" href="{{asset('plugin/DataTables/dataTables.bootstrap.min.css')}}">
        @yield('css')

        <!-- Scripts -->
        <script src="{{asset('plugin/fontawesome5/svg-with-js/js/fontawesome-all.js')}}"></script>
        <script src="{{asset('plugin/ckeditor/ckeditor.js')}}"></script>
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <div id="wrapper">
            <!-- Sidebar -->
            @include('teacher.dashboard.sidebar')
            <!-- /#sidebar-wrapper -->
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <!-- SubHeader -->
                <!-- NAV -->
                @include('teacher.dashboard.header')
                <!-- END NAV -->
                
                <!--  -->
                <!-- /#subHeader-wrapper -->
                <div class="container-fluid xyz" id="content">
                    @yield('breadcrums')
                    @yield('content')
                </div>

            @include('teacher.dashboard.footer')    
            </div>
            
        </div>

        <!-- Scripts -->
        

        <script src="{{asset('js/app.js?v=1.12')}}"></script>
        <script src="{{asset('plugin/DataTables/datatables.min.js')}}"></script>
        <script src="{{asset('plugin/DataTables/dataTables.bootstrap.min.js')}}"></script>
        <script src="{{asset('plugin/multiSelect/multiselect.js')}}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @yield('js')

    </body>
</html>