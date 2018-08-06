<div class="modal fade bs-example-modal-lg" id="modalDeleteFamily" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			{!! Form::open(['route' => 'student.index', 'method' => 'delete', 'id'=>'formDeleteFamily']) !!}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Eliminar familiar</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<p >¿Esta seguro que desea elimianr a <strong id="text_delte"></strong> de la lista de familiares?.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				{!! Form::hidden('relationship_id', null, ['id'=>'relationship_id']) !!}
				{!! Form::hidden('family_id', null, ['id'=>'family_id']) !!}
				{!! Form::hidden('student_id', null, ['id'=>'student_id']) !!}
				{!! Form::submit('Eliminar', ['class'=>'btn btn-primary']) !!}
			</div>
			{!! Form::close()!!}
		</div>
	</div>
</div>