<div class="col-md-12">
	{!! Form::open(['route'=>'constancy.study.pdf', 'method'=>'POST', 'target'=>'_blank']) !!}
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Tipo de Impresión</label>
				{!! Form::select('print_type', ['group_id_cs'=>'Por Grupo', 'grade_id_cs'=>'Por Grado'], null, ['class'=>'form-control', 'placeholder'=>'- Seleccione un tipo -','id'=>'print_type', 'required'=>true]) !!}
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group" id="fg_grade">
				<label for="">Grado</label>
				{!! Form::select('grade_id_cs', [], null, ['class'=>'form-control select-hidden', 'id'=>'grade_id_cs', 'placeholder'=> '- Seleccione un grado -', 'required'=>true]) !!}
			</div>
		</div> 
		<div class="col-md-4">
			<div class="form-group" id="fg_groups">
				<label for="">Grupo</label>
				{!! Form::select('group_id_cs', [], null, ['class'=>'form-control select-hidden', 'id'=>'group_id_cs', 'placeholder'=> '- Seleccione un grupo -', 'required'=>true]) !!}
			</div>
		</div> 
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('sheet_cs', [], null, ['id'=>'sheet_cs','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-default btn-block" id="sheet_cs_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_cs_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_cs_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
			<button type="button" id="sheet_cs_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('students[]', [], null, ['id'=>'sheet_cs_to','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
				{!! Form::hidden('year', date('Y'), []) !!}
				{!! Form::hidden('institution_id', $institution->id, []) !!}
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				<button type="submit" class="btn btn-primary">
					Imprimir Constancia
				</button>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
</div>