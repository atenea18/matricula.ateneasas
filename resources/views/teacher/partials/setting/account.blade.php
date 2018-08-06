@extends('teacher.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('teacher.home')}}">Inicio</a></li>
        <li class="active">Configuración</li>
    </ol>
@endsection

@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default" style="margin-bottom: 0">
			<div class="panel-body" style="padding: 0">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#account" aria-controls="account" role="tab" data-toggle="tab">
							<i class="fa fa-cogs"></i> Cuenta
						</a>
					</li>
					<li role="presentation">
						<a href="{{route('teacher.setting.security')}}">
							<i class="fa fa-shield-alt"></i> Seguridad e inicio de sesión
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- Tab panes -->
<div class="tab-content" style="padding: 0">
	<div role="tabpanel" class="tab-pane active" id="account">
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info">
					<p>
						Para actualizar la contraseña dirigase a la sección <b>Seguridad</b>.
					</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">	
				<div class="panel panel-default">
					{{-- <div class="panel-heading">
						
					</div> --}}
					<div class="panel-body">
						<h4><i class="fa fa-info-circle"></i> Información personal</h4>
						<hr>
						{!! Form::open(['route'=>['setting.updateAccount', $manager], 'method'=>'PUT', 'class'=>'form-horizontal', 'id'=>'formUpdateAccount']) !!}
							<div class="form-group">
								{!! Form::label('name', 'Nombre', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
									{!! Form::text('name', $manager->name, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('last_name', 'Apellido', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
									{!! Form::text('last_name', $manager->last_name, ['class'=>'form-control']) !!}
							    </div>
							</div>
							{{-- Identification --}}
							<div class="form-group">
								{!! Form::label('identification_type_id', 'Tipo de identificación', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::select('identification_type_id', $identification_types, $manager->identification->identification_type_id, ['class'=>'form-control', 'disabled'=>true]) !!}
							    </div>
							</div>	
							<div class="form-group">
								{!! Form::label('identification_number', 'N° de identificación', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('identification_number', $manager->identification->identification_number, ['class'=>'form-control', 'disabled'=>true]) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('gender_id', 'Genero', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::select('gender_id', $genders, $manager->identification->gender_id, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('birthdate', 'Fecha de nacimiento', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::date('birthdate', $manager->identification->birthdate, ['class'=>'form-control']) !!}
							    </div>
							</div>	
							{{-- Address --}}
							<div class="form-group">
								{!! Form::label('address', 'Dirección ', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('address', $manager->address->address, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('neighborhood', 'Barrio', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('neighborhood', $manager->address->neighborhood, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('phone', 'Telefono', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('phone', $manager->address->phone, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('mobil', 'Celular', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('mobil', $manager->address->mobil, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('email', 'Correo electronico', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('email', $manager->address->email, ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('id_city_address', 'Ciudad', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::select('id_city_address', $cities, $manager->address->id_city_address, ['class'=>'form-control', 'placeholder'=>'- Selecciona una ciudad -']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('zone_id', 'Zona', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::select('zone_id', $zones, $manager->address->zone_id, ['class'=>'form-control', 'placeholder'=>'- Selecciona una zona -']) !!}
							    </div>
							</div>
							<div class="form-grop">
								<button type="submit" class="btn btn-primary btn-block">Actualizar</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
					
			</div>
			<div class="col-md-5">
				<div class="panel panel-default">
					{{-- <div class="panel-heading">
						
					</div> --}}
					<div class="panel-body">
						<h4><i class="fa fa-image"></i> Imagen de perfil</h4>
						<hr>
						{!! Form::open(['']) !!}
							
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
	<script>
		
		$(document).ready(function(){

			$("#formUpdateAccount").submit(function(e){

				e.preventDefault();

				var form = $(this);

				$.ajax({
					url: form.attr('action'),
					method: form.attr('method'),
					data: form.serialize(),
					dataType: 'json',
					beforeSend:function(){
						form.find('input,select,button').prop('disabled', true);
						form.find('button').empty().html("Actualizando..  <i class='fas fa-spinner fa-pulse'></i>");
					},
					success:function(data){
						form.find('input,select,button').prop('disabled', false);
						form.find('button').empty().text("Actualizar");
						
						toastr.success("Datos actualizados con exito");
					},
					error:function(xhr){
						form.find('input,select,button').prop('disabled', false);
						form.find('button').empty().text("Actualizar");
						
						toastr.error("Algo ha salido mal");
					}
				});
			});
		});
	
	</script>
@endsection