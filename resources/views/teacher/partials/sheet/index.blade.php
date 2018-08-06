@extends('teacher.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('teacher.home')}}">Inicio</a></li>
        <li class="active">Planillas</li>
    </ol>
@endsection

@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body" style="padding: 0">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#evaluationSheet" aria-controls="evaluationSheet" role="tab" data-toggle="tab">
							Planillas de evalución
						</a>
					</li>
					<li role="presentation">
						<a href="#attendanceSheet" aria-controls="attendanceSheet" role="tab" data-toggle="tab">
							Planillas de asistencia
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="panel panel-default">
				<div class="panel-body">
					<div class="conatiner-fluid">
						<div class="row">
							<!-- Tab panes -->
							<div class="tab-content" style="padding: 0">
								<div role="tabpanel" class="tab-pane active" id="evaluationSheet">
									@include('teacher.partials.sheet.evaluationSheet')
								</div>
								<div role="tabpanel" class="tab-pane" id="attendanceSheet">
									@include('teacher.partials.sheet.attendanceSheet')
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
	<script>
		$(document).ready(function(){
			// Multi Select
			$('#sheet_ev').multiselect({
				search: {
					left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
						 
					right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
					 
				}
			});
		})
	</script>
@endsection