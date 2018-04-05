<!-- Modal -->
<div class="modal fade" id="configSheetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Configurar Planilla</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-offset-2"></div>
						<div class="col-md-4">
							{!! Form::label('orientation', 'Orientación', []) !!}
							{!! Form::select('orientation', 
							[
								'p'=> 'Vertical',
								'l'=> 'Horizontal'
							], 
							'l', ['class'=>'form-control']) !!}
						</div>
						<div class="col-md-4">
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
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Guardar</button>
				{{-- <button type="button" class="btn btn-primary">Guardar</button> --}}
			</div>
		</div>
	</div>
</div>