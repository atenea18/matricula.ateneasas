@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Planillas</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	{{-- <div class="panel-heading clearfix">

			  	</div> --}}
			  	<div class="panel-body" style="padding: 0">
					<ul class="nav nav-tabs" role="tablist">
			        	<li role="presentation" class="active">
			        		<a href="#attendance_sheet" aria-controls="attendance_sheet" role="tab" data-toggle="tab">Asistencia</a>
			        	</li>
					    <li role="presentation">
					    	<a href="#evaluation_sheet" aria-controls="evaluation_sheet" role="tab" data-toggle="tab">Aux. de Evaluación</a>
					    </li>
					    {{-- <li role="presentation">
					    	<a href="#evaluation_sheet_subgroup" aria-controls="evaluation_sheet_subgroup" role="tab" data-toggle="tab">P. Aux. Subgrupos</a>
					    </li> --}}
					    <li role="presentation">
					    	<a href="#attendance_teacher" aria-controls="attendance_teacher" role="tab" data-toggle="tab">Asistencia Docentes</a>
					    </li>
					    <li role="presentation">
					    	<a href="#attendance_parents" aria-controls="attendance_parents" role="tab" data-toggle="tab"> Asistencia Acudientes</a>
					    </li>
					    {{-- <li role="presentation">
					    	<a href="#sheet_teacher" aria-controls="sheet_teacher" role="tab" data-toggle="tab">Docentes</a>
					    </li> --}}
			        </ul>
			  	</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="conatiner-fluid">
						<div class="row">
							<div class="tab-content">
					        	{{-- PERSONAL INFORMATION --}}
							    <div role="tabpanel" class="tab-pane active" id="attendance_sheet">
							    	@include('institution.partials.sheet.attendance.student')
							    </div>
							    <div role="tabpanel" class="tab-pane" id="evaluation_sheet">
							    	@include('institution.partials.sheet.evaluation.home')
							    </div>
							    <div role="tabpanel" class="tab-pane" id="evaluation_sheet_subgroup">
							    	@include('institution.partials.sheet.evaluation.subgroup')
							    </div>
							    <div role="tabpanel" class="tab-pane" id="attendance_teacher">
							    	@include('institution.partials.sheet.attendance.teacher')
							    </div>
							    <div role="tabpanel" class="tab-pane" id="attendance_parents">
							    	@include('institution.partials.sheet.attendance.parent')
							    </div>
							    <div role="tabpanel" class="tab-pane" id="sheet_teacher">
							    	@include('institution.partials.sheet.attendance.teacherStudent')
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
			
			// Planillas de asistencias
			$("#headquarter_id_as, #working_day_id_as, #grade_id_as").change(function(){

				var headquarter = $("#headquarter_id_as").val(),
					workinDay = $("#working_day_id_as").val(),
					grade = $("#grade_id_as").val();

				if(headquarter != '' && workinDay != '' && grade != '')
				{
					$.get("{{env('APP_URL')}}/api/headquarter/"+headquarter+"/"+workinDay+"/"+grade+"/getGroup", function(data){
						
						var options = '';
						$.each(data.data, function(indx, el){
							
							options += '<option value="'+el.id+'">' + el.name + '</option>';
						});

						
						$( '#sheet_as' ).html( options );

					}, "json");
				}	
			});

			// Planillas de evaluacion
			$("#headquarter_id_ev, #working_day_id_ev, #grade_id_ev").change(function(){

				var headquarter = $("#headquarter_id_ev").val(),
					workinDay = $("#working_day_id_ev").val(),
					grade = $("#grade_id_ev").val();

				if(headquarter != '' && workinDay != '' && grade != '')
				{
					$.get("{{env('APP_URL')}}/api/headquarter/"+headquarter+"/"+workinDay+"/"+grade+"/getGroup", function(data){
						
						var options = '';
						$.each(data.data, function(indx, el){
							
							options += '<option value="'+el.id+'">' + el.name + '</option>';
						});

						
						$( '#sheet_ev' ).html( options );

					}, "json");
				}	
			});

			// Planillas de evaluacion
			$("#headquarter_id_evsg, #grade_id_evsg").change(function(){

				var headquarter = $("#headquarter_id_evsg").val(),
					grade = $("#grade_id_evsg").val();

				if(headquarter != '' && grade != '')
				{
					$.get("{{env('APP_URL')}}/api/headquarter/"+headquarter+"/"+grade+"/getSubgroups", function(data){
						
						var options = '';
						$.each(data.data, function(indx, el){
							
							options += '<option value="'+el.id+'">' + el.name +' </option>';
						});

						$( '#sheet_evsg' ).html( options );

					}, "json");
				}	
			});

			// Multi Select
			$('#sheet_as, #sheet_ev, #sheet_evsg, #sheet_pa, #sheet_pa_teacher').multiselect({
				search: {
					left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
						 
					right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
					 
				}
			});
			
			// 
			$("#print_type").change(function(){

				var value = this.value;

				if(value == 'headquarter')
				{
					$("#group_id_ta").prop('disabled',true);
				}
				else
				{
					$("#group_id_ta").prop('disabled',false);
				}
			});

			// 
			$("#headquarter_id_ta").change(function(){

				var headquarter = this.value;

				$.get("{{env('APP_URL')}}/api/headquarter/"+headquarter+"/groups", function(data){
						
					var options = '';
					$.each(data.data, function(indx, el){
						
						options += '<option value="'+el.id+'">' + el.name + '</option>';
					});

					
					$( '#group_id_ta' ).html( options );

				}, "json");
			});

			// 
			$("#headquarter_id_pa, #grade_id_pa").change(function(){

				var headquarter = $("#headquarter_id_pa").val(),
					grade = $("#grade_id_pa").val();

				if(headquarter != '' && grade != ''){
					$.get("{{env('APP_URL')}}/api/headquarter/"+headquarter+"/"+grade+"/groups", function(data){
							
						var options = '';
						$.each(data.data, function(indx, el){
							
							options += '<option value="'+el.id+'">' + el.name + '</option>';
						});
						
						$( '#sheet_pa' ).html( options );

					}, "json");
				}
			});

			// 
			$("#teacher_id_sheet").change(function(){

				var teacher = this.value;

				$.get("/api/teacher/"+teacher+"/asignatures/{{date('Y')}}", function(data){
						
					var options = '';
					$.each(data.data, function(indx, el){
						
						options += '<option value="'+el.group.id+'">' + el.group.name +" - "+el.asignature.name+ '</option>';
					});

					
					$( '#sheet_pa_teacher' ).html( options );

				}, "json");
			});
		});
	</script>
@endsection