@extends('teacher.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
		<li><a href="{{route('teacher.home')}}">Inicio</a></li>
	  	<li class="active">Evaluación</li>
	</ol>
@endsection

@section('content')
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
				               	<th></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
			  	</div>
			</div>
    	</div>
    </div>
@endsection