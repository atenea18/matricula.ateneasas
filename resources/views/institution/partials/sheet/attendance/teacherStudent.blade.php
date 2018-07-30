<div class="col-md-12">
	{!! Form::open(['method'=>'POST', 'target'=>'_blank']) !!}
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Docente</label>
				{!! Form::select('teacher_id_sheet', $teachers, null, ['class'=>'form-control', 'placeholder'=>'- Seleccione un docente -','id'=>'teacher_id_sheet']) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('sheet_pa_teacher', [], null, ['id'=>'sheet_pa_teacher','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-default btn-block" id="sheet_pa_teacher_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_pa_teacher_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_pa_teacher_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
			<button type="button" id="sheet_pa_teacher_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('groups[]', [], null, ['id'=>'sheet_pa_teacher_to','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
				{!! Form::hidden('year', date('Y'), []) !!}
				{!! Form::hidden('institution_id', $institution->id, []) !!}
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