<div class="modal fade" id="modalAddGeneralReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        {!! Form::open(['route'=>'generalReport.store', 'method'=>'post', 'id'=>'formAddGR']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Agregar Observaciones</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('group_gr', 'Grupo', []) !!}
                            {!! Form::select('group_gr', $groups, null, ['class'=>'form-control', 'placeholder'=>'- Selecciona un grupo -','required'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('period_working_day_id', 'Periodo', []) !!}
                            {!! Form::select('period_working_day_id', [], null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="" id="selectGR" class="form-control" multiple="multiple" size="6"></select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-default btn-block" id="selectGR_rightAll"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                        <button type="button" id="selectGR_rightSelected" class="btn btn-default btn-block"><i class="fa fa-angle-right" aria-hidden="true"></i></button>
                        <button type="button" id="selectGR_leftSelected" class="btn btn-default btn-block"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
                        <button type="button" id="selectGR_leftAll" class="btn btn-default btn-block"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="enrollments[]" id="selectGR_to" class="form-control" multiple="multiple" size="6"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        {!! Form::label('report_create', 'Informe', []) !!}
                        {!! Form::textarea('report_create', null, []) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>