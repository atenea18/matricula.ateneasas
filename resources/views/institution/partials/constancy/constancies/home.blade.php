<div class="col-md-12">
	<div class="heading clearfix">
		<h4 class="pull-left">Constancias</h4>
		<a href="{{route('constancy.create')}}" class="btn btn-primary btn-sm pull-right">Crear constancia</a>
	</div>
	<table class="table ">
		<thead>
			<tr>
				<th>Tipo de Constancia</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody>
			@foreach($constancies as $key => $constancy)
			<tr>
				<td>
					{{ ucfirst($constancy->type->name) }}
				</td>
				<td>
					<a href="{{route('constancy.edit', $constancy)}}" class="btn btn-primary btn-sm">
						<i class="fa fa-edit"></i>
					</a>
					{{-- &nbsp; --}}
					<a href="" class="btn btn-danger btn-sm">
						<i class="fa fa-trash"></i>
					</a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>