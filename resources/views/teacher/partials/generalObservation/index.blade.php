@extends('teacher.dashboard.index')

@section('css')
<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
<ol class="breadcrumb">
	<li><a href="{{route('teacher.home')}}">Inicio</a></li>
	<li class="active">Observaciones Generales</li>
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
					<li role="presentation">
						<a href="{{route('generalReport.index')}}">
							Informe General de Periodo
						</a>
					</li> 
					<li role="presentation" class="active">
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
									<button type="button" class="btn btn-primary" id="btnAddObservation">
										Agregar Observación General
									</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table">
										<thead>
											<tr>
												<th>Estudiante</th>
												<th>Grupo</th>
												<th>Periodo</th>
												<th>Descripción</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											@foreach($enrollments as $key => $enrollment)
												@if(!is_null($enrollment->observations->first()))
													@foreach($enrollment->observations as $key=> $observation)
													<tr>
														<td> 
															{{$enrollment->student->fullNameInverse}} 
														</td>
														<td>
															{{$enrollment->group->first()->name}}
														</td>
														<td>
															{{$observation->periodWorkingday->period->name}}
														</td>
														<td>
															{!! substr(strip_tags($observation->observation), 0, 20) !!}
														</td>
														<td>
															<a href="{{route('generalObservation.show', $observation->id)}}" class="btn btn-primary btn-sm" data-observatione="{{$observation->id}}">
																<i class="fa fa-edit"></i>
															</a>
															<a href="#" class="btn btn-danger btn-sm" data-observationd="{{$observation->id}}">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>
													@endforeach
												@endif
											@endforeach
										</tbody>
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
@include('teacher.partials.generalObservation.create')
@include('teacher.partials.generalObservation.edit')
@include('teacher.partials.generalObservation.delete')
@endsection

@section('js')
	<script>
		$(document).ready(function(){

			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});

			// Datatables
			$(".table").DataTable( {
				"language": {
				    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
				},
				"info":     false,
				// "order": [2],
				"autoWidth": false,
		    });

			// Multi Select
			$('#selectGO').multiselect({
				keepRenderingSort: true,	
				search: {
					left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
						 
					right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
					 
				}
			});

			$("#btnAddObservation").click(function(){

				$("#modalAddObservation").modal({
					keyboard: false,
					backdrop: 'static'
				});
			});

			CKEDITOR.replace( 'observation_create' );
			CKEDITOR.replace( 'observation_edit' );


			$("#group_og").change(function(){

				var select = $(this);

				$.ajax({
					url: "{{env('APP_URL')}}/api/periodByGroup/"+this.value,
					method: "GET",
					beforeSend:function(){
						
						select.prop('disabled',true);
						$("#period_working_day_id").empty();
					},
					success:function(data){

						select.prop('disabled',false);

						var html = "<option>- Seleciona un periodo -</option>";
						$.each(data.data, function(indx, el){
							html += "<option value='"+el.id+"'>"+el.period.name+"</option>";
						});

						$("#period_working_day_id").html(html);
					},
					error:function(xhr){
						select.prop('disabled',false)
						
					}
				});
			});

			$("#period_working_day_id").change(function(){

				var group_id = $("#group_og").val();

				$.ajax({
					url: "{{env('APP_URL')}}/api/group/"+group_id+"/enrollments",
					method: "GET",
					// data: {group: group_id},
					beforeSend:function(){},
					success:function(data){

						var html = '';

						$.each(data.data, function(indx, el){
							html += "<option value='"+el.id+"'>"+el.student.last_name+" "+el.student.name+"</option>";
						});

						$("#selectGO").html(html);
					},
					error:function(xhr){
						
					}
				});
			});

			$("#formAddGO").submit(function(e){

				e.preventDefault();

				var form = $(this)
					period_working_day_id = $("#period_working_day_id").val(),
					enrollments = $("#selectGO_to").val(),
					data = CKEDITOR.instances.observation_create.getData();

				$.ajax({
					url: form.attr('action'),
					method: form.attr('method'),
					data: {
						period_working_day_id,
						enrollments,
						observation : data
					},
					beforeSend:function(){},
					success:function(data){
						window.location.reload();
					},
					error:function(xhr){}
				});
			});

			// Obtenemos la oobservación a editar
			$("a[data-observatione]").click(function(e){

				e.preventDefault();

				var btn = $(this),
					form = $("#modalEditObservation");

				$.ajax({
					url: btn.attr('href'),
					method: "GET",
					beforeSend:function(){
						btn.empty().html("<i class='fas fa-spinner fa-pulse'></i>");
					},
					success:function(data){

						btn.empty().html("<i class='fa fa-edit'></i>");

						$("#modalEditObservation").modal({
							keyboard: false,
							backdrop: 'static'
						});

						form.find("#myModalLabel").text("Editar Observaciones periodo "+data.data.period_workingday.period.name);
						form.find("#id").val(data.data.id);
						form.find("#textStudent").text(data.data.enrollment.student.name+" "+data.data.enrollment.student.last_name);
						CKEDITOR.instances['observation_edit'].setData(data.data.observation);
					},
					error:function(xhr){
						btn.empty().html("<i class='fa fa-edit'></i>");
					}
				});
			});

			// actualizamos las observaciones generales
			$("#formEditGO").submit(function(e){

				e.preventDefault();

				var form = $(this),
					observation = CKEDITOR.instances.observation_edit.getData();

					form.find("#observation_edit").val(observation);

				$.ajax({
					url: "{{url('teacher/generalObservation')}}/"+form.find("#id").val(),
					method: form.attr('method'),
					data: form.serialize(),
					beforeSend:function(){
						form.find('button').prop('disabled',true);
					},
					success:function(data){
						form.find('button').prop('disabled',false);
						window.location.reload();
					},
					error:function(xhr){
						form.find('button').prop('disabled',false);
						
					}
				});
			});

			// Obtenemos la oobservación a eliminar
			$("a[data-observationd]").click(function(e){

				e.preventDefault();

				var btn = $(this),
					form = $("#formDelGO");

				$("#modalDelObservation").modal({
					keyboard: false,
					backdrop: 'static'
				});

				form.find("#id").val(btn.data('observationd'));
				form.find("#textStudent").text("¿Esta seguro de eliminar esta observación ?");
			});

			$("#formDelGO").submit(function(e){

				e.preventDefault();

				var form = $(this);

				$.ajax({
					url: "{{url('teacher/generalObservation')}}/"+form.find("#id").val(),
					method: form.attr('method'),
					data: form.serialize(),
					beforeSend:function(){
						form.find('button').prop('disabled',true);
					},
					success:function(data){
						form.find('button').prop('disabled',false);
						window.location.reload();
					},
					error:function(xhr){
						form.find('button').prop('disabled',false);
						
					}
				});
			})
		})
	</script>
@endsection