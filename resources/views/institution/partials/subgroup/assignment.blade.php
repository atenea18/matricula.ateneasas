@extends('institution.dashboard.index')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <link rel="stylesheet" href="{{asset('css/bootstrap-chosen.css')}}">

@endsection

@section('breadcrums')
    <ol class="breadcrumb">
        <li><a href="{{route('subgroup.index')}}">Subgrupos</a></li>
        <li class="active">Asignación</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            	<div class="panel-heading" style="text-align: center; background-color:#eee; padding-top: 10px;">
            		<h4>Subgrupo - {{ $subgroup->name}}</h4>
            	</div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="allCheckbox"></th>
                                {{-- <th>#</th> --}}
                                <th>Nombres y Apellidos</th>
                                <th>Novedad</th>
                                <th>Asginar / Remover</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $cont = 0; ?>
                        @foreach($allEnrollments as $key => $enrollment)   
                        <tr>
                            <td>
                                {!! Form::checkbox('assignmentCheck[]', $enrollment->id, false, []) !!}
                            </td>
                            {{-- <td>
                                {{ ++$cont }}
                            </td> --}}
                            <td>
                                {{ $enrollment->student->fullNameInverse }}
                            </td>
                            <td></td>
                            <td class="text-center" width="1%">
                                <?php $hasAssignment = false; ?>
                                @foreach($students_with_subgroup as $key => $studentWS)
                                    
                                    @if($enrollment->id == $studentWS->id)
                                        <?php $hasAssignment = true; ?>
                                    @endif

                                @endforeach
                                @if(!$hasAssignment)
                                <a href="" class="btn btn-primary btn-sm" data-enrollment="{{$enrollment->id}}" data-subgroup="{{$subgroup->id}}" data-method="store">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                <a href="" class="btn btn-danger btn-sm hide" data-enrollment="{{$enrollment->id}}" data-subgroup="{{$subgroup->id}}" data-method="delete">
                                    <i class="fa fa-user-times"></i>
                                </a>
                                @else
                                <a href="" class="btn btn-primary btn-sm hide" data-enrollment="{{$enrollment->id}}" data-subgroup="{{$subgroup->id}}" data-method="store">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                                <a href="" class="btn btn-danger btn-sm" data-enrollment="{{$enrollment->id}}" data-subgroup="{{$subgroup->id}}" data-method="delete">
                                    <i class="fa fa-user-times"></i>
                                </a>
                                @endif
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
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".table").DataTable( {
                "language": {
                    "url": "{{asset('plugin/DataTables/languaje/Spanish.json')}}"
                },
                // "info":     false,
                "order": [
                    [2, "asc"]
                ],
                "autoWidth": false,
            });

            $("a[data-method=store]").click(function(e){

                e.preventDefault();

                var btn = $(this),
                    enrollment_id = btn.data('enrollment'),
                    subgroup_id = btn.data('subgroup');

                $.ajax({
                    url: "{{route('subgroup.addEnrollment')}}",
                    method: "POST",
                    data: { enrollment_id, subgroup_id},
                    beforeSend: function(){
                        btn.empty().html("<i class='fas fa-spinner fa-pulse'></i>");
                    },
                    success:function(data){
                        btn.empty().html("<i class='fa fa-user-plus'></i>");
                        console.log(data);

                        btn.addClass('hide');
                        btn.next().removeClass('hide');
                        toastr.success(data.message);
                    },  
                    error:function(xhr)
                    {
                        btn.empty().html("<i class='fa fa-user-plus'></i>");
                        console.log(xhr);
                    }
                });
            });

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
                        console.log(data);

                        btn.addClass('hide');
                        btn.prev().removeClass('hide');
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