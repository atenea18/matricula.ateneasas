@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class=""><a href="{{route('constancy.index')}}">Constancias</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>'constancy.store', 'method'=>'post'])!!}
				<div class="panel panel-default">

				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('type_id', 'Tipo de Constancia') !!}
				  						{!! Form::select('type_id', $types, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un tipo', 'required'=>true]) !!}
				  					</div>
				  				</div>
				  			</div>
				  			<div class="row">
				  				{{-- First Rol --}}
				  				<div class="col-md-6">
				  					<div class="row">
					  					<div class="col-md-12">
					  						<div class="form-group">
						  						{!! Form::label('firstFirm', 'Primera Firma', []) !!}
						  						{!! Form::text('firstFirm', null, ['class'=>'form-control']) !!}
						  					</div>
					  					</div>
				  					</div>
				  					<div class="row">
					  					<div class="col-md-12">
					  						<div class="form-group">
						  						{!! Form::label('firstRol', 'Cargo', []) !!}
						  						{!! Form::text('firstRol', null, ['class'=>'form-control']) !!}
						  					</div>
					  					</div>
				  					</div>
				  				</div>
				  				{{-- Second Rol --}}
				  				<div class="col-md-6">
				  					<div class="row">
					  					<div class="col-md-12">
					  						<div class="form-group">
						  						{!! Form::label('secondFirm', 'Segunda Firma', []) !!}
						  						{!! Form::text('secondFirm', null, ['class'=>'form-control']) !!}
						  					</div>
					  					</div>
				  					</div>
				  					<div class="row">
					  					<div class="col-md-12">
					  						<div class="form-group">
						  						{!! Form::label('secondRol', 'Cargo', []) !!}
						  						{!! Form::text('secondRol', null, ['class'=>'form-control']) !!}
						  					</div>
					  					</div>
				  					</div>
				  				</div>
				  			</div>
				  			<div class="row">
				  				<div class="col-md-12">
									<div class="form-group">
										{!! Form::label('header', 'Encabezado', []) !!}
										{!! Form::textarea('header', null, ['class'=>'form-control', 'rows'=>6]) !!}
									</div>
				  				</div>
				  				{{-- <div class="col-md-12">
									<div class="form-group">
										{!! Form::label('foter', 'Pie', []) !!}
										{!! Form::textarea('foter', null, ['class'=>'form-control']) !!}
									</div>
				  				</div> --}}
				  			</div>
				  		</div>
				  	</div>
				  	<div class="panel-footer">
						<div class="form-group text-center">
							{!! Form::hidden('institution_id', $institution->id, []) !!}
				  			{!! Form::submit('Crear', ['class'=>'btn btn-primary']) !!}
						</div>
					</div>
				</div>
			{!! Form::close()!!}
    	</div>
    </div>
@endsection

@section('js')
	<script src="{{asset('plugin/ckeditor/ckeditor.js')}}"></script>
	<script>
    	// CKEDITOR.replace( 'header' );
    	// CKEDITOR.replace( 'footer' );
	</script>
@endsection