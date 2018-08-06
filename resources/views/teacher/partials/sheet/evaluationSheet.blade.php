<div class="col-md-12">
	{!! Form::open(['route'=>'teacher.sheetByPensum', 'method'=>'POST', 'target'=>'_blank']) !!}
	<div class="row">
		{{-- <div class="col-md-4">
			<div class="form-group">
				<label for="">Grupo</label>
				{!! Form::select('group_id_ev', $pensums, null, ['class'=>'form-control', 'placeholder'=>'- Seleccione un grupo -','id'=>'group_id_ev']) !!}
			</div>
		</div> --}}
		<div class="col-md-2">
			<div class="form-group">
				<label for="period_id">Periodo</label>
				{!! Form::select('period_id', $periods, null, ['class'=>'form-control','requierd'=>true]) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				<select class="form-control" multiple="true" size="8" id="sheet_ev">
					@foreach($pensums as $pensum)
						<option value="{{$pensum['id']}}" title="{{$pensum['asignature']['name'] ." ".$pensum['group']['name']}}">
							{{$pensum['asignature']['name'] ." ".$pensum['group']['name'] }}
						</option>
					@endforeach
				</select>
				{{-- {!! Form::select('sheet_ev', [], null, ['id'=>'sheet_ev','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!} --}}
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-default btn-block" id="sheet_ev_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_ev_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
			<button type="button" id="sheet_ev_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
			<button type="button" id="sheet_ev_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				{!! Form::select('pensums[]', [], null, ['id'=>'sheet_ev_to','class'=>'form-control', 'multiple'=>true, 'size'=>8]) !!}
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
					{!! Form::hidden('group_type', 'group', []) !!}
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