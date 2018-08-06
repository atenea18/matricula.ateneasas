@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('headquarter.index')}}">Sedes</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>'headquarter.store'])!!}
				<div class="panel panel-default">
				  	<div class="panel-heading">
				    	<h3 class="panel-title">Crear sede</h3>
				  	</div>
				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			{{-- PERSONAL IDENTIFICATION --}}
				  			<div id="identification" class="section_inscription">
					  			<div class="row">
					  				<div class="col-md-3">
					  					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					  						{!! Form::label('name', 'Nombre de la sede') !!}
					  						{!! Form::text('name', null, ['class'=>'form-control']) !!}
					  					</div>
					  				</div>
					  				<div class="col-md-3">
					  					<div class="form-group">
					  						{!! Form::label('nit', 'NIT') !!}
					  						{!! Form::text('nit', null, ['class'=>'form-control']) !!}
					  					</div>
					  				</div>
					  			</div>
					  		</div>
					  		{{-- ADDRESS --}}
				  			<div id="identification" class="section_inscription">
				  				<div class="section_inscription__tittle">
				  					<h4>Dirección Residencia</h4>
				  				</div>
				  				<div class="row">
				  					<div class="col-md-2">
				  						<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
				  							{!! Form::label('address', 'Dirección') !!}
				  							{!! Form::text('address', old('address'), ['class' => 'form-control', 'id'=>'adress']) !!}
				  						</div>
				  					</div>
				  					<div class="col-md-3">
				  						<div class="form-group {{ $errors->has('neighborhood') ? ' has-error' : '' }}">
				  							{!! Form::label('neighborhood', 'Barrio') !!}
				  							{!! Form::text('neighborhood', old('neighborhood'), ['class' => 'form-control', 'id'=>'neighborhood']) !!}
				  						</div>
				  					</div>
				  					<div class="col-md-2">
				  						<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
				  							{!! Form::label('phone', 'Telefono') !!}
				  							{!! Form::text('phone', old('phone'), ['class' => 'form-control', 'id'=>'phone']) !!}
				  						</div>
				  					</div>
				  					<div class="col-md-2">
				  						<div class="form-group {{ $errors->has('mobil') ? ' has-error' : '' }}">
				  							{!! Form::label('mobil', 'Celular') !!}
				  							{!! Form::text('mobil', old('mobil'), ['class' => 'form-control', 'id'=>'mobil']) !!}
				  						</div>
				  					</div>
				  					<div class="col-md-3">
				  						<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
				  							{!! Form::label('email', 'Email') !!}
				  							{!! Form::text('email', old('email'), ['class' => 'form-control', 'id'=>'email']) !!}
				  						</div>
				  					</div>
				  				</div>
				  				<div class="row">
				  					<div class="col-md-3">
				  						<div class="form-group {{ $errors->has('id_city_address') ? ' has-error' : '' }}">
				  							{!! Form::label('id_city_address', 'Ciudad') !!}
				  							{!! Form::select('id_city_address', $cities, old('id_city_address'), ['class'=>'form-control chosen-select', 'placeholder'=>'seleccione una ciudad']) !!}
				  						</div>
				  					</div>
				  					<div class="col-md-3">
				  						<div class="form-group {{ $errors->has('zone_id') ? ' has-error' : '' }}">
				  							{!! Form::label('zone_id', 'Zona') !!}
				  							{!! Form::select('zone_id', $zones, old('zone_id'), ['class'=>'form-control chosen-select', 'placeholder'=>'seleccione una zona']) !!}
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
	<script src="{{asset('js/chosen.jquery.js')}}"></script>
	<script>
    	$(function() {
	        $('.chosen-select').chosen({width: "100%"});
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    	});
	</script>
@endsection