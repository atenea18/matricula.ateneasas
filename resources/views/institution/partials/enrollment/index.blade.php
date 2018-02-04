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
                    <div class="pull-right">
                        <a href="{{route('enrollment.card.grade')}}" class="btn btn-primary btn-sm">Ficha Académica</a>

                        <a href="{{route('student.create')}}" class="btn btn-primary btn-sm">Crear
                            Estudiante</a>
                    </div>
                </div>
                <div class="panel-body">

                    <table class="table" id="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Apellidos y Nombres</th>
                            <th>Sede</th>
                            <th>Grupo</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $cont=0;?>
                        @foreach($enrollments as $key => $enrollment)
                            <tr>
                                <td>{{ (++$cont) }}</td>
                                <td>{{ $enrollment->student->fullNameInverse}}</td>
                                <td>{{ $enrollment->group[0]->headquarter->name}}</td>
                                <td>{{ ($enrollment->group[0] != null) ? $enrollment->group[0]->name : ''}}</td>

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
                // "order": [1, "asc" ],
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                "info": true,
                // "order": [1],
                "autoWidth": false,
            });
        });
    </script>
@endsection