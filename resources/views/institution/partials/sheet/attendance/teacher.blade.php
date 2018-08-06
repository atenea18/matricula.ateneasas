<div class="col-md-12">
	{!! Form::open(['route'=>'teacher.attendances.pdf', 'method'=>'POST', 'target'=>'_blank']) !!}
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label('print_type', 'Imprimir por', []) !!}
				{!! Form::select('print_type', ['headquarter'=>'Sede', 'group'=>'Grupo'], null, ['placeholder'=>'- Selecciona un tipo -', 'class'=>'form-control']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="">Sede</label>
				{!! Form::select('headquarter_id_ta', $headquarters, null, ['class'=>'form-control', 'placeholder'=>'- Seleccione una sede -','id'=>'headquarter_id_ta']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{!! Form::label('group_id_ta', 'Grupo', []) !!}
				{!! Form::select('group_id_ta', [], null, ['class'=>'form-control']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="">Nombre del Evento</label>
				{!! Form::text('event', null, ['class'=>'form-control']) !!}
			</div>
		</div>
	</div>
	<div class="row text-center">
		<div class="form-group">
			<button class="btn btn-primary" type="submit" style="margin-top: 1.5em;">Imprimir</button>
		</div>
	</div>
	{!! Form::close() !!}
</div>