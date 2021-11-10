<?php
	session_start(['name'=>'SPM']);
	require_once "../config/APP.php";

	if (isset($_POST['busqueda_inicial']) ||  isset($_POST['eliminar_busqueda']) || isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {
		
		$data_url=[
			"usuario"=>"user-search",
			"venta"=>"venta-buscar",
			"gasto"=>"gastos-buscar",
			"cliente"=>"client-search",
			"producto"=>"producto-search",
			"item"=>"item-search",
			"proveedor"=>"proveedor-search"
		];

		if (isset($_POST['modulo'])) {
			$modulo=$_POST['modulo'];
			if (!isset($data_url[$modulo])) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo realizar la busqueda",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
			}
		}else{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo realizar la busqueda debido a un error de configuracion",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();

		}

		if ($modulo=="venta" || $modulo=="gasto") {
			$fecha_inicio="fecha_inicio_".$modulo;
			$fecha_final="fecha_final_".$modulo;

			// iniciar busqueda

			if (isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {
				if ($_POST['fecha_inicio']=="" || $_POST['fecha_final']=="") {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Introduce una fecha de inicio y una fecha final",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

				$_SESSION[$fecha_inicio]=$_POST['fecha_inicio'];
				$_SESSION[$fecha_final]=$_POST['fecha_final'];			
			}

			// eliminar busqueda

			if (isset($_POST['eliminar_busqueda'])) {
				unset($_SESSION[$fecha_inicio]);
				unset($_SESSION[$fecha_final]);
			}
		}else{
			$name_var="busqueda_".$modulo;

			//iniciar busqueda
			if (isset($_POST['busqueda_inicial'])) {
				if ($_POST['busqueda_inicial']=="") {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrio un error inesperado",
						"Texto"=>"Introduce una fecha de inicio y una fecha final",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

				$_SESSION[$name_var]=$_POST['busqueda_inicial'];
			}

			// eliminar busqueda

			if (isset($_POST['eliminar_busqueda'])) {
				unset($_SESSION[$name_var]);
			}
		}

		//redireccionar
		$url=$data_url[$modulo];

		$alerta=[
			"Alerta"=>"redireccionar",
			"URL"=>SERVERURL.$url."/"
		];
		echo json_encode($alerta);
	}else{
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();
	}