@extends('institution.dashboard.index')
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                <div class="panel-body" style="padding-top: 0px;">
                    <div id="app">
                        <group-assignment></group-assignment>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection