@extends('institution.dashboard.index')

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
			    	<h3 class="panel-title pull-left">Ver sedes</h3>
			    	<a href="{{route('headquarter.create')}}" class="btn btn-primary btn-sm pull-right">Crear Sede</a>
			  	</div>
			  	<div class="panel-body">

			  		<table class="table">
			  			<thead>
			  				<tr>
			  					<th>Nombre Sede</th>
			  					<th>Nit</th>
			  					<th>Dirección</th>
			  					<th>Telefono</th>
			  					<th>Email</th>
			  					<th></th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  				@foreach($headquarters as $headquarter)
								
								<tr>
									<td>{{ $headquarter->name }}</td>
									<td>{{ $headquarter->nit }}</td>
									<td>{{ $headquarter->address->address }}</td>
									<td>{{ $headquarter->address->phone }}</td>
									<td>{{ $headquarter->address->email }}</td>

									<td>
										<a href="{{route('headquarter.edit', $headquarter)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
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