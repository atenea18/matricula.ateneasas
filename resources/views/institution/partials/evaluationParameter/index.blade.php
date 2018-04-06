@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Parametros de Evaluación</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">

					<a class="btn btn-primary btn-sm pull-right" href="{{route('evaluationParameter.create')}}">Crear Parametros de Evalución</a>
			  	</div>
			  	<div class="panel-body">

			  		<table class="table">
			  			<thead>
			  				<tr>
								<th>Parametros de Evaluación</th>
								<th>Abreviación</th>
			  					<th>Porcentaje</th>
			  					<th>Año</th>
			  					<th>Acción</th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  			@foreach($parameters as $parameter)
						<tr>
							<td>{{ $parameter->parameter }}</td>
							<td>{{ $parameter->abbreviation }}</td>
							<td>{{ $parameter->percent}}</td>
							<td>{{ $parameter->schoolYear->year}}</td>
							<td>
								<a href="{{route('evaluationParameter.edit',$parameter)}}" class="btn btn-primary btn-sm">
									<i class="fa fa-edit"></i>
								</a>
								<a href="" class="btn btn-sm btn-danger" onclick="event.preventDefault();if(confirm('¿Desea eliminar este Parametro de Evaluación?')){document.getElementById('formDelEvalParameter{{$parameter->id}}').submit();}">
									<i class="fa fa-trash"></i>
								</a>
								{!! Form::open(['route'=>['evaluationParameter.destroy', $parameter], 'method'=>'DELETE', 'id'=>"formDelEvalParameter{$parameter->id}"] ) !!}
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
@endsection

@section('js')
	{{-- <script>
		$(document).ready(function(){

			$(".table").DataTable( {
				"language": {
				    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
				},
				"info":     false,
				// "order": [2],
				"autoWidth": false,
		    });
		});
	</script> --}}
@endsection