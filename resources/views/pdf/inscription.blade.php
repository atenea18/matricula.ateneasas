<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Planeado academico</title>
	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<style type="text/css">
		body{
			background-color: #fff;
			font-size: 8px;
			color: #000;
		}

		.table{
			margin-bottom: 5px;
		}

		.table_header>tbody>tr>td{
		  border-top: none;
		}

		.table_header .table>thead>tr>th{
		  background-color: #ddd;
		}

		.table_header .table>tbody>tr>td{
		  background-color: #fff;
		}

		.bg-gray{
		  background-color: #f2f2f2;
		}
		
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
			padding: 3px;
		}
		.table td{
			padding: 0;
		}
	</style>
</head>
<body>
	{{--  --}}
	<table class="table">
		<tbody>
			<tr>
				<td width="1%" rowspan="2">
					<img src="{{ ($institution->picture === '') ? '' : Storage::disk('uploads')->url($institution->picture)}}" alt="" width="50">
				</td>
				<td class="text-center">
					<h4>{{$group->headquarter->institution->name}}</h4>
					<h5>{{$group->headquarter->name}}</h5>
				</td>
			</tr>
		</tbody>
	</table>
	{{--  --}}
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Apellidos y Nombres del Estudiante</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $cont= 0; ?>
			@foreach($students->sortBy('last_name') as $key => $student)
			<tr>
				<td width="5">{{ (++$cont) }}</td>
				<td>{{$student->fullNameInverse}}</td>
				<td></td>
				<td></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>