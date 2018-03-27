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
		<div class="panel panel-default" style="margin-bottom: 0">
			<div class="panel-body" style="padding: 0">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="">
						<a href="{{route('teacher.evaluation')}}">
							Evaluación
						</a>
					</li>
					@if($teacher->isDirector())
					<li role="presentation" class="active">
						<a href="{{route('generalReport.index')}}">
							Informe General de Periodo
						</a>
					</li> 
					<li role="presentation">
						<a href="{{route('generalObservation.index')}}">
							Observaciones Generales
						</a>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<!-- Tab panes -->
		<div class="tab-content" style="padding: 0">
			@if($teacher->isDirector())
			<div role="tabpanel" class="tab-pane active" id="generalObservationTab">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="container-fluid">
							<div class="row" style="margin-bottom: 2em;">
								<div class="col-md-12 text-center">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addObservation">
										Agregar Informe General de Periodo
									</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table">
										<thead>
											<tr>
												<th>Estudiante</th>
												<th>Periodo</th>
												<th>Descripción</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

@endsection

@section('js')

@endsection

{{-- @include('teacher.partials.generalObservation.create') --}}