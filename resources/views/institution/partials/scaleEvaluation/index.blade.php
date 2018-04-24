@extends('institution.dashboard.index')


@section('breadcrums')
<ol class="breadcrumb">
	<li><a href="">Configuración</a></li>
	<li class="active">Escala Valorativa</li>
</ol>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">

				<h5>Escala Valorativa</h5>

				<a class="btn btn-primary btn-sm pull-right" href="{{route('scaleEvaluation.create')}}">
					Crear Escala Valorativa
				</a>

			</div>
			<div class="panel-body">
				@include('flash::message')
				<table class="table" id="tableIndex">
					<thead>
						<tr>
							{{-- <th>#</th> --}}
							<th>Nombre</th>
							<th>Abreviación</th>
							<th>Rango inicia</th>
							<th>Rango final</th>
							<th>Año lectivo</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($scaleEvaluations as $key => $scaleEvaluation)
						<tr>
							<td>{{ $scaleEvaluation->name }}</td>
							<td>{{ $scaleEvaluation->abbreviation }}</td>
							<td>{{ $scaleEvaluation->rank_start }}</td>
							<td>{{ $scaleEvaluation->rank_end }}</td>
							<td>{{ $scaleEvaluation->schoolyear->year }}</td>
							<td>
								<a href="{{route('scaleEvaluation.edit', $scaleEvaluation)}}" class="btn btn-primary btn-sm">
									<i class="fa fa-edit"></i>
								</a>
								<a 	href="" class="btn btn-sm btn-danger" 
								onclick="event.preventDefault();
								document.getElementById('formDelSEval{{$scaleEvaluation->id}}').submit();">
								<i class="fa fa-trash"></i>
							</a>

							{!! Form::open(['route'=>['scaleEvaluation.destroy', $scaleEvaluation], 'method'=>'DELETE', 'id'=>"formDelSEval{$scaleEvaluation->id}"]) !!}
							{!! Form::close() !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
@include('institution.partials.period.enableAndDisablePeriod')
@endsection

@section('js')
<script>
	$(document).ready(function(){

		$("#tableIndex").DataTable( {
			"language": {
				"url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
			},
			"info":     false,
			"autoWidth": false,
		});	
	});
</script>
@endsection