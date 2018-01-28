@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
	<link rel="stylesheet" href="{{asset('css/datepicker.css')}}">
	<link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('enrollment.lists', 1)}}">Inscripcion</a></li>
	  <li class="active">Crear</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		{!! Form::open(['route' => 'enrollment.store', 'method' => 'post', 'files' => true]) !!}
	    		<div class="panel panel-default">
				  	<div class="panel-heading">
				    	<h3 class="panel-title">Grupos</h3>
				  	</div>
				  	<div class="panel-body">

				  		@include('complements.error')

				        <ul class="nav nav-tabs" role="tablist">
				        	<li role="presentation" class="active"><a href="#personal_info" aria-controls="personal_info" role="tab" data-toggle="tab">Inf. Personal</a></li>
						    <li role="presentation"><a href="#academic_info" aria-controls="academic_info" role="tab" data-toggle="tab">Inf. Académica</a></li>
						    <li role="presentation"><a href="#medical_info" aria-controls="medical_info" role="tab" data-toggle="tab">Inf. Médica</a></li>
						    <li role="presentation"><a href="#displacement" aria-controls="displacement" role="tab" data-toggle="tab">Desplazamiento</a></li>
						    <li role="presentation"><a href="#socioeconomic" aria-controls="socioeconomic" role="tab" data-toggle="tab">Economía</a></li>
						    <li role="presentation"><a href="#territorialty" aria-controls="territorialty" role="tab" data-toggle="tab">Territotialidad</a></li>
						    <li role="presentation"><a href="#capa_discapa" aria-controls="capa_discapa" role="tab" data-toggle="tab">Capacidades y Discapacidades</a></li>
						    <li role="presentation"><a href="#family" aria-controls="capa_discapa" role="tab" data-toggle="tab">Familiares</a></li>
				        </ul>

				        <div class="tab-content">
				        	{{-- PERSONAL INFORMATION --}}
						    <div role="tabpanel" class="tab-pane active" id="personal_info">
						    	@include('institution.partials.enrollment.create.personal_info')
						    </div>
						    {{-- ACADEMIC INFORMATION --}}
						    <div role="tabpanel" class="tab-pane" id="academic_info">
						    	@include('institution.partials.enrollment.create.academic_info')

						    </div>
						    {{-- MEDIC INFORMATION --}}
						    <div role="tabpanel" class="tab-pane" id="medical_info">
						    	<div class="row">
						    		<div class="col-md-7">
						    			<div class="form-group{{ $errors->has('eps_id') ? ' has-error' : '' }}">
						    				{!! Form::label('eps_id', 'Eps') !!}
						    				{!! Form::select('eps_id', $eps, old('eps'), ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una eps']) !!}
						    			</div>
						    		</div>
						    		<div class="col-md-5">
						    			<div class="form-group{{ $errors->has('ips') ? ' has-error' : '' }}">
						    				{!! Form::label('ips', 'Ips') !!}
						    				{!! Form::text('ips', null, ['class'=>'form-control']) !!}
						    			</div>
						    		</div>
						    	</div>
						    	<div class="row">
						    		<div class="col-md-5">
						    			<div class="form-group{{ $errors->has('ars') ? ' has-error' : '' }}">
						    				{!! Form::label('ars', 'Ars') !!}
						    				{!! Form::text('ars', null, ['class'=>'form-control']) !!}
						    			</div>
						    		</div>
						    		<div class="col-md-2">
						    			<div class="form-group{{ $errors->has('blood_type_id') ? ' has-error' : '' }}">
						    				{!! Form::label('blood_type_id', 'Tipo de sangre') !!}
						    				{!! Form::select('blood_type_id', $blood_types, old('blood_type_id'), ['class'=>'form-control chosen-select', 'placeholder' =>'tipo de sangre']) !!}
						    			</div>
						    		</div>
						    	</div>
						    </div>
						    {{-- DISPLACEMENT --}}
							<div role="tabpanel" class="tab-pane" id="displacement">
								<div class="col-md-5">
						    		<div class="form-group">
						    			{!! Form::label('victim_of_conflict_id', 'Victima de conflicto') !!}
						    			{!! Form::select('victim_of_conflict_id', $victims, old('victim_of_conflict_id'), ['class'=>'form-control chosen-select', 'placeholder'=> 'victimas del conflicto armado']) !!}
						    		</div>
						    	</div>
						    	<div class="col-md-2">
						    		<div class="form-group">
						    			{!! Form::label('expulsion_date', 'Fecha de expulsión') !!}
						    			{!! Form::text('expulsion_date', null, ['class'=>'form-control datepicker']) !!}
						    		</div>
						    	</div>
						    	<div class="col-md-3">
						    		<div class="form-group">
						    			{!! Form::label('certificate', 'Certificado') !!}
						    			{!! Form::file('certificate', ['class'=>'form-control']) !!}
						    		</div>
						    	</div>
							</div>
							{{-- SOCIOECONOMIC --}}
							<div role="tabpanel" class="tab-pane" id="socioeconomic">
								
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											{!! Form::label('sisben_number', 'Número de sisben') !!}
											{!! Form::text('sisben_number', old('sisben_number'), ['class'=>'form-control']) !!}
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											{!! Form::label('sisben_level', 'Nivel del sisben') !!}
											{!! Form::select('sisben_level', [
												1=>'1',
												2=>'2',
												3=>'3',
												4=>'4',
											], old('sisben_level'), ['class'=>'form-control chosen-select', 'placeholder'=>'Seleccione el nivel del sisben']) !!}
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											{!! Form::label('stratum_id', 'Estrato') !!}
											{!! Form::select('stratum_id', $stratums, old('stratum_id'), ['class'=>'form-control chosen-select', 'placeholder'=>'Seleccione un estrado']) !!}
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group text-center">
											<div>
												{!! Form::label('amcf','Alumno Madre Cabeza de Familia') !!}
											</div>
											{!! Form::checkbox('amcf') !!}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group text-center">
											<div>
												{!! Form::label('bhdmcf','Beneficiarios Hijos dependientes de madres cabezas de familia') !!}
											</div>
											{!! Form::checkbox('bhdmcf') !!}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group text-center">
											<div>
												{!! Form::label('bvfp','Beneficiario Veterano Fuerza Pública') !!}
											</div>
											{!! Form::checkbox('bvfp') !!}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group text-center">
											<div>
												{!! Form::label('bhn','Beneficiario Héroe Nación') !!}
											</div>
											{!! Form::checkbox('bhn') !!}
										</div>
									</div>
								</div>
							</div>
							{{-- TERRITORIALTY --}}
							<div role="tabpanel" class="tab-pane" id="territorialty">
								
								<div class="row">
									<div class="col-md-5">
										<div class="form-group">
											{!! Form::label('guard', 'Resguardo') !!}
											{!! Form::text('guard', null, ['class'=>'form-control']) !!}
										</div>
									</div>	
									<div class="col-md-5">
										<div class="form-group">
											{!! Form::label('ethnicity', 'Etnia') !!}
											{!! Form::text('ethnicity', null, ['class'=>'form-control']) !!}
										</div>
									</div>
								</div>
							</div>
							{{-- CAPACITY / DISCAPACITY --}}
							<div role="tabpanel" class="tab-pane" id="capa_discapa">
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('capacity_id', 'Capacidades')!!}
											{!! Form::select('capacity_id[]', $capacities, old('capacity_id[]'), ['class'=>'form-control chosen-select', 'data-placeholder'=>'Mis capacidades', 'multiple']) !!}
										</div>
									</div>	
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('discapacity_id', 'Discapacidades')!!}
											{!! Form::select('discapacity_id[]', $discapacities, old('discapacity_id[]'), ['class'=>'form-control chosen-select', 'data-placeholder'=>'Mis discapacidades', 'multiple']) !!}
										</div>
									</div>	
								</div>
							</div>
							{{-- FAMILY --}}
							<div role="tabpanel" class="tab-pane" id="family">
								<div class="row">
									<div class="form-group text-center">
										<button type="button" class="btn btn-primary" id="addFamily">Agregar Acudiente</button>

										<button type="button" class="btn btn-primary" id="searchFamily">Consultar Acudiente</button>
									</div>
								</div>
								<div class="row">
									<table class="table" id="tableFamily" width="100%">
										<thead>
											<tr>
												<th>Nombres</th>
												<th>Apellidos</th>
												<th>Parentesco</th>
												{{-- <th>N° de identificación</th> --}}
												<th>Dirección</th>
												<th>Celular</th>
												<th>Telefono</th>
												{{-- <th>Email</th> --}}
												<th></th>
											</tr>
										</thead>
									</table>
									<tbody>
									</tbody>
								</div>
							</div> 
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group text-center">
							{!! Form::hidden('enrollment_state_id', 1) !!}
							{!! Form::hidden('enrollment_result_id', 1) !!}
							{!! Form::hidden('student_id', $student->id, ['id'=>'student_id']) !!}
							{!! Form::hidden('AppUrl', env('APP_URL'), ['id'=>'AppUrl']) !!}
				  			{!! Form::submit('Guardar Inscripción', ['class'=>'btn btn-primary']) !!}
						</div>
					</div>
				</div>
			{!! Form::close() !!}
		</div>
    </div>

    
{{--  --}}
@include('institution.partials.enrollment.modalCreateFamily')
@include('institution.partials.enrollment.modalEditFamily')
@include('institution.partials.enrollment.modalDeleteFamily')
@include('institution.partials.enrollment.modalDetachFamily')
@include('institution.partials.enrollment..family.modalSearchFamily')
@endsection

@section('js')
	<script src="{{asset('js/chosen.jquery.js')}}"></script>
	<script src="{{asset('js/bootstrap-datepicker.js')}}"></script>
	<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
	<script src="{{asset('js/enrollment.js')}}"></script>
@endsection