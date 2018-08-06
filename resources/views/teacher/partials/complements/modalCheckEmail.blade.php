<div class="modal fade" id="modalCheckEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		{!! Form::open(['route'=>['teacher.setting.saveEmail', $manager], 'class'=>'form-horizontal', 'method'=>'PUT', 'id'=>'formCheckEmail']) !!}
			<div class="modal-header">
				{{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
				<h4 class="modal-title" id="myModalLabel">Por favor digite su correo electronico</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="alert alert-info">
								Estamos recoletando toda la información relevante de nuestros usuario, esto con el fin para una mayor proteción y gestión de sus datos.
							</div>
						</div>
						<div class="col-md-12">
							<div id="formerrors"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								{!! Form::label('email', 'Correo electronico:', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
							    	{!! Form::text('email', $manager->address->email, ['class'=>'form-control', 'placeholder'=>'usuario@ejemplo.com']) !!}
							    </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Guardar</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>