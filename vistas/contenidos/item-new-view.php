<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM
    </h3>

</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
        </li>
    </ul>
</div>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/itemAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="far fa-plus-square"></i> &nbsp; Información de Item<legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="item_codigo" class="bmd-label-floating">Código</label>
							<input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="item_codigo_reg" id="item_codigo" maxlength="45">
						</div>
					</div>
					
					<div class="col-12 col-md-8">
						<div class="form-group">
							<label for="item_nombre" class="bmd-label-floating">Nombre</label>
							<input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="item_nombre_reg" id="item_nombre" maxlength="140">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_tipo" class="bmd-label-floating">Tipo</label>
							<select class="form-control" name="item_tipo_reg" id="item_tipo">
								<option value="" selected="" disabled="">Seleccione una opción</option>
								<option value="Producto">Producto</option>
								<option value="Servicio">Servicio</option>								
							</select>
						</div>
					</div>
					
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_stock" class="bmd-label-floating">Stock (Solo Productos)</label>
							<input type="num" pattern="[0-9]{1,9}" class="form-control" name="item_stock_reg" id="item_stock" maxlength="9">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_estado" class="bmd-label-floating">Estado</label>
							<select class="form-control" name="item_estado_reg" id="item_estado">
								<option value="" selected="" disabled="">Seleccione una opción</option>
								<option value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_detalle" class="bmd-label-floating">Detalle</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="item_detalle_reg" id="item_detalle" maxlength="190">
						</div>
					</div>
					
				</div>
			</div>
		</fieldset>
		
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
			&nbsp; &nbsp;
			<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
		</p>
	</form>
</div>
