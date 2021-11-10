<?php

	if($peticionAjax){
		require_once "../modelos/proveedorModelo.php";
	}else{
		require_once "./modelos/proveedorModelo.php";
	}

	class proveedorControlador extends proveedorModelo{

		/*--------- Controlador agregar proveedor ---------*/
		public function agregar_proveedor_controlador(){
			$dni=mainModel::limpiar_cadena($_POST['proveedor_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['proveedor_nombre_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['proveedor_telefono_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['proveedor_direccion_reg']);


			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[0-9]{8,11}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}



			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9]{9}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El Celular no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La DIRECCION no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			

			/*== Comprobando DNI ==*/
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT proveedor_dni FROM proveedor WHERE proveedor_dni='$dni'");
			if($check_dni->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			

			

			$datos_proveedor_reg=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
			];

			$agregar_proveedor=proveedorModelo::agregar_proveedor_modelo($datos_proveedor_reg);

			if($agregar_proveedor->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"proveedor registrado",
					"Texto"=>"Los datos del proveedor han sido registrados con exito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el proveedor",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin controlador */

		/*== Controlador paginador proveedor ==*/
		public function paginador_proveedor_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$id=mainModel::limpiar_cadena($id);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina=(isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio=($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;

			if (isset($busqueda) && $busqueda!="") {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedor WHERE ((proveedor_id!='$id') AND (proveedor_dni LIKE '%$busqueda%' OR proveedor_nombre LIKE '%$busqueda%' OR proveedor_telefono LIKE '%$busqueda%' OR proveedor_direccion LIKE '%$busqueda%')) ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedor ORDER BY proveedor_nombre ASC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas = ceil($total/$registros);

			$tabla.='<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>#</th>
							<th>DNI o RUC</th>
							<th>NOMBRE</th>
							<th>CELULAR</th>
							<th>DIRECCIÓN</th>
							<th>EDITAR</th>
							<th>ELIMINAR</th>
						</tr>
					</thead>
					<tbody id="myTable">';
				if ($total>=1 && $pagina<=$Npaginas) {
					
					$contador=$inicio+1;
					$reg_inicio=$inicio+1;
					foreach($datos as $rows){
						$tabla.='
						<tr class="text-center" >
							<td>'.$contador.'</td>
							<td>'.$rows['proveedor_dni'].'</td>
							<td>'.$rows['proveedor_nombre'].'</td>
							<td>'.$rows['proveedor_telefono'].'</td>
							<td>'.$rows['proveedor_direccion'].'</td>
							<td>
								<a href="'.SERVERURL.'proveedor-update/'.mainModel::encryption($rows['proveedor_id']).'/" class="btn btn-success">
										<i class="fas fa-edit"></i>	
								</a>
							</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/proveedorAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="proveedor_id_del" value="'.mainModel::encryption($rows['proveedor_id']).'">
									<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
									</button>
								</form>
							</td>
						</tr>
						';
						$contador++;
					}
					$reg_final=$contador-1;
				}else{
					if ($total>=1) {
						$tabla.='<tr class="text-center">
						<td colspan="9">
						<a href="'.$url.'" class=="btn btn-raised btn-primary btn-sm"> Haga click aquí para recargar la lista</a>
						</td> 
						</tr>';
					}else{
						$tabla.='<tr class="text-center">
						<td colspan="9">No hay registros en el sistema</td> 
						</tr>';
					}
					
				}

				$tabla.='</tbody></table></div>';


				if ($total>=1 && $pagina<=$Npaginas) {
					$tabla.='<p class="text-right">Proveedores desde  '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */

		/* Controlador Eliminar proveedor */		
		public function eliminar_proveedor_controlador(){

			/* Recibiendo ID del proveedor */		
			$id=mainModel::decryption($_POST['proveedor_id_del']);
			$id=mainModel::limpiar_cadena($id);


			/* Comprobando proveedor en la base de datos */	
			$check_proveedor=mainModel::ejecutar_consulta_simple("SELECT proveedor_id FROM proveedor WHERE proveedor_id='$id'");
			if ($check_proveedor->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El proveedor que intenta eliminar no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}

		

			$eliminar_proveedor=proveedorModelo::eliminar_proveedor_modelo($id);

			if ($eliminar_proveedor->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"proveedor Eliminado",
					"Texto"=>"El proveedor fue eliminado correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar el proveedor, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}
		/* Fin controlador */

		/*--------- Controlador datos del proveedor ---------*/
		public function datos_proveedor_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return proveedorModelo::datos_proveedor_modelo($tipo,$id);
		}	
		/* Fin controlador */	

		/*--------- Controlador actualizar datos del proveedor ---------*/
		public function actualizar_proveedor_controlador(){
			// Recibiendo el ID
			$id=mainModel::decryption($_POST['proveedor_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/* Comprobando proveedor en la base de datos */	
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM proveedor WHERE proveedor_id='$id'");
			if ($check_user->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro el proveedor en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_user->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['proveedor_dni_up']);
			$nombre=mainModel::limpiar_cadena($_POST['proveedor_nombre_up']);
			$telefono=mainModel::limpiar_cadena($_POST['proveedor_telefono_up']);
			$direccion=mainModel::limpiar_cadena($_POST['proveedor_direccion_up']);

			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" || $telefono=="" || $direccion=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[0-9]{8,11}",$dni)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}



			if($telefono!=""){
				if(mainModel::verificar_datos("[0-9]{9}",$telefono)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El Celular no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			if($direccion!=""){
				if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La DIRECCION no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			/*== Comprobando DNI ==*/

			if ($dni!=$campos['proveedor_dni']) {
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT proveedor_dni FROM proveedor WHERE proveedor_dni='$dni'");
				if($check_dni->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
				}
			}
			
			// Comprobando Privilegios
			session_start(['name'=>'SPM']);
			if ($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No tienes permisos necesarios para hacer la operacion",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Preparando datos para enviarlos al modelo ==*/
			$datos_proveedor_up=[
				"DNI"=>$dni, 
				"Nombre"=>$nombre,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"ID"=>$id
			];

			if (proveedorModelo::actualizar_proveedor_modelo($datos_proveedor_up)) {
				$alerta=[
		 					"Alerta"=>"recargar",
						"Titulo"=>"Datos Actualizados",
						"Texto"=>"Los datos fueron actualizados correctamente",
						"Tipo"=>"success"
						];
						
			}else{
				$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Los datos no fueron actualizados, intente nuevamente",
						"Tipo"=>"error"
						];
						 
			}
			echo json_encode($alerta);

		}	
		/* Fin controlador */

	}