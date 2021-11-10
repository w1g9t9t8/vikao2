<?php

	if($peticionAjax){
		require_once "../modelos/loginModelo.php";
	}else{
		require_once "./modelos/loginModelo.php";
	}

	class loginControlador extends loginModelo{
		/*--------- Controlador para iniciar sesión ---------*/
		public function iniciar_sesion_controlador(){
			$usuario=mainModel::limpiar_cadena($_POST['usuario_log']);
			$clave=mainModel::limpiar_cadena($_POST['clave_log']);

					/*--------- Comprobar campos vacios ---------*/
			if ($usuario=="" || $clave=="") {
				echo '
				<script>
					Swal.fire({
					title: "Ocurrio un error inesperado",
					text: "No has llenado todos los campos",
					type: "error",
					confirmButtonText: "Aceptar"
				});

				</script>
				';
				exit();
			}

			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-9]{7,35}",$usuario)){ 
				echo '
				<script>
					Swal.fire({
					title: "Ocurrio un error inesperado",
					text: "El usuario no coincide con el formato",
					type: "error",
					confirmButtonText: "Aceptar"
				});

				</script>
				  ';
				  exit();
			}

			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,30}",$clave)){ 
				echo '
				<script>
					Swal.fire({
					title: "Ocurrio un error inesperado",
					text: "La contraseña no coincide con el formato",
					type: "error",
					confirmButtonText: "Aceptar"
				});

				</script>';
				exit();
			}

			$clave=mainModel::encryption($clave);

			$datos_login=[
				"Usuario"=>$usuario,
				"Clave"=>$clave
			];

			$datos_cuenta=loginModelo::iniciar_sesion_modelo($datos_login);

			if ($datos_cuenta->rowCount()==1) {
				/*== Aqui se almacenan los datos del usuario ==*/
				$row=$datos_cuenta->fetch();
				session_start(['name'=>'SPM']);

				$_SESSION['id_spm']=$row['usuario_id'];
				$_SESSION['nombre_spm']=$row['usuario_nombre'];
				$_SESSION['apellido_spm']=$row['usuario_apellido'];	
				$_SESSION['usuario_spm']=$row['usuario_usuario'];
				$_SESSION['privilegio_spm']=$row['usuario_privilegio'];
				/*== Número unico por sesion, para que no puedan cerrar sesion de otro lado ==*/
				$_SESSION['token_spm']=md5(uniqid(mt_rand(),true));

				return header("Location: ".SERVERURL."Inicio/");
			}else{
				echo '
				<script>
					Swal.fire({
					title: "Ocurrio un error inesperado",
					text: "El usuario o contraseña son incorrectos",
					type: "error",
					confirmButtonText: "Aceptar"
				});

				</script>
				';
			}
		}
		/*--------- Fin controlador ---------*/

		/*--------- Controlador para cierre sesión ---------*/

		public function forzar_cierre_sesion_controlador(){

				session_unset();
				session_destroy();
				if (headers_sent()) {
					return "<script> 
						window.location.href='".SERVERURL."login/' ;
					</script>";
				}else{
					return header("Location: ".SERVERURL."login/");
				}
		}
		/*--------- Fin controlador ---------*/


		/*---------  controlador Cerrar Sesión ---------*/
		public function cerrar_sesion_controlador(){
			session_start(['name'=>'SPM']);
			$token=mainModel::decryption($_POST['token']);
			$usuario=mainModel::decryption($_POST['usuario']);
			if ($token==$_SESSION['token_spm'] && $usuario==$_SESSION['usuario_spm']) {
				session_unset();
				session_destroy();
				$alerta=[
					"Alerta"=>"redireccionar",
					"URL"=>SERVERURL."login/"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hse pudo Cerrar la Sessión en el sistema",
					"Tipo"=>"error"

				];
			}
			echo json_encode($alerta);
		}
		/*--------- Fin controlador ---------*/
	}