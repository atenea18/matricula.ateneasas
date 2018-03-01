<div class="modal fade bs-example-modal-lg" id="modalAddCriteria" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			{!! Form::open(['route' => 'criteria.store', 'method' => 'post', 'id'=>'formAddCriteria']) !!}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Crear Criterio de evaluación</h4>
			</div>
			<div class="modal-body">
				<div id="formerrorsAdd"></div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('parameter', 'Criterio', []) !!}
								{!! Form::text('parameter', null, ['class'=>'form-control']) !!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('abbreviation', 'Abreviación', []) !!}
								{!! Form::text('abbreviation', null, ['class'=>'form-control']) !!}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				{!! Form::hidden('evaluation_parameter_id', $parameter->id, []) !!}
				{!! Form::submit('Guardar', ['class'=>'btn btn-primary']) !!}
			</div>
			{!! Form::close()!!}
		</div>
	</div>
</div>