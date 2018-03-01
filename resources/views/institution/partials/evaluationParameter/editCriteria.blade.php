<div class="modal fade bs-example-modal-lg" id="modalEditCriteria" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="" id="formEditCriteria" method="POST">
				{{csrf_field()}}
	    		{{method_field('PUT')}}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Crear Criterio de evaluación</h4>
			</div>
			<div class="modal-body">
				<div id="formerrorsEdit"></div>
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
				{!! Form::hidden('id', null, ['id'=>'id']) !!}
				{!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
			</div>
			</form>
		</div>
	</div>
</div>