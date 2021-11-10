<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-home"></i> &nbsp; INICIO
	</h3>

</div>

<!-- Content -->
<div class="full-box tile-container">
	<?php 
		require_once "./controladores/clienteControlador.php";
		$ins_cliente= new clienteControlador();

		$total_clientes=$ins_cliente->datos_cliente_controlador("Conteo",1);
	 ?>
	<a href="<?php echo SERVERURL; ?>clienteList/" class="tile">
		<div class="tile-tittle">Clientes</div>
		<div class="tile-icon">
			<i class="fas fa-users fa-fw"></i>
			<p> <?php echo $total_clientes->rowCount(); ?> Registrados</p>
		</div>
	</a>
	
	
	<?php 
			require_once "./controladores/ventaControlador.php";
			$ins_venta= new ventaControlador();

			$total_ventas=$ins_venta->datos_venta_controlador("Conteo_Aprobado",0);
			$total_cancelados=$ins_venta->datos_venta_controlador("Conteo_Cancelado",0);
		 ?>
	<a href="<?php echo SERVERURL; ?>venta-venta/" class="tile">
		<div class="tile-tittle">Ventas</div>
		<div class="tile-icon">
			<i class="fas fa-cart-plus"></i>
			<p><?php echo $total_ventas->rowCount(); ?> Ventas</p>
		</div>
	</a>

	<a href="<?php echo SERVERURL; ?>venta-cancelado/" class="tile">
		<div class="tile-tittle">Cancelados</div>
		<div class="tile-icon">
				<i class="fas fa-clipboard-list fa-fw"></i>
				<p><?php echo $total_cancelados->rowCount(); ?> Ventas Canceladas</p>
		</div>
	</a>
	<?php 
		require_once "./controladores/gastoControlador.php";
		$ins_gasto= new gastoControlador();

		$total_gastos=$ins_gasto->datos_gasto_controlador("Conteo",1);
	 ?>
	<a href="<?php echo SERVERURL; ?>gastos-gastos/" class="tile">
		<div class="tile-tittle">Gastos</div>
		<div class="tile-icon">
			<i class="fas fa-hand-holding-usd"></i>
			<p><?php echo $total_gastos->rowCount(); ?> Gastos</p>
		</div>
	</a>
	
	<?php 
		require_once "./controladores/productoControlador.php";
		$ins_producto= new productoControlador();

		$total_productos=$ins_producto->datos_producto_controlador("Conteo",1);
	 ?>
	<a href="<?php echo SERVERURL; ?>productoList/" class="tile">
		<div class="tile-tittle">Productos</div>
		<div class="tile-icon">
			<i class="fas fa-cookie-bite"></i>
			<p><?php echo $total_productos->rowCount(); ?> Productos</p>
		</div>
	</a>
	<?php 
		require_once "./controladores/proveedorControlador.php";
		$ins_proveedor= new proveedorControlador();

		$total_proveedors=$ins_proveedor->datos_proveedor_controlador("Conteo",1);
	 ?>
	<a href="<?php echo SERVERURL; ?>proveedorList/" class="tile">
		<div class="tile-tittle">Proveedores</div>
		<div class="tile-icon">
			<i class="fas fa-truck"></i>
			<p><?php echo $total_proveedors->rowCount(); ?> Proveedores</p>
		</div>
	</a>

	<?php 
		require_once "./controladores/itemControlador.php";
		$ins_item= new itemControlador();

		$total_items=$ins_item->datos_item_controlador("Conteo",1);
	 ?>
	<a href="<?php echo SERVERURL; ?>item-list/" class="tile">
		<div class="tile-tittle">Items</div>
		<div class="tile-icon">
			<i class="fas fa-pallet fa-fw"></i>
			<p><?php echo $total_items->rowCount(); ?> Registrados</p>
		</div>
	</a>
	

	<?php 
				//permiso de sesiones para usuario 1
		if ($_SESSION['privilegio_spm']==1) {
			require_once "./controladores/usuarioControlador.php";
			$ins_usuario= new usuarioControlador();
			$total_usuarios=$ins_usuario->datos_usuario_controlador("Conteo",0);
	?>
	<a href="<?php echo SERVERURL; ?>usuarioList/" class="tile">
		<div class="tile-tittle">Usuarios</div>
		<div class="tile-icon">
			<i class="fas fa-user-secret fa-fw"></i>
			<p><?php echo $total_usuarios->rowCount(); ?> Registrados</p>
		</div>
	</a>

	
<?php
			}
			//fin de permiso
			?>
	
</div>