@extends('institution.dashboard.index')


@section('breadcrums')
    <ol class="breadcrumb">

    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left">Consulta de Estudiantes</h3>
                    <a href="{{route('enrollment.card.grade')}}" class="btn btn-primary btn-sm pull-right">Ficha Académica</a>
                    <a href="{{route('student.create')}}" class="btn btn-primary btn-sm
                    pull-right">Crear
                        Estudiante</a>
                </div>
                <div class="panel-body">

                    <table class="table" id="table">
                        <thead>
                        <tr>

                            <th>Apellidos y Nombres</th>
                            <th>Sede</th>
                            <th>Grupo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>{{ $enrollment->student->last_name.' '.$enrollment->student->name}}</td>
                                <td>{{ $enrollment->headquarter->name}}</td>
                                <td>{{ ($enrollment->group != null) ? $enrollment->group->name : ''}}</td>

                                <td>
                                    <a href="{{ route('enrollment.edit', $enrollment->id) }}" class="btn btn-primary btn-sm"><i
                                                class="fa fa-edit"></i></a>
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
    <script>
        $(document).ready(function () {

            $(".table").DataTable({
                "order": [[ 0, "asc" ]],
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection