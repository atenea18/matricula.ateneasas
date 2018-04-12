@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('subgroup.index')}}">Subrupos</a></li>
	  <li class="active">Editar</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>['subgroup.update',$subgroup], 'method'=>'put'])!!}
				<div class="panel panel-default">

				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('headquarter_id', 'Sede') !!}
				  						{!! Form::select('headquarter_id', $headquarters, $subgroup->headquarter->id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una sede']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('grade_id', 'Grado') !!}
				  						{!! Form::select('grade_id', $grades, $subgroup->grade->id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un grado']) !!}
				  					</div>
				  				</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('name', 'Nombre del Subgrupo') !!}
										{!! Form::text('name', $subgroup->name, ['class'=>'form-control']) !!}
									</div>
								</div>
								{{-- <div class="col-md-3">
									<div class="form-group">
										{!! Form::label('working_day_id', 'Jornada') !!}
										{!! Form::select('working_day_id', $journeys, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una jornada']) !!}
									</div>
								</div> --}}
				  			</div>
				  			{{-- <div class="row">
								<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('quota', 'Cupos') !!}
				  						{!! Form::text('quota', null, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('teacher_id', 'Director de Grupo') !!}
				  						{!! Form::select('teacher_id', $teachers, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un docente']) !!}
				  					</div>
				  				</div>
				  			</div> --}}
				  		</div>
				  	</div>
				  	<div class="panel-footer">
						<div class="form-group text-center">
				  			{!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
						</div>
					</div>
				</div>
			{!! Form::close()!!}
    	</div>
    </div>
@endsection

@section('js')
	<script src="{{asset('js/chosen.jquery.js')}}"></script>
	<script>
    	$(function() {
	        $('.chosen-select').chosen({width: "100%"});
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    	});
	</script>
@endsection