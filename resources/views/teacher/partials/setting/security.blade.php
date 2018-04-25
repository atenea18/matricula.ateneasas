@extends('teacher.dashboard.index')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('teacher.home')}}">Inicio</a></li>
        <li class="active">Configuración</li>
    </ol>
@endsection

@section('content')


<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default" style="margin-bottom: 0">
			<div class="panel-body" style="padding: 0">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation">
						<a href="{{route('teacher.setting')}}">
							<i class="fa fa-cogs"></i> Cuenta
						</a>
					</li>
					<li role="presentation" class="active">
						<a href="#security" aria-controls="security" role="tab" data-toggle="tab">
							<i class="fa fa-shield-alt"></i> Seguridad e inicio de sesión
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- Tab panes -->
<div class="tab-content" style="padding: 0">
	<div role="tabpanel" class="tab-pane active" id="security">
		<div class="row">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-body">
						<h4><i class="fa fa-key"></i> Contraseña</h4>
						<hr>
						<div id="formerrors"></div>
						{!! Form::open(['route'=>['setting.updatePassword', $manager], 'method'=>'PUT', 'id'=>'formUpdatePass', 'class'=>'form-horizontal']) !!}
							<div class="form-group">
								{!! Form::label('current_password', 'Contraseña Actual', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
									{!! Form::password('current_password', ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('password', 'Nueva Contraseña', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
									{!! Form::password('password', ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-group">
								{!! Form::label('password_confirmation', 'Repita la Contraseña', ['class'=>'col-sm-4 control-label']) !!}
							    <div class="col-sm-8">
									{!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
							    </div>
							</div>
							<div class="form-grop">
								<button type="submit" class="btn btn-primary btn-block">Actualizar</button>
							</div>
						{!! Form::close() !!}
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

			$("#formUpdatePass").submit(function(e){

				e.preventDefault();

				var form = $(this);

				$.ajax({
					url: form.attr('action'),
					method: form.attr('method'),
					data: form.serialize(),
					dataType: 'json',
					beforeSend:function(){
						form.find('input[type=password], button').prop('disabled', true);
						form.find('button').empty().html("Actualizando..  <i class='fas fa-spinner fa-pulse'></i>");
					},
					success:function(data){
						form.find('input[type=password], button').prop('disabled', false).val('');
						form.find('button').empty().text("Actualizar");
						
						html = '<div class="alert alert-success"><ul><li>La contraseña ha sido actualizada exitosamente</li></ul></di>';

						$( '#formerrors' ).empty().html( html );
					},
					error:function(xhr){
						form.find('input[type=password], button').prop('disabled', false).val('');
						form.find('button').empty().text("Actualizar");
						
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