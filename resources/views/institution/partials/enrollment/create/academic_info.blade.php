<div id="identification" class="section_inscription">
	<div class="section_inscription__tittle">
		<h4>Información Acdémica</h4>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('academic_character_id') ? ' has-error' : '' }}">
				{!! Form::label('academic_character_id', 'Caracter') !!}
				{!! Form::select('academic_character_id', $characters, old('academic_character_id'), ['class'=>'form-control chosen-select', 'placeholder'=>'caracter académico']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('academic_specialty_id') ? ' has-error' : '' }}">
				{!! Form::label('academic_specialty_id', 'Especialidad') !!}
				{!! Form::select('academic_specialty_id', $specialties, old('academic_specialty_id'), ['class'=>'form-control chosen-select', 'placeholder'=>'especialidad académica']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('has_subsidy') ? ' has-error' : '' }}">
				{!! Form::label('has_subsidy', 'Subsidiado') !!}
				{!! Form::select('has_subsidy', [false=>'no', true=>'si'], old('has_subsidy'), ['class'=>'form-control chosen-select', 'placeholder'=>'Seleccione un tipo de subsidio']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('grade_id') ? ' has-error' : '' }}">
				{!! Form::label('grade_id', 'Grado') !!}
				{!! Form::select('grade_id', $grade, old('grade_id'), ['class'=>'form-control chosen-select', 'placeholder'=>'Seleccione un grado']) !!}
			</div>
		</div>
	</div>
</div>
{{--  --}}
<div id="identification" class="section_inscription">
	<div class="section_inscription__tittle">
		<h4>Información para la matricula</h4>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('headquarter_id') ? ' has-error' : '' }}">
				{!! Form::label('headquarter_id', 'Sede') !!}
				{!! Form::select('headquarter_id', $headquarters, old('headquarter_id'), ['placeholder'=>'Seleccione una sede', 'class'=>'form-control chosen-select']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group{{ $errors->has('workingday_id') ? ' has-error' : '' }}">
				{!! Form::label('workingday_id', 'Jornada') !!}
				{!! Form::select('workingday_id', $journeys, old('workingday_id'), ['placeholder'=>'Seleccione una jornada', 'class'=>'form-control chosen-select', 'id'=>'workingday_id']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label('group_id', 'Grupo') !!}
				{!! Form::select('group_id', [], old('group_id'), ['class'=>'form-control chosen-group', 'id'=>'group_id', 'data-placeholder'=>'Seleccione un grupo']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label('school_year_id', 'Año lectivo') !!}
				{!! Form::select('school_year_id', $schoolyears, old('school_year_id'), ['class'=>'form-control chosen-group', 'id'=>'school_year_id', 'placeholder'=>'Seleccione un año lectivo']) !!}
			</div>
		</div>
	</div>
</div>