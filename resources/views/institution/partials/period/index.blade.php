@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Periodo Académico</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">

					<a class="btn btn-primary btn-sm pull-right" href="{{route('period.create')}}">
						Crear Periodo Académico
					</a>

			  	</div>
			  	<div class="panel-body">
					@include('flash::message')
			  		<table class="table">
			  			<thead>
			  				<tr>
			  					{{-- <th>#</th> --}}
								<th>Periodo</th>
								<th>Pocentaje (%)</th>
			  					<th>Jornada</th>
			  					<th>Año lectivo</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				@foreach($periods as $key => $period)
								<tr>
									<td>{{ $period->period->period }}</td>
									<td>{{ $period->percent }}</td>
									<td>{{ $period->workingday->name }}</td>
									<td>{{ $period->schoolyear->year }}</td>
									<td>
										<a href="{{route('period.edit', $period)}}" class="btn btn-primary btn-sm">
											<i class="fa fa-edit"></i>
										</a>
										<a href="" class="btn btn-danger btn-sm">
											<i class="fa fa-trash"></i>
										</a>
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
	<script>
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
	</script>
@endsection