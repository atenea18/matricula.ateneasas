
<table class="table" id="table">
    <thead>
    <tr>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Número Identidad</th>
        <th>Grupo</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $student)
        <tr>
            <td>{{$student->name}}</td>
            <td>{{$student->last_name}}</td>
            <td>{{$student->identification->identification_number}}</td>
            <td>{{ (\App\Group::findOrfail($student->group_id)!= null) ? \App\Group::findOrfail($student->group_id)->name : ''}}</td>
            <td>
                <form action="{{route('enrollment.card.generate')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="typecard" value="byStudent">
                    <input type="hidden" name="student_id" value="{{$student->student_id}}">
                    <input type="submit" value="Generar" class="btn btn-primary">
                </form>
            </td>
        </tr>

    @endforeach
    </tbody>
</table>



@section('js')
    <script>
        $(document).ready(function () {

            $(".table").DataTable({
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                "info": false,
                "autoWidth": false,
            });
        });
    </script>
@endsection