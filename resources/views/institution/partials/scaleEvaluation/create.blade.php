@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/datepicker.css')}}">
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('scaleEvaluation.index')}}">Escala Valorativa</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>'scaleEvaluation.store', 'method'=>'post'])!!}
				<div class="panel panel-default">

				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('name', 'Nombre') !!}
				  						{!! Form::text('name', null, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-2">
				  					<div class="form-group">
				  						{!! Form::label('abbreviation', 'Abreviación') !!}
				  						{!! Form::text('abbreviation', null, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('rank_start', 'Rango inicial') !!}
										{!! Form::text('rank_start', null, ['class'=>'form-control']) !!}
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										{!! Form::label('rank_end', 'Rango final') !!}
										{!! Form::text('rank_end', null, ['class'=>'form-control']) !!}
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('school_year_id', 'Año lectivo') !!}
										{!! Form::select('school_year_id', $schoolyears, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciona un año lectivo']) !!}
									</div>
								</div>
				  			</div>
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						<div class="">
				  							{!! Form::label('words_expressions_id', 'Expresión', []) !!}
				  							{!! Form::select('words_expressions_id', $wordExpresions, null, ['class'=>'form-control', 'placeholder' => '- Selecciona una Expresión-']) !!}
				  						</div>
				  					</div>
				  				</div>
				  			</div>
				  		</div>
				  	</div>
				  	<div class="panel-footer">
						<div class="form-group text-center">
				  			{!! Form::submit('Guardar', ['class'=>'btn btn-primary']) !!}
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
    	});
	</script>
@endsection