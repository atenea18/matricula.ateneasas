@extends('institution.dashboard.index')


@section('breadcrums')
<ol class="breadcrumb">
	<li class="active">Periodo Académico</li>
</ol>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">

				<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
					Habilitar / Desabilitar Periodo
				</button>

				<a class="btn btn-primary btn-sm pull-right" href="{{route('period.create')}}">
					Crear Periodo Académico
				</a>

			</div>
			<div class="panel-body">
				@include('flash::message')
				<table class="table" id="tableIndex">
					<thead>
						<tr>
							{{-- <th>#</th> --}}
							<th>Periodo</th>
							<th>Pocentaje (%)</th>
							<th>Jornada</th>
							<th>Año lectivo</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach($periods as $key => $period)
						<tr>
							<td>{{ $period->period->name }}</td>
							<td>{{ $period->percent }}</td>
							<td>{{ $period->workingday->name }}</td>
							<td>{{ $period->schoolyear->year }}</td>
							<td>
								<a href="{{route('period.edit', $period)}}" class="btn btn-primary btn-sm">
									<i class="fa fa-edit"></i>
								</a>
								<a 	href="" class="btn btn-sm btn-danger" 
								onclick="event.preventDefault();
								document.getElementById('formDelPEriod{{$period->id}}').submit();">
								<i class="fa fa-trash"></i>
							</a>

							{!! Form::open(['route'=>['period.destroy', $period], 'method'=>'DELETE', 'id'=>"formDelPEriod{$period->id}"]) !!}
							{!! Form::close() !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
@include('institution.partials.period.enableAndDisablePeriod')
@endsection

@section('js')
<script>
	$(document).ready(function(){

		$("#tableIndex").DataTable( {
			"language": {
				"url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
			},
			"info":     false,
				// "order": [2],
				"autoWidth": false,
			});

		var table = $("#tablePeriodSearch").DataTable({
            // "order": [[ 0, "asc" ]],
            "language": {
            	"url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
            },
            "info": true,
            // "order": [1],
            "autoWidth": false,
            retrieve: true,
            "ajax": {
            	"method": "GET",
            	"url": "{{env('APP_URL')}}api/periods/{{$institution->id}}",
            },
            "columns": [
                {
                    "render": function(data, type, full, meta){
                        return "Periodo "+full.period.name;
                    }
                },
                {
                    "render": function(data, type, full, meta){
                        return full.workingday.name;
                    }
                },
                {
                    "render": function(data, type, full, meta){
                    	if(full.periods_state_id == 1)
                    	{
                    		return "<input type='checkbox' value='"+full.id+"' checked><div class='hide' style='float:right;'>Cargando..<i class='fas fa-spinner fa-pulse'></i></div>";
                    	}
                    	else
                    	{
                    		return "<input type='checkbox' value='"+full.id+"'><div class='hide' style='float:right;'>Cargando..<i class='fas fa-spinner fa-pulse'></i></div>";
                    	}
                    }
                },
            ]
        });

        // ADD EVENT TO BUTTON EDIT AND DELTE
		$('#tablePeriodSearch').on( 'click', 'input[type=checkbox]', function (e) {

			var checkbox = $(this),
				nextEl = checkbox.next(),
				checked = checkbox.is(':checked');

			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
			
			
			$.ajax({
				url: "{{route('period.changeState')}}",
				method : "POST",
				data : {checked : checked, value: checkbox.val()},
				beforeSend:function(){
					checkbox.prop('disabled',true);
					nextEl.removeClass('hide');
					nextEl.parent().parent().addClass('tr_loading');
				},
				success:function(data){
					console.log(data);
					checkbox.prop('disabled',false);
					nextEl.addClass('hide');
					nextEl.parent().parent().removeClass('tr_loading');
				},
				error:function(xhr){
					checkbox.prop('disabled',false);
					console.log(xhr)
				}

			});
			
		});
	});
</script>
@endsection