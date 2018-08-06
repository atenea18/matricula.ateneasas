@extends('teacher.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('teacher.home')}}">Inicio</a></li>
        <li class="active">Evaluación</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h2 class="panel-title pull-left">Evaluación de Periodo</h2>

                </div>
                <div class="panel-body">
                    <div id="app">

                        <evaluation-manager :group="{{$itemGroup}}" filter="{{$filter}}" :asignatureid="{{$asignature_id}}">
                        </evaluation-manager>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection