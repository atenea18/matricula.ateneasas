<table class="table" id="familySearchTable">
	<thead>
		<tr>
			<th>Nombres</th>
			<th>Apellidos</th>
			<th>Dirección</th>
			<th>Telefono</th>
			<th>Seleccionar</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ $identification->family->name}}</td>
			<td>{{ $identification->family->last_name}}</td>
			<td>{{ $identification->family->address->address}}</td>
			<td>{{ $identification->family->address->phone}}</td>
			<td>
				<a href="#" class="btn btn-primary btn_select">
					<i class="fa fa-check"></i>
				</a>

				<form action="{{route('student.attachFamily')}}" method="POST" id="SendAttachFamily">
					{{csrf_field()}}
					{!! Form::hidden('student_id', $student_id, []) !!}
					{!! Form::hidden('relationship_id', $relationship_id, []) !!}
					{!! Form::hidden('family_id', $identification->family->id, []) !!}
				</form>
			</td>
		</tr>
	</tbody>
</table>