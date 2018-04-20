@extends('institution.dashboard.index')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">

@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li class="active">Asignación de Subgrupos</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body" style="padding-top: 0px;">

                    <div id="app">
                        <subgroup-manager></subgroup-manager>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{--
    <script>
        $(document).ready(function () {

            $(".table").DataTable({
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                "info": false,
                // "order": [2],
                "autoWidth": false,
            });
        });
    </script>
    --}}
@endsection