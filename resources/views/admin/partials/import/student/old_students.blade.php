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
			    	<h3 class="panel-title pull-left">Importar</h3>
			  	</div>
			  	<div class="panel-body">
			  		{!! Form::open(['route'=>'import.old_students', 'method'=>'post', 'files'=>true]) !!}
						<div class="container-fuild">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Importar Estudiantes antigüos</label>
									{!! Form::file('excel', ['class'=>'form-control','required'=>true]) !!}
								</div>
								{!! Form::submit('Importar', ['class'=>'btn btn-primary']) !!}
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Institutción</label>
									{!! Form::select('institution_id', $institutions, null, ['class'=>'form-control', 'placeholder'=>'Selecciona una Institución','required'=>true]) !!}
								</div>
							</div>
						</div>
			  		{!! Form::close() !!}
			  	</div>
			</div>
    	</div>
    </div>
@endsection