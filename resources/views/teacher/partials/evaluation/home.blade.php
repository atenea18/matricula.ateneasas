<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h2 class="panel-title pull-left">Grupos</h2>
			</div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>N°</th>
							<th>Grupo</th>
							<th>Asignatura</th>
							<th>Tipo</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						@foreach($pemsun as $key => $pensum)
						<tr>
							<td>{{ ($key+1) }}</td>
							<td>{{ $pensum->group->name }}</td>
							<td>{{ $pensum->asignature->name }}</td>
							<td>{{ $pensum->subjectType->name }}</td>
							<td>
								<div class='btn-group' role='group'>
									<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
										Evaluación
										<span class='caret'></span>
									</button>
									<ul class='dropdown-menu'>
										<li>
											<a href=''>Evaluar Periodo
											</a>
										</li>
										<li>
											<a href=''>Superaciones
											</a>
										</li>
										<li>
											<a href=''>Evaluar Periodo Pendiente
											</a>
										</li>
										<li><a href=''>Refuerzo Academico</a></li>
									</ul>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>