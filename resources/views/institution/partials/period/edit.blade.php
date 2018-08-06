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
				  				<div class="col-md-2">
				  					<div class="form-group">
				  						{!! Form::label('periods_id', 'Periodo') !!}
				  						{!! Form::select('periods_id', $periods, $period->periods_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciona un periodo']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-2">
				  					<div class="form-group">
				  						{!! Form::label('percent', 'Porcentaje (%)') !!}
				  						{!! Form::text('percent', $period->percent, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-2">
				  					<div class="form-group">
				  						{!! Form::label('working_day_id', 'Jornada') !!}
				  						{!! Form::select('working_day_id', $journeys, $period->working_day_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciona una jornada']) !!}
				  					</div>
				  				</div>
								<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('school_year_id', 'Año lectivo') !!}
										{!! Form::select('school_year_id', $schoolyears, $period->school_year_id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciona un año lectivo']) !!}
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('start_date', 'Fecha de inicio') !!}
										{!! Form::text('start_date', $period->start_date, ['class'=>'form-control datepicker']) !!}
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('end_date', 'Fecha de culminación') !!}
										{!! Form::text('end_date', $period->end_date, ['class'=>'form-control datepicker']) !!}
									</div>
								</div>
				  			</div>
				  			<div class="row">
				  				
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