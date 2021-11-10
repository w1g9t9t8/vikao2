<?php
	$peticionAjax=true;
	require_once "../config/APP.php";

	if(isset($_POST['producto_codigo_reg']) || isset($_POST['producto_id_del']) || isset($_POST['producto_id_up'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/productoControlador.php";
		$ins_producto = new productoControlador();


		/*--------- Agregar un producto ---------*/
		if(isset($_POST['producto_codigo_reg']) && isset($_POST['producto_tipo_reg'])){
			echo $ins_producto->agregar_producto_controlador();
		}

		/*--------- Eliminar un producto ---------*/
		if(isset($_POST['producto_id_del'])){
			echo $ins_producto->eliminar_producto_controlador();
		}

		/*--------- Actualizar un producto ---------*/
		if(isset($_POST['producto_id_up'])){
			echo $ins_producto->actualizar_producto_controlador();
		}

		
	}else{
		session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();
	}