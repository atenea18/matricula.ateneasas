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
				<td class="text-center">
					<h4>{{$group->headquarter->institution->name}}</h4>
					<h5>{{$group->headquarter->name}}</h5>
				</td>
			</tr>
			{{-- <tr>
				<td>
					<img src="{{asset('img/mac.png')}}" alt="" width="50">
				</td>
			</tr> --}}
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
			@foreach($students as $key => $student)
			<tr>
				<td width="5">{{ ($key+1) }}</td>
				<td style="height:5px; min-height:5px;">{{$student->fullName}}</td>
				<td></td>
				<td></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>