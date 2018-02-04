@extends('admin.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Sedes</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			    	<h3 class="panel-title pull-left">Ver Institución</h3>
			    	<div class="pull-right">
			    		{{-- <a href="{{route('institutions.excel')}}" class="btn btn-success btn-sm" target="_blank"><i class="fa fa-file-excel"></i></a>
			    		<a href="{{route('institution.create')}}" class="btn btn-primary btn-sm">Crear Sede</a> --}}
			    	</div>
			  	</div>
			  	<div class="panel-body">
			  		{!! Form::open(['route'=>'import.enrollment', 'method'=>'post', 'files'=>true]) !!}
						<div class="container-fuild">
							<div class="col-md-12">
								<div class="form-group">
									<label for="">Importar identificaciones</label>
									{!! Form::file('excel', ['class'=>'form-control']) !!}
								</div>
								{!! Form::submit('Guardar', ['class'=>'btn btn-primary']) !!}
							</div>
						</div>
			  		{!! Form::close() !!}
			  	</div>
			</div>
    	</div>
    </div>
@endsection