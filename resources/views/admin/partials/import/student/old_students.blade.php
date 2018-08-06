@extends('admin.dashboard.index')

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
			    	<h3 class="panel-title pull-left">Importar</h3>
			  	</div>
			  	<div class="panel-body">
			  		{!! Form::open(['route'=>'import.old_students', 'method'=>'post', 'files'=>true, 'id'=>'formUploadFile']) !!}
						<div class="container-fuild">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Importar Estudiantes antigüos</label>
									{!! Form::file('excel', ['id'=>'excel','class'=>'form-control','required'=>true]) !!}
								</div>
								{!! Form::submit('Importar', ['class'=>'btn btn-primary']) !!}
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Institutción</label>
									{!! Form::select('institution_id', $institutions, null, ['class'=>'form-control', 'placeholder'=>'Selecciona una Institución','id'=>'institution_id', 'required'=>true]) !!}
								</div>
							</div>
						</div>
			  		{!! Form::close() !!}
			  	</div>
			</div>
    	</div>
    </div>
@endsection

@section('js')
	<script>
		$(document).ready(function(){

			// $("#formUploadFile").submit(function(e){

			// 	e.preventDefault();

			// 	var formData = new FormData(),
			// 		file_data = $('#excel').prop('files')[0],
			// 		institution_id = $('#institution_id').val();

			// 		// formData.append('excel', file_data);
			// 		formData.append('institution_id', institution_id);

			// 	$.ajaxSetup({
			// 	    headers: {
			// 	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			// 	    }
			// 	});

			// 	$.ajax({
			// 	    url: $(this).attr('action'),
			// 	    type: 'POST',
			// 	    data: formData,
			// 	    success: function (data) {
			// 	        console.log(data);
			// 	    },
			// 	    cache: false,
			// 	    processData: false
			// 	});

			// 	return false;
			// });
		});
	</script>
@endsection