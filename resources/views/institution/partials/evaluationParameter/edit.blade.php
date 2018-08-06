@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('evaluationParameter.index')}}">Parametros de Evaluación</a></li>
	  <li class="active">Editar</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
					<h4>Parametro de Evalucion</h4>
			  	</div>
			  	<div class="panel-body">
					<div class="container-fluid">
					{!! Form::open(['route'=>['evaluationParameter.update', $parameter], 'method'=>'put']) !!}
						@include('complements.error')
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									{!! Form::label('parameter', 'Parametros de Evaluación', []) !!}
									
									{!! Form::text('parameter', $parameter->parameter, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::label('abbreviation', 'Abreviación', []) !!}
									
									{!! Form::text('abbreviation', $parameter->abbreviation, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::label('percent', 'Porcentaje (%)', []) !!}
									
									{!! Form::text('percent', $parameter->percent, ['class'=>'form-control
									']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::label('school_year_id', 'Año lectivo', []) !!}
									
									{!! Form::select('school_year_id', $schoolyears, $parameter->school_year_id, ['class'=>'form-control', 'placeholder'=>'- Año lectivo -']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									{!! Form::label('group_type', 'Tipo de grupo', []) !!}
									
									{!! Form::select('group_type', [
										'group'	=> 'Grupo',
										'subgroup'	=>	'Subgrupo'
										], $parameter->group_type, ['class'=>'form-control']) !!}
								</div>
							</div>
						</div>
						<div class="row text-center">
							<div class="col-md-12">
								<button class="btn btn-primary">Actualizar</button>
							</div>
						</div>
					{!! Form::close() !!}
					</div>
			  	</div>
			</div>
		</div>
	</div>

	{{-- CRITERIA --}}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<h4 class="pull-left">Criterios de Evaluación</h4>
					<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalAddCriteria">
						Crear Criterio de Evaluación
					</button>
				</div>
				<div class="panel-body">
					<table class="table">
						<thead>
							<tr>
								<th>Criterio</th>
								<th>Abreviación</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
						@foreach($parameter->criterias as $criteria)
						<tr>
							<td>{{$criteria->parameter}}</td>
							<td>{{$criteria->abbreviation}}</td>
							<td>
								<a href="" class="btn btn-primary btn-sm btnCriteriaEdit" data-criteria="{{$criteria->id}}">
									<i class="fa fa-edit"></i>
								</a>

								<a href="" class="btn btn-danger btn-sm" onclick="event.preventDefault();if(confirm('¿Desea eliminar este criterio de Evaluación?')){document.getElementById('formDeleteCriteria{{$criteria->id}}').submit();}">
									<i class="fa fa-trash"></i>
								</a>

								<form action="{{route('criteria.destroy',$criteria)}}" id="formDeleteCriteria{{$criteria->id}}" method="post">
									{{csrf_field()}}
                                    {{method_field('DELETE')}}
								</form>
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>	
				
		</div>
	</div>
@include('institution.partials.evaluationParameter.addCriteria')
@include('institution.partials.evaluationParameter.editCriteria')
@endsection

@section('js')
	<script>
		$(document).ready(function(){

			$("#btnAddCriteria").click(function(e){
				e.preventDefault();

				$("#modalAddCriteria").modal('show');
			});
			// $(".table").DataTable( {
			// 	"language": {
			// 	    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
			// 	},
			// 	"info":     false,
			// 	// "order": [2],
			// 	"autoWidth": false,
		 //    });

		 // SEND DATA FORM ADD DAMILY
	    $('#formAddCriteria').submit(function(e){

	    	e.preventDefault();

	    	var that = $(this),
	    	url	=	that.attr('action');

	    	$.ajax({

	    		type: that.attr('method'),
	    		url: url,
	    		data: that.serialize(),
	    		dataType: 'json',
	    		success: function(data){
	    			
	    			if(data.state){
	    				$('#modalAddCriteria').modal('hide');
	    				window.location.reload();
	    			}

	    		},
	    		error: function(jqXhr){
	    			
	    			if( jqXhr.status === 422 )
	    			{
	    				//process validation errors here.
	    				var errors = jqXhr.responseJSON;

	    				errorsHtml = '<div class="alert alert-danger"><ul>';

	    				$.each( errors.errors , function( key, value ) {
				            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
				        });
	    				errorsHtml += '</ul></di>';

	    				$( '#formerrorsAdd' ).html( errorsHtml );

	    			}
	    		}
	    	});
	    });

	    // Edit Criteria
	    $(".btnCriteriaEdit").click(function(e){
	    	e.preventDefault();

	    	var _this = $(this),
	    		criteria_id = _this.data('criteria')
	    		form = $("#formEditCriteria")
	    		modal = $("#modalEditCriteria");

	    	// console.log(criteria_id);
	    	$.get("{{env('APP_URL')}}/api/criteria/"+criteria_id, function($response){

	    		$.each($response, function(indx, el){
	    			// console.log(indx);
	    			form.find("#"+indx).val(el);
	    			modal.modal('show');
	    		});
    		}, "json");

	    });

	    $("#formEditCriteria").submit(function(e){

	    	e.preventDefault();

	    	var form = $(this)
	    		modal = $("#modalEditCriteria");

	    	$.ajax({

	    		type: form.attr('method'),
	    		url: "{{url('institution/criteria')}}/"+form.find("#id").val(),
	    		data: form.serialize(),
	    		dataType: 'json',
	    		success: function(data){
	    			
	    			if(data.state){
	    				modal.modal('hide');
	    				window.location.reload();
	    			}

	    		},
	    		error: function(jqXhr){
	    			
	    			if( jqXhr.status === 422 )
	    			{
	    				//process validation errors here.
	    				var errors = jqXhr.responseJSON;

	    				errorsHtml = '<div class="alert alert-danger"><ul>';

	    				$.each( errors.errors , function( key, value ) {
				            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
				        });
	    				errorsHtml += '</ul></di>';

	    				$( '#formerrorsEdit' ).html( errorsHtml );

	    			}
	    		}
	    	});

	    });
	});
	</script>
@endsection