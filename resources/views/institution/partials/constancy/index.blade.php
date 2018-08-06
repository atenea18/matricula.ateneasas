@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Constancias</li>
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
			        	@if($institution->hasConstancyStudy())
				        	<li role="presentation" class="active">
				        		<a href="#stydy_constancy" aria-controls="stydy_constancy" role="tab" data-toggle="tab">Constancias de estudio</a>
				        	</li>
			        	@endif
			        	<li role="presentation" class="{{($institution->hasConstancyStudy()) ? : 'active'}}">
					    	<a href="#constancies" aria-controls="constancies" role="tab" data-toggle="tab">Configurar Constancias</a>
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
								{{-- CONSTANCIAS --}}
								<div role="tabpanel" class="tab-pane {{($institution->hasConstancyStudy()) ? : 'active'}}" id="constancies">
							    	@include('institution.partials.constancy.constancies.home')
							    </div>
					        	{{-- CONSTANCIA DE ESTUDIO --}}
							    <div role="tabpanel" class="tab-pane {{($institution->hasConstancyStudy()) ? 'active' : ''}}" id="stydy_constancy">
							    	@include('institution.partials.constancy.study.home')
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
			
			hiddenSelect();

			$("#print_type").change(function(){

				var type = $(this).val()
					url = "{{env('APP_URL')}}/api/";

					url += (type === 'grade_id_cs') ? "institution/{{$institution->id}}/grades" : "institution/{{$institution->id}}/groups";

				hiddenSelect();
				showSelect(type,url);
				// console.log(type);
			});

			$("#group_id_cs").change(function(){

				aviableSelectMultiple();

				var value = $(this).val();

				$.get("{{env('APP_URL')}}/api/group/"+value+"/students", function(data){

					var options = '';
					$.each(data.data, function(indx, el){

						options += '<option value="'+el.id+'">' + el.name + '</option>';
					});

					$( "#sheet_cs" ).html( options );

				},"json");
			});

			function showSelect(ele, url){

				$.get(url, function(data){

					console.log(data.data);
					var options = '',
						tag = $('#'+ele);

					options += '<option>- Seleccine una opcion -</option>'
					$.each(data.data, function(indx, el){

						options += '<option value="'+el.id+'">' + el.name + '</option>';
						// console.log(el.name);
					});

					tag.html( options );
					tag.prop('disabled',false);
					tag.attr('required',true);

				}, "json");

			}

			function aviableSelectMultiple()
			{
				$("#sheet_cs, #sheet_cs_to").each(function(indx, ele){
					var elemento = $("#"+ele.id);

					elemento.prop('disabled',false);
				});
			}

			function hiddenSelect(){

				$(".select-hidden, #sheet_cs, #sheet_cs_to").each(function(indx, ele){
					var elemento = $("#"+ele.id);

					elemento.prop('disabled',true);
				});
			}

			// Multi Select
			$('#sheet_cs').multiselect({
				search: {
					left: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
						 
					right: '<input type="text" name="q" class="form-control" placeholder="Buscar..." style="margin-bottom:5px;"/>',
					 
				}
			});
			
		});
	</script>
@endsection