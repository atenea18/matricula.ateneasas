@extends('admin.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('institution.index')}}">Instituciones</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		@include('complements.error')
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			    	<h3 class="panel-title pull-left">Crear Institución</h3>
			  	</div>
			  	<div class="panel-body">
			  		{!! Form::open(['route'=>'institution.store', 'method'=>'POST', 'files'=>true]) !!}
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-8">
								<div class="conatiner-fluid">
									<div class="row">
										<div class="col-md-8">
											<div class="form-group">
												<label for="">Nombre de la institución</label>
												{!! Form::text('name', null, ['class'=>'form-control']) !!}
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Codigo DANE</label>
												{!! Form::text('dane_code', null, ['class'=>'form-control']) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Correo Electronico</label>
												{!! Form::text('email', null, ['class'=>'form-control']) !!}
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Contraseña</label>
												{!! Form::password('password', ['class'=>'form-control']) !!}
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Confirmar Contraseña</label>
												{!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Imagen</label>
										{!! Form::file('picture', ['class'=>'form-control']) !!}
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="for-group">
									<button class="btn btn-primary" type="submit">Crear</button>
								</div>
							</div>
						</div>
					</div>
			  		{!! Form::close() !!}
			  	</div>
			</div>
    	</div>
    </div>
@endsection