<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h2 class="panel-title pull-left">Grupos</h2>
            </div>
            <div class="panel-body">
                @if(count($pemsun) > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>N°</th>
                            <th>Grupo</th>
                            <th>Asignatura</th>
                            <th>Tipo</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pemsun as $key => $pensum)
                            <tr>
                                <td>{{ ($key+1) }}</td>
                                <td>{{ $pensum->group->name }}</td>
                                <td>{{ $pensum->asignature->name }}</td>
                                <td>{{ $pensum->subjectType->name }}</td>
                                <td>
                                    <div class='btn-group' role='group'>
                                        <button type='button' class='btn btn-primary dropdown-toggle'
                                                data-toggle='dropdown'
                                                aria-haspopup='true' aria-expanded='false'>
                                            Evaluación
                                            <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu'>
                                            <li>
                                                <a href="{{route('evaluation.index', [$pensum->group_id,$pensum->asignature->id])}}">
                                                    Evaluar Periodo
                                                </a>
                                                {{--
                                                <a href="{{route('teacher.evaluation.periods', [$pensum->group_id,'group',$pensum->asignature->id])}}">Evaluar
                                                    Periodo
                                                </a>
                                                 --}}
                                            </li>
                                            <li>
                                                <a href="{{route('group.recovery', [$pensum->group_id, $pensum->asignatures_id])}}">Superaciones
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('group.pendingPeriod', [$pensum->group_id, $pensum->asignatures_id])}}">Evaluar
                                                    Periodo Pendiente
                                                </a>
                                            </li>
                                            <li><a href=''>Refuerzo Academico</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if(count($sub_pensum) > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>N°</th>
                            <th>Grupo</th>
                            <th>Asignatura</th>
                            <th>Tipo</th>
                            <th>Acción</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($sub_pensum as $key => $pensum)

                            <tr>
                                <td>{{ ($key+1) }}</td>
                                <td>{{ $pensum->subgroup->name }}</td>
                                <td>{{ $pensum->asignature->name }}</td>
                                <td>{{ $pensum->subjectType->name }}</td>
                                <td>
                                    <div class='btn-group' role='group'>
                                        <button type='button' class='btn btn-primary dropdown-toggle'
                                                data-toggle='dropdown'
                                                aria-haspopup='true' aria-expanded='false'>
                                            Evaluación
                                            <span class='caret'></span>
                                        </button>
                                        <ul class='dropdown-menu'>
                                            <li>
                                                <a href="{{route('teacher.evaluation.periods', [$pensum->subgroup->id,'subgroup',$pensum->asignature->id])}}">Evaluar
                                                    Periodo
                                                </a>
                                            </li>


                                        </ul>
                                    </div>
                                </td>


                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>