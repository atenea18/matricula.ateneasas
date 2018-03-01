@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('evaluationParameter.index')}}">Parametros de Evaluación</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">

			  	</div>
			  	<div class="panel-body">
					<div class="container-fluid">
					{!! Form::open(['route'=>'evaluationParameter.store', 'method'=>'post']) !!}
						@include('complements.error')
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('parameter', 'Parametros de Evaluación', []) !!}
									
									{!! Form::text('parameter', null, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('abbreviation', 'Abreviacón', []) !!}
									
									{!! Form::text('abbreviation', null, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('percent', 'Porcentaje (%)', []) !!}
									
									{!! Form::text('percent', null, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('school_year_id', 'Año lectivo', []) !!}
									
									{!! Form::select('school_year_id', $schoolyears, null, ['class'=>'form-control', 'placeholder'=>'- Año lectivo -']) !!}
								</div>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-md-12">
								{!! Form::hidden('institution_id', $institution->id, []) !!}
								<button class="btn btn-primary">Guardar</button>
							</div>
						</div>
					{!! Form::close() !!}
					</div>
			  	</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	{{-- <script>
		$(document).ready(function(){

			$(".table").DataTable( {
				"language": {
				    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
				},
				"info":     false,
				// "order": [2],
				"autoWidth": false,
		    });
		});
	</script> --}}
@endsection