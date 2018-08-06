<div class="col-md-12">
	{!! Form::open(['route'=>'evaluationSheet.pdf', 'method'=>'POST', 'target'=>'_blank']) !!}
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="">Sede</label>
				{!! Form::select('headquarter_id_evsg', $headquarters, null, ['class'=>'form-control', 'placeholder'=>'- Seleccione una sede -','id'=>'headquarter_id_evsg']) !!}
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label for="">Grado</label>
				{!! Form::select('grade_id_evsg', $grades, null, ['class'=>'form-control', 'id'=>'grade_id_evsg', 'placeholder'=> '- Seleccione un grado -']) !!}
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label for="">N° de Copias</label>
				{!! Form::number('copy', 0, ['class'=>'form-control']) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('sheet_evsg', [], null, ['id'=>'sheet_evsg','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-default btn-block" id="sheet_evsg_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_evsg_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_evsg_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
			<button type="button" id="sheet_evsg_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('groups[]', [], null, ['id'=>'sheet_evsg_to','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
				{!! Form::hidden('year', date('Y'), []) !!}
				{!! Form::hidden('institution_id', $institution->id, []) !!}
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-3 col-md-3">
				<div class="form-group">
					{!! Form::label('orientation', 'Orientación', []) !!}
					{!! Form::select('orientation', 
						[
							'p'=> 'Vertical',
							'l'=> 'Horizontal'
						], 
					'l', ['class'=>'form-control']) !!}
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					{!! Form::label('papper', 'Tamaño de papel', []) !!}
					{!! Form::select('papper', 
					[
						'letter'=> 'Carta',
						'legal'	=> 'Oficio',
						'a3'	=> 'A3',
						'a4'	=> 'A4',
						'a5'	=> 'A5',
					], 
					'letter', ['class'=>'form-control']) !!}
					{!! Form::hidden('group_type', 'subgroup', []) !!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center">
				{{-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#configSheetModal">
				  Configuración
				</button> --}}
				<button type="submit" class="btn btn-primary">
					Imprimir planillas
				</button>
			</div>
		</div>
	</div>
	{{-- @include('institution.partials.sheet.configSheet') --}}
	{!! Form::close() !!}
</div>