
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PRODUCTO
    </h3>
    <p class="text-justify">
    </p>
</div>
<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>productoRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRODUCTO</a>
        </li>
        <li>
            <a  href="<?php echo SERVERURL; ?>productoList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PRODUCTOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>producto-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PRODUCTO</a>
        </li>
    </ul> 
</div>

<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/productoAjax.php" method="POST" data-form="save" autocomplete="off" enctype="multipart/form-data">
		<fieldset>
			<legend><i class="far fa-plus-square"></i> &nbsp; Información de Producto<legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_codigo" class="bmd-label-floating">Código</label>
							<input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="producto_codigo_reg" id="producto_codigo" maxlength="45">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_nombre" class="bmd-label-floating">Nombre</label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="producto_nombre_reg" id="producto_nombre" maxlength="40" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_tipo" class="bmd-label-floating">Tipo</label>
							<select class="form-control" name="producto_tipo_reg" id="producto_tipo">
								<option value="" selected="" disabled="">Seleccione una opción</option>
								<option value="Taza">Taza</option>
								<option value="Consumo">Consumo</option>
								<option value="Hojuelas">Hojuelas</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_costo" class="bmd-label-floating">Costo</label>
							<input type="num" pattern="[0-9 .]{1,9}" class="form-control" name="producto_costo_reg" id="producto_costo" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_imagen" class="bmd-label-floating">Imagen</label>
							<input type="file" class="form-control" name="producto_imagen_reg" id="producto_imagen" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_peso" class="bmd-label-floating">Peso</label>
							<input type="num" pattern="[0-9 .]{1,9}" class="form-control" name="producto_peso_reg" id="producto_peso" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_unidad" class="bmd-label-floating">Unidad</label>
							<select class="form-control" name="producto_unidad_reg" id="producto_unidad">
								<option value="" selected="" disabled="">Seleccione una unidad</option>
								<option value="kg.">kg</option>
								<option value="g.">g</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_estado" class="bmd-label-floating">Estado</label>
							<select class="form-control" name="producto_estado_reg" id="producto_estado">
								<option value="" selected="" disabled="">Seleccione una opción</option>
								<option value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_porcentaje" class="bmd-label-floating">Porcentaje</label>
							<select class="form-control" name="producto_porcentaje_reg" id="producto_porcentaje">
								<option value="" selected="" disabled="">Seleccione una opción</option>
								<option value="50">50%</option>
								<option value="75">75%</option>
								<option value="100">100%</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_stock" class="bmd-label-floating">Stock</label>
							<input type="text" pattern="[0-9-]{1,5}" class="form-control" name="producto_stock_reg" id="producto_stock" maxlength="45">
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