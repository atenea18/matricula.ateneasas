@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Subgrupos</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
					<a class="btn btn-success btn-sm" style="margin-right: 3px" href="{{route('subgroup.assignment')
					}}">Asignar Subgrupos</a>

					<a class="btn btn-primary btn-sm pull-right" href="{{route('subgroup.create')}}">Crear Subrupo</a>
			  	</div>
			  	<div class="panel-body">
					@include('flash::message')
			  		<table class="table">
			  			<thead>
			  				<tr>
								<th>Sede</th>
								<th>Grado</th>
			  					<th>Subgrupo</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				@foreach($subgroups as $key => $subgroup)
								<tr>
									<td>{{ $subgroup->headquarter->name }}</td>
									<td>{{ $subgroup->grade->name}}</td>
									<td>{{ $subgroup->name }}</td>
									<td>
										<a href="{{route('subgroup.edit', $subgroup)}}" class="btn btn-primary btn-sm">
											<i class="fa fa-edit"></i>
										</a>
										<a 	href="" 
											class="btn btn-danger btn-sm"
											onclick="event.preventDefault();
													document.getElementById('formDelsg{{$subgroup->id}}').submit();">
											<i class="fa fa-trash"></i>
										</a>
										{!! Form::open(['route'=>['subgroup.destroy', $subgroup->id], 'method'=>'DELETE', 'id'=>"formDelsg{$subgroup->id}"]) !!}
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