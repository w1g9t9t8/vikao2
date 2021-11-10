<?php
	$peticionAjax=true;
	require_once "../config/APP.php";

	if(isset($_POST['buscar_proveedor']) || isset($_POST['id_agregar_proveedor'])  || isset($_POST['id_eliminar_proveedor']) || isset($_POST['buscar_item']) || isset($_POST['id_agregar_item']) || isset($_POST['id_eliminar_item']) || isset($_POST['gasto_fecha_pago_reg']) || isset($_POST['gasto_codigo_del'])|| isset($_POST['gasto_id_up'])){

		/*--------- Instancia al controlador ---------*/
		require_once "../controladores/gastoControlador.php";
		$ins_gasto = new gastoControlador();

		/*--------- Buscar proveedor ---------*/
		if (isset($_POST['buscar_proveedor'])) {
			echo $ins_gasto->buscar_proveedor_gasto_controlador();
		}

		/*--------- Agregar proveedor ---------*/
		if (isset($_POST['id_agregar_proveedor'])) {
			echo $ins_gasto->agregar_proveedor_gasto_controlador();
		}

		/*--------- eliminar proveedor ---------*/
		if (isset($_POST['id_eliminar_proveedor'])) {
			echo $ins_gasto->eliminar_proveedor_gasto_controlador();
		}
		
		/*--------- Buscar item ---------*/
		if (isset($_POST['buscar_item'])) {
			echo $ins_gasto->buscar_item_gasto_controlador();
		}

		/*--------- Agregar item ---------*/
		if (isset($_POST['id_agregar_item'])) {
			echo $ins_gasto->agregar_item_gasto_controlador();
		}

		/*--------- Eliminar item ---------*/
		if (isset($_POST['id_eliminar_item'])) {
			echo $ins_gasto->eliminar_item_gasto_controlador();
		}

		/*--------- Agregar gasto ---------*/
		if (isset($_POST['gasto_fecha_pago_reg'])) {
			echo $ins_gasto->agregar_gasto_controlador();
		}

		/*--------- Eliminar un gasto ---------*/
		if(isset($_POST['gasto_codigo_del'])){
			echo $ins_gasto->eliminar_gasto_controlador();
		}

		/*--------- Actualizar un gasto ---------*/
		if(isset($_POST['gasto_id_up'])){
			echo $ins_gasto->actualizar_gasto_controlador();
		}

		}else{
		session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();
	}