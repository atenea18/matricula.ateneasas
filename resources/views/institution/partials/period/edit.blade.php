@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/datepicker.css')}}">
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('period.index')}}">Periodos Académico</a></li>
	  <li class="active">Actualizar</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>['period.update',$period], 'method'=>'put'])!!}
				<div class="panel panel-default">

				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('period_id', 'Periodo') !!}
				  						{!! Form::select('period_id', $periods, $period->period_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una sede']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('working_day_id', 'Jornada') !!}
				  						{!! Form::select('working_day_id', $journeys, $period->working_day_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un grado']) !!}
				  					</div>
				  				</div>
								<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('period_state_id', 'Estado') !!}
				  						{!! Form::select('period_state_id', $period_states, $period->period_state_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un estado']) !!}
				  					</div>
				  				</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('school_year_id', 'Año lectivo') !!}
										{!! Form::select('school_year_id', $schoolyears, $period->school_year_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una año lectivo']) !!}
									</div>
								</div>
				  			</div>
				  			<div class="row">
								<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('percent', 'Porcentaje (%)') !!}
				  						{!! Form::text('percent', $period->percent, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('start_date', 'Fecha de inicio') !!}
										{!! Form::text('start_date', $period->start_date, ['class'=>'form-control datepicker']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('end_date', 'Fecha de culminación') !!}
										{!! Form::text('end_date', $period->end_date, ['class'=>'form-control datepicker']) !!}
									</div>
								</div>
				  			</div>
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
	<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
	<script src="{{asset('js/chosen.jquery.js')}}"></script>
	<script>
    	$(function() {
	        $('.chosen-select').chosen({width: "100%"});
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });

	        $('.datepicker').datepicker({
				format: 'yyyy/mm/dd',
				startDate: '-3d',
				language: 'es'
			});
    	});
	</script>
@endsection