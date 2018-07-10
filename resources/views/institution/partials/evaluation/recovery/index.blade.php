@extends('institution.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
    <style type="text/css">
    	.table .form-control {
		    width: 50%;
		    height: 26px;
		}
    </style>
@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('institution.home')}}">Inicio</a></li>
        <li class="active"><a href="{{route('group.index')}}">Grupos</a></li>
        <li>Supereaciones</li>
    </ol>
@endsection

@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">Superaciones</h5>
			</div>
			<div class="panel-body">
				<div class="conatiner-fluid">
					<div class="row">
						<div class="col-md-6">
							<h5>{{ $group->name}}</h5>
						</div>
						<div class="col-md-3">
							{!! Form::label('asignature_id', 'Asignatura', []) !!}
							{!! Form::select('asignature_id', $pensums, null, ['class'=>'form-control', 'placeholder'=>'Seleccionar una asignatura', 'id'=>'asignature_id']) !!}
						</div>
						<div class="col-md-3">
							{!! Form::label('period_id', 'Periodo', []) !!}
							{!! Form::select('period_id', $periods, null, ['class'=>'form-control', 'placeholder'=>'Seleccionar un periodo', 'id'=>'period_id']) !!}
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th>Apellidos y Nombres</th>
											<th>Superación</th>
											<th>Nota Superación</th>
											<th>Nota periodo</th>
											{{-- <th id="periodNumber">Periodo</th> --}}
										</tr>
									</thead>
									<tbody id="tableBody"></tbody>
								</table>
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

			$("#period_id, #asignature_id").change(function(){

				var period_id = $("#period_id").val(),
					asignature_id = $("#asignature_id").val()
					group_id = {{$group->id}};

				if(period_id != '' && asignature_id != '') {
					$.ajax({
						url: "/api/recovery/"+group_id+"/"+asignature_id+"/"+period_id+"/students",
						method: "get",
						dataType: "json",
						beforeSend: function(){
							$("#tableBody").empty().html("<tr><td colspan='5' class='text-center'><i class='fas fa-spinner fa-pulse fa-3x'></i></td></tr>")
						},
						success: function(data){
							// 
							var html = '';

							$.each(data.data, function(indx, ele){

								html += "<tr>";

								html += "<td>"+indx+"</td>";
								html += "<td>"+ele.name_student+"</td>";
								html += "<td><input type='text' class='form-control' data-id='"+ele.note_final_id+"' data-old='"+ele.value+"'><span class='hide'>Cargando...</span></td>";
								html += "<td><span>"+( (ele.overcoming == null) ? 0 : ele.overcoming) +"</span></td>";
								html += "<td><span>"+ele.value+"</span></td>";
								html += "</tr>";
							});

							$("#tableBody").empty().html(html);

							bindInput();
							blurInput();
						},
						error: function(xhr){
							console.log(xhr);
						}
					});
				}
				
			});

			var bindInput = function(){
				$(".table input").keydown(function(event){

			       	if(event.which == 40){
			       		var ele = $(this).parent().parent().next().find("input");
			       		ele.focus();
			       	}else if(event.which == 38){
			       		var ele = $(this).parent().parent().prev().find("input");
			       		ele.focus();
			      	}
			    });
			}

			var blurInput = function(){

				$(".table input").focus(function(){

					this.select();

					$(this).blur(function(){
						var that = $(this),
							value = that.data('old'),
							overcoming = that.val().replace(',', '.'),
							id = that.data('id'),
							group_id = {{$group->id}};

						if( (overcoming != value) && overcoming > 0){

							$.ajax({
								url: "/mix/recovery/"+id,
								method: "PUT",
								data: { group_id, id, overcoming, value},
								beforeSend:function(){
									that.parent().find('span').removeClass('hide');
									that.prop('disabled',true);
								},
								success:function(data){
									that.parent().find('span').addClass('hide');
									that.parent().next().find('span').text(overcoming);
									that.prop('disabled',false);
								},
								error:function(xhr){
									that.parent().find('span').addClass('hide');
									that.prop('disabled',false);

									if(xhr.status == 422){
										toastr.error(xhr.responseJSON.message);
									}
								}
							});
						}

						that.unbind("blur");
					});
				});
			}
		});
	</script>
@endsection