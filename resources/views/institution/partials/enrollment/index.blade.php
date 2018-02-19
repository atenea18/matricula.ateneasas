@extends('institution.dashboard.index')


@section('breadcrums')
    <ol class="breadcrumb">

    </ol>
@endsection

@section('content')
    <div id="app">

    </div>
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
                            {{-- <th>#</th> --}}
                            <th>Apellidos y Nombres</th>
                            <th>Sede</th>
                            <th>Grupo</th>
                            <th>Año</th>
                            <th></th>
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
        $(document).ready(function () {

            var cont = 0;

            $(".table").DataTable({
                "order": [[ 0, "asc" ]],
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                "info": true,
                // "order": [1],
                "autoWidth": false,
                "ajax": {
                    "method": "GET",
                    "url": "{{route('institution.enrollments', [$institution_id,'2018'])}}"
                },
                "columns": [
                    // {
                    //     "render": function(data, type, full, meta){
                    //         return ++cont;
                    //     }
                    // },
                    {
                        "render": function(data, type, full, meta){
                            return full.student.last_name+" "+full.student.name;
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return (full.group.length) ? full.group[0].headquarter.name : ''
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return (full.group.length) ? full.group[0].name : ''
                        }
                    },
                    { 
                        "render": function(data, type, full, meta){
                            return full.school_year.year
                        }
                    },
                    {
                        "render": function(data, type, full, meta){
                            return '<a href="enrollment/'+full.id+'/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>';
                        }
                    }
                ]
            });
        });
    </script>
@endsection