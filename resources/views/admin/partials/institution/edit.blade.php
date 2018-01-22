@extends('admin.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('institution.index')}}">Instituciones</a></li>
	  <li class="active">Editar</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			@include('complements.error')
		</div>
	</div>
    <div class="row">
    	<div class="col-md-7">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			    	<h3 class="panel-title pull-left">Editar Institución</h3>
			  	</div>
			  	<div class="panel-body">
			  		{!! Form::open(['route'=>['institution.update',$institution], 'method'=>'PUT', 'files'=>true]) !!}
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="conatiner-fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="">Nombre de la institución</label>
												{!! Form::text('name', $institution->name, ['class'=>'form-control']) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Correo Electronico</label>
												{!! Form::text('email', $institution->email, ['class'=>'form-control']) !!}
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="">Codigo DANE</label>
												{!! Form::text('dane_code', $institution->dane_code, ['class'=>'form-control']) !!}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="">Imagen</label>
												<img src="{{asset('storage')."/".$institution->picture}}" alt="" width="300" style="display: block;margin: 0 auto;">
												{!! Form::file('picture', ['class'=>'form-control']) !!}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="for-group">
									<button class="btn btn-primary" type="submit">Actualizar</button>
								</div>
							</div>
						</div>
					</div>
			  		{!! Form::close() !!}
			  	</div>
			</div>
    	</div>
    	<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4>Cambiar contraseña</h4>
					</div>
					<div class="panel-body">
						{!! Form::open(['route'=>['institution.changePassword',$institution], 'method'=>'PUT']) !!}
						<div class="container-fluid">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Nueva Contraseña</label>
										{!! Form::password('password', ['class'=>'form-control']) !!}
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Confirmar Contraseña</label>
										{!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="for-group">
										<button class="btn btn-primary">Actualizar Contraseña</button>
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