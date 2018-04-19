<div class="container-fluid">
	{{-- PERSONAL IDENTIFICATION --}}
	<div id="identification" class="section_inscription">
		<div class="section_inscription__tittle">
			<h4>Datos de indentificaión</h4>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('name', 'Nombres') !!}
					{!! Form::text('name_dis', $student->name, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('last_name', 'Apellidos') !!}
					{!! Form::text('last_name_dis', $student->last_name, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('identification_type_dis', 'Tipo de identificación') !!}
					{!! Form::text('identification_type', $student->identification->identification_type->name, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('identification_number_dis', 'Número de identificación') !!}
					{!! Form::text('identification_number', $student->identification->identification_number, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<!--  CITY  EXPEDITION / BIRTH-->
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('city_expedition_dis', 'Ciudad de expedicción') !!}
					{!! Form::text('city_expedition', (!is_null($student->identification->city_expedition)) ? $student->identification->city_expedition->name : null, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('city_birth_dis', 'Ciudad de nacimiento') !!}
					{!! Form::text('city_birth', (!is_null($student->identification->city_birth)) ? $student->identification->city_birth->name : null, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('birthdate_dis', 'Fecha de nacimiento') !!}
					{!! Form::text('birthdate_dis', $student->identification->birthdate, ['class'=>'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('gender_dis', 'Genero') !!}
					{!! Form::text('gender', $student->identification->gender->gender, ['class'=>'form-control', 'disabled']) !!}
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
				<div class="form-group">
					{!! Form::label('address', 'Dirección') !!}
					{!! Form::text('adress_dis', $student->address->address, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('neighborhood', 'Barrio') !!}
					{!! Form::text('neighborhood_dis', $student->address->neighborhood, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					{!! Form::label('phone', 'Telefono') !!}
					{!! Form::text('phone_dis', $student->address->phone, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
					{!! Form::label('mobil', 'Celular') !!}
					{!! Form::text('mobil_dis', $student->address->mobil, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('email', 'Email') !!}
					{!! Form::text('email_dis', $student->address->email, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('city', 'Ciudad') !!}
					{!! Form::text('city_dis', $student->address->city->name, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('zone', 'Zona rural') !!}
					{!! Form::text('zone_dis', $student->address->zone->name, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>
		</div>
	</div>
</div>