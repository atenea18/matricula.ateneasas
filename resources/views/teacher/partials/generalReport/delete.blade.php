<div class="modal fade" id="modalDelReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        {!! Form::open(['route'=>'generalReport.store', 'method'=>'DELETE', 'id'=>'formDelGR']) !!}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Eliminar Informe General</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div>
                        	<p id="textStudent"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	{!! Form::hidden('id', null, ['id'=>'id']) !!}
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Eliminar</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>