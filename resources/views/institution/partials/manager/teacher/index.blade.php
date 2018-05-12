@extends('institution.dashboard.index')


@section('breadcrums')
	<ol class="breadcrumb">
	  <li><a href="">Funcionarios</a></li>
	  <li><a href="{{route('institution.list.teacher')}}">Docentes</a></li>
	  <li class="active">Ver</li>
	</ol>
@endsection

@section('content')
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
			  	<div class="panel-heading clearfix">

					<a class="btn btn-primary btn-sm pull-right" href="{{route('teacher.create')}}">Crear Docente</a>
			  	</div>
			  	<div class="panel-body">

			  		<table class="table">
			  			<thead>
			  				<tr>
								<th>Apellidos y Nombres</th>
			  					<th>Tipo de identificacion</th>
			  					<th>N° identificacion</th>
			  					<th>Celular</th>
			  					<th>Email</th>
			  					<th>Acción</th>
			  				</tr>
			  			</thead>
			  			<tbody>
			  			</tbody>
			  		</table>
			  	</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$(document).ready(function(){

			$(".table").DataTable( {
				"language": {
				    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
				},
				"info":     false,
				// "order": [2],
				"autoWidth": false,
                "ajax": {
                    "method": "GET",
                    "url": "{{route('institution.teachers', [$institution->id, '2018'])}}"
                },
                "columns": [
                    {
                        "render": function(data, type, full, meta){
                            return full.manager.last_name+" "+full.manager.name;
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return full.manager.identification.identification_type.name
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return full.manager.identification.identification_number
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return full.manager.address.mobil
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return full.manager.address.email
                        }
                    },
                    {
                        "render": function(data, type, full, meta){
                            return '<a href="../manager/'+full.manager.id+'/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                        }
                    }
                ]
		    });
		});
	</script>
@endsection