<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR GASTOS POR FECHA
    </h3>

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a  href="<?php echo SERVERURL; ?>gastos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR GASTO</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gastos-gastos/"><i class="far fa-calendar-alt"></i> &nbsp; GASTOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gastos-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>gastos-buscar/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSQUEDA</a>
        </li>

    </ul>
</div>
<?php 
	if (!isset($_SESSION['fecha_inicio_gasto']) && empty($_SESSION['fecha_inicio_gasto']) && !isset($_SESSION['fecha_final_gasto']) && empty($_SESSION['fecha_final_gasto'])) {
?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
		<input type="hidden" name="modulo" value="gasto">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-4">
					<div class="form-group">
						<label for="busqueda_inicio_gasto" >Fecha inicial (día/mes/año)</label>
						<input type="date" class="form-control" name="fecha_inicio" id="busqueda_inicio_gasto" value="<?php echo date("Y-m-d"); ?>" maxlength="30">
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="form-group">
						<label for="busqueda_final_gasto" >Fecha final (día/mes/año)</label>
						<input type="date" class="form-control" name="fecha_final" id="busqueda_final_gasto" value="<?php echo date("Y-m-d"); ?>" maxlength="30">
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
		<input type="hidden" name="modulo" value="gasto">
		<input type="hidden" name="eliminar_busqueda" value="eliminar">
		<div class="container-fluid">
			<div class="row justify-content-md-center">
				<div class="col-12 col-md-6">
					<p class="text-center" style="font-size: 20px;">
						Fecha de busqueda: <strong><?php echo date("d-m-Y",strtotime($_SESSION['fecha_inicio_gasto'])); ?> &nbsp; a &nbsp; <?php echo date("d-m-Y",strtotime($_SESSION['fecha_final_gasto'])); ?></strong>
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
        require_once "./controladores/gastoControlador.php";
        $ins_gasto= new gastoControlador();

        echo $ins_gasto->paginador_gasto_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$pagina[0],"Busqueda",$_SESSION['fecha_inicio_gasto'],$_SESSION['fecha_final_gasto']);
    ?>
</div>
<?php } ?>