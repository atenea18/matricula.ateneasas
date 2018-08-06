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
					<li role="presentation" class="active">
						<a href="#evaluationTab" aria-controls="evaluationTab" role="tab" data-toggle="tab">
							Evaluación
						</a>
					</li>
					@if($teacher->isDirector())
					<li role="presentation">
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
			<div role="tabpanel" class="tab-pane active" id="evaluationTab">
				@include('teacher.partials.evaluation.home')
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')

@endsection