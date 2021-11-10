<?php
	if ($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2) {
			echo $lc->forzar_cierre_sesion_controlador();
			exit();
		}
?>


<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PRODUCTO
    </h3>
    
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVERURL; ?>productoRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PRODUCTO</a>
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

	<?php 
		//Comprobando si hay usuarios o administradores 
		require_once "./controladores/productoControlador.php";
		$ins_producto= new productoControlador();

		$datos_producto=$ins_producto->datos_producto_controlador("Unico",$pagina[1]);

		if ($datos_producto->rowCount()==1){
			$campos=$datos_producto->fetch();
		
	?>

	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/productoAjax.php" method="POST" data-form="update" autocomplete="off">

		<input type="hidden" name="producto_id_up" value="<?php echo $pagina[1] ?>">

		<fieldset>
			<legend><i class="far fa-plus-square"></i> &nbsp; Información del Producto</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="" class="bmd-label-floating">Código</label>
							<input  type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="producto_codigo_up" id="producto_codigo" value="<?php echo $campos['producto_codigo'] ?>" maxlength="45">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_nombre" class="bmd-label-floating">Nombre</label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="producto_nombre_up" id="producto_nombre" value="<?php echo $campos['producto_nombre'] ?>" maxlength="40" required="">
						</div>
					</div>
					
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_tipo" class="bmd-label-floating">Tipo</label>

							<select class="form-control" name="producto_tipo_up" id="producto_tipo">
								
								<option value="Taza" <?php if ($campos['producto_tipo']=="Taza") {
									echo 'selected=""';
								} ?> >Taza <?php if($campos['producto_tipo']=="Taza"){ echo '(Actual)';} ?> </option>
								<option value="Consumo" <?php if ($campos['producto_tipo']=="Consumo") {
									echo 'selected=""';
								} ?> >Consumo <?php if($campos['producto_tipo']=="Consumo"){ echo '(Actual)';} ?> </option>
								<option value="Hojuelas" <?php if ($campos['producto_tipo']=="Hojuelas") {
									echo 'selected=""';
								} ?> >Hojuelas <?php if($campos['producto_tipo']=="Hojuelas"){ echo '(Actual)';} ?> </option>
								
							</select>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_costo" class="bmd-label-floating">Costo</label>
							<input type="num" pattern="[0-9 .]{1,9}" class="form-control" name="producto_costo_up" id="producto_costo" value="<?php echo $campos['producto_costo'] ?>" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_peso" class="bmd-label-floating">Peso</label>
							<input type="num" pattern="[0-9 .]{1,9}" class="form-control" name="producto_peso_up" id="producto_peso" value="<?php echo $campos['producto_peso'] ?>" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_unidad" class="bmd-label-floating">Unidad</label>
							<select class="form-control" name="producto_unidad_up" id="producto_unidad">
								<option value="" selected="" disabled="">Seleccione una unidad</option>
								<option value="kg." <?php if ($campos['producto_unidad']=="kg.") {
									echo 'selected=""';
								} ?>>kg. <?php if($campos['producto_unidad']=="kg."){ echo '(Actual)';} ?></option>
								<option value="g." <?php if ($campos['producto_unidad']=="g.") {
									echo 'selected=""';
								} ?>>g. <?php if($campos['producto_unidad']=="g."){ echo '(Actual)';} ?></option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_estado" class="bmd-label-floating">Estado</label>
							<select class="form-control" name="producto_estado_up">

								<option value="Activo" <?php if ($campos['producto_estado']=="Activo") {
									echo 'selected=""';
								} ?> >Activo <?php if($campos['producto_estado']=="Activo"){ echo '(Actual)';} ?> </option>

								<option value="Inactivo" <?php if ($campos['producto_estado']=="Inactivo") {
									echo 'selected=""';
								} ?> >Inactivo <?php if($campos['producto_estado']=="Inactivo"){ echo '(Actual)';} ?> </option>


							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="producto_porcentaje" class="bmd-label-floating">Porcentaje</label>

							<select class="form-control" name="producto_porcentaje_up" id="producto_porcentaje">
								
								<option value="50" <?php if ($campos['producto_porcentaje']=="50") {
									echo 'selected=""';
								} ?> >50% <?php if($campos['producto_porcentaje']=="50"){ echo '(Actual)';} ?> </option>
								<option value="75" <?php if ($campos['producto_porcentaje']=="75") {
									echo 'selected=""';
								} ?> >75% <?php if($campos['producto_porcentaje']=="75"){ echo '(Actual)';} ?> </option>
								<option value="100" <?php if ($campos['producto_porcentaje']=="100") {
									echo 'selected=""';
								} ?> >100% <?php if($campos['producto_porcentaje']=="100"){ echo '(Actual)';} ?> </option>
								
							</select>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="producto_stock" class="bmd-label-floating">Stock</label>
							<input type="num" pattern="[0-9]{1,9}" class="form-control" name="producto_stock_up" id="producto_stock" value="<?php echo $campos['producto_stock'] ?>" maxlength="9">
						</div>
					</div>
					
				</div>
			</div>
		</fieldset>

		<p class="text-center" style="margin-top: 40px;">
			<button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
		</p>
	</form>
<?php } else { ?>
	<div class="alert alert-danger text-center" role="alert">
		<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
		<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
		<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
	</div>
	<?php } ?>
</div>