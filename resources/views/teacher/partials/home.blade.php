@extends('teacher.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li class="active">Sedes</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">
			    	<h3 class="panel-title pull-left">Inicio</h3>
			  	</div>
			  	<div class="panel-body">

			  	</div>
			</div>
    	</div>
    </div>
@include('teacher.partials.complements.modalCheckEmail')
@endsection

@section('js')
    <script>
        $(document).ready(function(){

            $.get("{{env('APP_URL')}}/teacher/setting/{{$manager->id}}/checkEmail", function(data){

            	if(!data)
            	{
            		$("#modalCheckEmail").modal({
            			keyboard: false,
						backdrop: 'static'
            		});
            	}
            });

            $("#formCheckEmail").submit(function(e){

            	e.preventDefault();

            	var form = $(this);

            	$.ajax({
            		url: form.attr('action'),
            		method: form.attr('method'),
            		data: form.serialize(),
            		beforeSend:function(){
            			form.find('input[type=text], button').prop('disabled', true);
						form.find('button[type=submit]').empty().html("Guardando..  <i class='fas fa-spinner fa-pulse'></i>");
            		},
            		success:function(data){
            			console.log(data);
            			
            			window.location = "{{route('teacher.setting')}}";
						form.find('button[type=submit]').empty().text("Guardar");
            		},
            		error:function(xhr){
            			console.log(xhr);
            			form.find('input[type=text], button').prop('disabled', false).val('');
						form.find('button[type=submit]').empty().text("Guardar");

            			var errors = xhr.responseJSON.errors,
							html = '<div class="alert alert-danger"><ul>';
						
						$.each(errors, function(indx, error){
							html += '<li>' + error[0] + '</li>';
						});

						html += '</ul></di>';

						$( '#formerrors' ).empty().html( html );
            		}
            	});
            });

        });
    </script>
@endsection