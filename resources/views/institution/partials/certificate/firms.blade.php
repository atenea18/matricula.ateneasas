<!-- Modal -->
<div class="modal fade" id="mFirmas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	    	<form action="" id="formFirmas" method="POST" action="">
		    	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">Configurar mensajes del informe final</h4>
		      	</div>
		      	<div class="modal-body">
		      		<div class="container-fluid">
		      			<div class="row">
		      				<div class="col-md-12">
		      					<div class="form-group">
		      						<label for="">Encabezado</label>
		      						<textarea class="form-control" id="header" name="header"></textarea>
		      					</div>
		      				</div>
		      			</div>
		      			<div class="row">
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Nombre Rector (a)</label>
		      						<input type="text" name="rector_firm" id="rector_firm" class="form-control">
		      					</div>
		      				</div>
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Numero documento Rector (a)</label>
		      						<input type="text" name="rector_number_document" id="rector_number_document" class="form-control">
		      					</div>
		      				</div>
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Lugar de expedición</label>
		      						<input type="text" name="rector_place_expedition" id="rector_place_expedition" class="form-control">
		      					</div>
		      				</div>
		      			</div>
		      			<div class="row">
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Nombre Secretario (a)</label>
		      						<input type="text" name="secretary_firm" id="secretary_firm" class="form-control">
		      					</div>
		      				</div>
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Numero documento Secretario (a)</label>
		      						<input type="text" name="secretary_number_document" id="secretary_number_document" class="form-control">
		      					</div>
		      				</div>
		      				<div class="col-md-4">
		      					<div class="form-group">
		      						<label for="">Lugar de expedición</label>
		      						<input type="text" name="secretary_place_expedition" id="secretary_place_expedition" class="form-control">
		      					</div>
		      				</div>
		      			</div>
		      			<div class="row">
		      				<div class="col-md-6">
		      					<div class="form-group">
		      						<label for="">Lugar donde se expide el documento</label>
		      						<input type="text" name="place_expedition_document" id="place_expedition_document" class="form-control">
		      					</div>
		      				</div>
		      			</div>
		      		</div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" id="btncancelMessage" class="btn btn-default" data-dismiss="modal">Cancelar</button>
		        	<button type="submit" id="btnMessageSave" class="btn btn-primary">Guardar</button>
		      	</div>
	      	</form>
	    </div>
	</div>
</div>