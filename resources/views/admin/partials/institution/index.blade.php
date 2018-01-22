@extends('admin.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Sedes</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			    	<h3 class="panel-title pull-left">Ver Institución</h3>
			    	<a href="{{route('institution.create')}}" class="btn btn-primary btn-sm pull-right">Crear Sede</a>
			  	</div>
			  	<div class="panel-body">

			  		<table class="table">
			  			<thead>
			  				<tr>
			  					<th></th>
			  					<th>Nombre</th>
			  					<th>Codigo Dane</th>
			  					<th>Email</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				@foreach($institutions as $institution)
								<tr>
									<td>
										<img src="{{asset('storage')."/".$institution->picture}}" width="40" alt="">
									</td>
									<td>{{ $institution->name }}</td>
									<td>{{ $institution->dane_code }}</td>
									<td>{{ $institution->email }}</td>

									<td>
										<a href="{{route('institution.edit', $institution)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
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