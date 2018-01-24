<div class="modal fade bs-example-modal-lg" id="modalSearchFamily" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">
				<div id="formerrorsFamilySearch"></div>
				<div class="container-fluid">
					{{--  SERACH FAMILY --}}
					<div id="identification" class="section_inscription">
						{{-- <div class="section_inscription__tittle">
							<h4>Datos Familiares</h4>
						</div> --}}
						{!! Form::open(['route'=>'family.search','method' => 'get', 'id'=>'formSearchFamily']) !!}
						<div class="row">
							<div class="col-md-4 col-md-offset-2">
								<div class="form-group">
								{!! Form::label('no_document', 'Número de documento') !!}
								{!! Form::text('no_document_search', null, ['class'=>'form-control','id'=>'no_document_search']) !!}
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									{!! Form::label('relationship_id', 'Parentesco') !!}
									{!! Form::select('relationship_id', $relationship_types, old('relationship_id'), ['class'=>'form-control chosen-select', 'placeholder' => 'Seleccione el parentesco']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="">dssds</label>
									<button class="btn btn-primary" style="display: block;">Consultar</button>
								</div>
							</div>
						</div>
						{!! Form::hidden('student_id', $student->id) !!}
						{!! Form::close()!!}
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive" id="contentFamilySearch">
								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				{{-- {!! Form::submit('Guardar', ['class'=>'btn btn-primary']) !!} --}}
			</div>
		</div>
	</div>
</div>