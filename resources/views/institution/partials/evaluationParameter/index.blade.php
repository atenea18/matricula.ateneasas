@extends('institution.dashboard.index')

@section('css')
	
	<style>
		.table td{
			font-size: 12px;
		}
	</style>
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Parametros de Evaluación</li>
	</ol>
@endsection

@section('content')
	<div class="row text-center" style="margin-bottom: 1em;">
		<div class="col-md-12">
			<a class="btn btn-primary" href="{{route('evaluationParameter.create')}}">Crear Parametros de Evalución</a>
		</div>
	</div>
    <div class="row">
    	{{-- Table Groups --}}
    	<div class="col-md-6">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			  		<h5>Parametros de Evalaución (GRUPOS)</h5>
			  	</div>
			  	<div class="panel-body">
					<div class="table-responsive">
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
				  			@foreach($parameters->where('group_type', '=', 'group') as $parameter)
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

		{{-- Table subgroups --}}
		<div class="col-md-6">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			  		<h5>Parametros de Evalaución (SUBGRUPOS)</h5>
			  	</div>
			  	<div class="panel-body">
					<div class="table-responsive">
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
				  			@foreach($parameters->where('group_type', '=', 'subgroup') as $parameter)
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