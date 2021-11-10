<?php
	$peticionAjax=true;
	require_once "../config/APP.php";

	if(isset($_POST['buscar_cliente']) || isset($_POST['id_agregar_cliente'])  || isset($_POST['id_eliminar_cliente']) || isset($_POST['buscar_producto']) || isset($_POST['id_agregar_producto']) || isset($_POST['id_eliminar_producto']) || isset($_POST['venta_fecha_pago_reg']) || isset($_POST['venta_id_up']) || isset($_POST['venta_codigo_del'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/ventaControlador.php";
		$ins_venta = new ventaControlador();

		/*--------- Buscar Cliente ---------*/
		if (isset($_POST['buscar_cliente'])) {
			echo $ins_venta->buscar_cliente_venta_controlador();
		}

		/*--------- Agregar Cliente ---------*/
		if (isset($_POST['id_agregar_cliente'])) {
			echo $ins_venta->agregar_cliente_venta_controlador();
		}

		/*--------- eliminar Cliente ---------*/
		if (isset($_POST['id_eliminar_cliente'])) {
			echo $ins_venta->eliminar_cliente_venta_controlador();
		}
		
		/*--------- Buscar Producto ---------*/
		if (isset($_POST['buscar_producto'])) {
			echo $ins_venta->buscar_producto_venta_controlador();
		}

		/*--------- Agregar Producto ---------*/
		if (isset($_POST['id_agregar_producto'])) {
			echo $ins_venta->agregar_producto_venta_controlador();
		}

		/*--------- Eliminar Producto ---------*/
		if (isset($_POST['id_eliminar_producto'])) {
			echo $ins_venta->eliminar_producto_venta_controlador();
		}

		/*--------- Agregar venta ---------*/
		if (isset($_POST['venta_fecha_pago_reg'])) {
			echo $ins_venta->agregar_venta_controlador();
		}

		/*--------- Actualizar un venta ---------*/
		if(isset($_POST['venta_id_up'])){
			echo $ins_venta->actualizar_venta_controlador();
		}

		/*--------- Eliminar un venta ---------*/
		if(isset($_POST['venta_codigo_del'])){
			echo $ins_venta->eliminar_venta_controlador();
		}

		}else{
		session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();
	}