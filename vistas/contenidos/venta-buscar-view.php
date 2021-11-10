<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR VENTAS POR FECHA
    </h3>

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a  href="<?php echo SERVERURL; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR VENTA</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-venta/"><i class="far fa-calendar-alt"></i> &nbsp; VENTAS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>venta-buscar/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSQUEDA</a>
        </li>

    </ul>
</div>
<?php 
	if (!isset($_SESSION['fecha_inicio_venta']) && empty($_SESSION['fecha_inicio_venta']) && !isset($_SESSION['fecha_final_venta']) && empty($_SESSION['fecha_final_venta'])) {
?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
		<input type="hidden" name="modulo" value="venta">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-4">
					<div class="form-group">
						<label for="busqueda_inicio_venta" >Fecha inicial (día/mes/año)</label>
						<input type="date" class="form-control" name="fecha_inicio" id="busqueda_inicio_venta" value="<?php echo date("Y-m-d"); ?>" maxlength="30">
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="form-group">
						<label for="busqueda_final_venta" >Fecha final (día/mes/año)</label>
						<input type="date" class="form-control" name="fecha_final" id="busqueda_final_venta" value="<?php echo date("Y-m-d"); ?>" maxlength="30">
					</div>
				</div>
				<div class="col-12">
					<p class="text-center" style="margin-top: 40px;">
						<button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
					</p>
				</div>
			</div>
		</div>
	</form>
</div>

<?php }else{ ?>
<div class="container-fluid">
	<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" data-form="search" autocomplete="off">
		<input type="hidden" name="modulo" value="venta">
		<input type="hidden" name="eliminar_busqueda" value="eliminar">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-6">
					<p class="text-center" style="font-size: 20px;">
						Fecha de busqueda: <strong><?php echo date("d-m-Y",strtotime($_SESSION['fecha_inicio_venta'])); ?> &nbsp; a &nbsp; <?php echo date("d-m-Y",strtotime($_SESSION['fecha_final_venta'])); ?></strong>
					</p>
				</div>
				<div class="col-12">
					<p class="text-center" style="margin-top: 20px;">
						<button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
					</p>
				</div>
			</div>
		</div>
	</form>
</div>


<div class="container-fluid">
	<?php 
        require_once "./controladores/ventaControlador.php";
        $ins_venta= new ventaControlador();

        echo $ins_venta->paginador_venta_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$pagina[0],"Busqueda",$_SESSION['fecha_inicio_venta'],$_SESSION['fecha_final_venta']);
    ?>
</div>
<?php } ?>