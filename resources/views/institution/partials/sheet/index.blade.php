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
			        		<a href="#attendance_sheet" aria-controls="attendance_sheet" role="tab" data-toggle="tab">Planilla de asistencia</a>
			        	</li>
					    <li role="presentation">
					    	{{-- <a href="#academic_info" aria-controls="academic_info" role="tab" data-toggle="tab">Inf. Académica</a> --}}
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
							<div class="tab-content">
					        	{{-- PERSONAL INFORMATION --}}
							    <div role="tabpanel" class="tab-pane active" id="attendance_sheet">
							    	@include('institution.partials.sheet.attendance.home')
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

						console.log(options);
						$( '#sheet_as' ).html( options );

					}, "json");
				}	
			});

			// Multi Select
			$('#sheet_as').multiselect({
				search: {
					left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
						 
					right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
					 
				}
			});
			
		});
	</script>
@endsection