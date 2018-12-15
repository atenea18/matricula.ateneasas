@extends('institution.dashboard.index')


@section('breadcrums')
<ol class="breadcrumb">
	<li class="active">Boletin</li>
</ol>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h4>Certificado academico</h4>
			</div>
			<div class="panel-body">
				{!! Form::open(['route'=> 'certificate.create', 'method'=>'post', 'target'=>'_blank']) !!}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								{!! Form::label('headquarter', 'Sede', []) !!}
								{!! Form::select('headquarter', $headquarters, null, ['class'=>'form-control', 'placeholder'=>'- Seleccione un a sede -']) !!}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								{!! Form::label('group', 'Grupos', []) !!}
								{!! Form::select('group', [], null, ['class'=>'form-control']) !!}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="">Fecha</label>
								<input type="date" name="fecha" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group">
								<select name="" id="selectStudent" class="form-control" multiple="multiple" size="13">
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-default btn-block" id="selectStudent_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
							<button type="button" id="selectStudent_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
							<button type="button" id="selectStudent_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
							<button type="button" id="selectStudent_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<select name="enrollments[]" id="selectStudent_to" class="form-control" multiple="multiple" size="13">
									<?php

									?>
								</select>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="form-group">
								<label class="radio-inline">
									<input type="checkbox" name="areasDisabled"> Desactivar Areas
								</label>
								<label class="radio-inline" title="Incluir Informe final">
									<input type="checkbox" name="decimals" checked> Incluir Decimales
								</label>
							</div>
						</div>
					</div>
					<div class="form-group text-center">
						<button type="button" name="btn_config_msgs" class="btn btn-default" data-toggle="modal" data-target="#mFirmas">Configurar firmas</button>
						<input type="submit" name="btn_p_superacion" class="btn btn-primary" value="Crear certificado">
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@include('institution.partials.certificate.firms')
@endsection

@section('js')
<script>
	$(document).ready(function(){

		$('#selectStudent').multiselect({
				search: {
				 
				left: '<input type="text" name="ql" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
				 
				right: '<input type="text" name="qr" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
				 
				}
			});

		$("#headquarter").change(function(e){

			// Se obtiene los grupos de la sede selecionada
			$.get("{{env('APP_URL')}}/api/headquarter/"+this.value+"/groups", function(data){

				var html = "<option>- Seleccione un grupo -</option>";

				$.each(data.data, function(indx, ele){
					
					html += "<option value='"+ele.id+"'>"+ele.name+"</option>";

				});

				$("#group").empty().html(html);

			}, 'json');
		});

		$("#group").change(function(){

			// Se obtiene los alunos del grupo selecionado
			$.get("{{env('APP_URL')}}/api/group/"+this.value+"/enrollments", function(data){

				var html = "";

				$.each(data.data, function(indx, ele){
					
					html += "<option value='"+ele.id+"'>"+ele.student.last_name+' '+ele.student.name+"</option>";

				});

				$("#selectStudent").empty().html(html);
				$("#selectStudent_to").empty();

			}, 'json');

			// Se obtiene los periodos de la sede selecionada
			$.get("{{env('APP_URL')}}/api/periodByGroup/"+this.value, function(data){

				var html = "<option>- Seleccione un periodo -</option>";

				$.each(data.data, function(indx, ele){
					
					// console.log(ele);
					html += "<option value='"+ele.id+"'>"+ele.period.name+"</option>";

				});

				$("#period").empty().html(html);

			}, 'json');
		});

		$("#mFirmas").on('show.bs.modal', function(){
			var _that = $(this);
			$.get("{{route('showFirms', $institution->id)}}", function(data, status, xhr){
				
				if(data.data != null){
					console.log(data);
					$.each(data.data, function(ind, item){
						var elem = $('#'+ind);
						if(elem.length > 0)
						{
							elem.val(item);
						}
					});
				}else{
					console.log('nulo');
				}
			}, 'json');
		});

		$("#formFirmas").submit(function(e){
			e.preventDefault();
			var _that = $(this);
			$.ajax({
				url: "{{route('certificate.saveFirms')}}",
				method: 'POST',
				data: _that.serialize(),
				beforeSend: function(){
					_that.find("#btncancelMessage").prop('disabled', true);
					_that.find("#btnMessageSave").text('Guardando..').prop('disabled', true);
				},
				success: function(data){
					
					_that.find("#btncancelMessage").prop('disabled', false);
					_that.find("#btnMessageSave").text('Guardar').prop('disabled', false);
					$("#mFirmas").modal('hide');
				}
			});
		});
	});
</script>
@endsection