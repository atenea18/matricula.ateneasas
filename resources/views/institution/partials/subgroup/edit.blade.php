@extends('institution.dashboard.index')

@section('css')
	<link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">
@endsection

@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="{{route('subgroup.index')}}">Subrupos</a></li>
	  <li class="active">Editar</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
			
			{!! Form::open(['route'=>['subgroup.update',$subgroup], 'method'=>'put'])!!}
				<div class="panel panel-default">

				  	<div class="panel-body">

				  		@include('complements.error')

				  		<div class="container-fluid">
				  			<div class="row">
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('headquarter_id', 'Sede') !!}
				  						{!! Form::select('headquarter_id', $headquarters, $subgroup->headquarter->id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una sede']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('grade_id', 'Grado') !!}
				  						{!! Form::select('grade_id', $grades, $subgroup->grade->id, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un grado']) !!}
				  					</div>
				  				</div>
								<div class="col-md-3">
									<div class="form-group">
										{!! Form::label('name', 'Nombre del Subgrupo') !!}
										{!! Form::text('name', $subgroup->name, ['class'=>'form-control']) !!}
									</div>
								</div>
								{{-- <div class="col-md-3">
									<div class="form-group">
										{!! Form::label('working_day_id', 'Jornada') !!}
										{!! Form::select('working_day_id', $journeys, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones una jornada']) !!}
									</div>
								</div> --}}
				  			</div>
				  			{{-- <div class="row">
								<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('quota', 'Cupos') !!}
				  						{!! Form::text('quota', null, ['class'=>'form-control']) !!}
				  					</div>
				  				</div>
				  				<div class="col-md-3">
				  					<div class="form-group">
				  						{!! Form::label('teacher_id', 'Director de Grupo') !!}
				  						{!! Form::select('teacher_id', $teachers, null, ['class'=>'form-control chosen-select', 'placeholder'=>'Selecciones un docente']) !!}
				  					</div>
				  				</div>
				  			</div> --}}
				  		</div>
				  	</div>
				  	<div class="panel-footer">
						<div class="form-group text-center">
				  			{!! Form::submit('Actualizar', ['class'=>'btn btn-primary']) !!}
						</div>
					</div>
				</div>
			{!! Form::close()!!}
    	</div>
    </div>

    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h4>Listado de Estudiantes</h4>
    			</div>
    			<div class="panel-body">
    				<table class="table table-hover">
    					<thead>
    						<tr>
    							<th>#</th>
    							<th>Apellidos y Nombres</th>
    							<th>Tipo Doc.</th>
    							<th>Num. Doc.</th>
    							<th>Grupo</th>
    							<th>Remover</th>
    						</tr>
    					</thead>
    					<tbody>
    					@foreach($enrollments->sortBy('last_name') as $key => $enrollment)
						<tr>
							<td>{{ (++$key) }}</td>
							<td> {{ $enrollment->student->fullNameInverse }} </td>
							<td> {{ $enrollment->student->identification->identification_type->name }} </td>
							<td> {{ $enrollment->student->identification->identification_number }} </td>
							<td> {{ $enrollment->group->first()->name }} </td>
							<td>
								<a href="" class="btn btn-danger btn-sm" data-enrollment="{{$enrollment->id}}" data-subgroup="{{$subgroup->id}}" data-method="delete">
                                    <i class="fa fa-user-times"></i>
                                </a>
							</td>
						</tr>
    					@endforeach	
    					</tbody>
    				</table>
    			</div>
    		</div>
    	</div>
    </div>
@endsection

@section('js')
	<script src="{{asset('js/chosen.jquery.js')}}"></script>
	<script>
    	$(function() {

    		$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

	        $('.chosen-select').chosen({width: "100%"});
	        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });

	        $(".table").DataTable( {
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                // "info":     false,
                // "order": [
                //     [0, "asc"]
                // ],
                "autoWidth": false,
            });

            // 
            $("a[data-method=delete]").click(function(e){

                e.preventDefault();

                var btn = $(this),
                    enrollment_id = btn.data('enrollment'),
                    subgroup_id = btn.data('subgroup');

                $.ajax({
                    url: "{{route('subgroup.deleteEnrollment')}}",
                    method: "POST",
                    data: { enrollment_id, subgroup_id},
                    beforeSend: function(){
                        btn.empty().html("<i class='fas fa-spinner fa-pulse'></i>");
                    },
                    success:function(data){
                        btn.empty().html("<i class='fa fa-user-times'></i>");

                        btn.parent().parent().remove();
                        toastr.success(data.message);
                    },  
                    error:function(xhr)
                    {
                        btn.empty().html("<i class='fa fa-user-times'></i>");
                        console.log(xhr);
                    }
                });
            });
    	});
	</script>
@endsection