@extends('institution.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">

@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('group.index')}}">Grupos</a></li>
        <li class="active">Asignación</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                {{--<div class="panel-heading clearfix">
                    <a class="btn btn-primary btn-sm pull-right" href="{{route('group.create')}}">Crear Grupo</a>
                </div>--}}
                <div class="panel-body">
                    <div id="app">
                        <example-component></example-component>
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