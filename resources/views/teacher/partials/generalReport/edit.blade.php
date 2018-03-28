<div class="modal fade" id="modalEditReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        {!! Form::open(['route'=>'generalReport.store', 'method'=>'put', 'id'=>'formEditGR']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Editar Observaciones</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                        	<h4 id="textStudent"></h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    	<div class="form-group">
	                        {!! Form::label('report_edit', 'Reporte', []) !!}
	                        {!! Form::textarea('report_edit', null, []) !!}
	                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	{!! Form::hidden('id', null, ['id'=>'id']) !!}
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>