<?php

	if($peticionAjax){
		require_once "../modelos/clienteModelo.php";
	}else{
		require_once "./modelos/clienteModelo.php";
	}

	class clienteControlador extends clienteModelo{

		/*--------- Controlador agregar cliente ---------*/
		public function agregar_cliente_controlador(){
			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);


			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" || $apellido==""){
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
			if(mainModel::verificar_datos("[0-9]{8}",$dni)){
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

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
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
			$check_dni=mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");
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

		


			

			

			$datos_cliente_reg=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				
			];

			$agregar_cliente=clienteModelo::agregar_cliente_modelo($datos_cliente_reg);

			if($agregar_cliente->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Cliente registrado",
					"Texto"=>"Los datos del cliente han sido registrados con exito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el cliente",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin controlador */

		/*== Controlador paginador cliente ==*/
		public function paginador_cliente_controlador($pagina,$registros,$privilegio,$url,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
		

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina=(isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio=($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;

			if (isset($busqueda) && $busqueda!="") {
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE cliente_dni LIKE '%$busqueda%' OR cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_telefono LIKE '%$busqueda%' OR cliente_direccion LIKE '%$busqueda%' ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY cliente_nombre ASC LIMIT $inicio,$registros";
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
							<th>DNI</th>
							<th>NOMBRE</th>
							<th>APELLIDO</th>
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
							<td>'.$rows['cliente_dni'].'</td>
							<td>'.$rows['cliente_nombre'].'</td>
							<td>'.$rows['cliente_apellido'].'</td>
							<td>'.$rows['cliente_telefono'].'</td>
							<td>'.$rows['cliente_direccion'].'</td>
							<td>
								<a href="'.SERVERURL.'client-update/'.mainModel::encryption($rows['cliente_id']).'/" class="btn btn-success">
										<i class="fas fa-edit"></i>	
								</a>
							</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="cliente_id_del" value="'.mainModel::encryption($rows['cliente_id']).'">
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
					$tabla.='<p class="text-right">Clientes desde  '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */

		/* Controlador Eliminar cliente */		
		public function eliminar_cliente_controlador(){

			/* Recibiendo ID del cliente */		
			$id=mainModel::decryption($_POST['cliente_id_del']);
			$id=mainModel::limpiar_cadena($id);


			/* Comprobando cliente en la base de datos */	
			$check_cliente=mainModel::ejecutar_consulta_simple("SELECT cliente_id FROM cliente WHERE cliente_id='$id'");
			if ($check_cliente->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El cliente que intenta eliminar no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}

		

			$eliminar_cliente=clienteModelo::eliminar_cliente_modelo($id);

			if ($eliminar_cliente->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"cliente Eliminado",
					"Texto"=>"El cliente fue eliminado correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar el cliente, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}
		/* Fin controlador */

		/*--------- Controlador datos del cliente ---------*/
		public function datos_cliente_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return clienteModelo::datos_cliente_modelo($tipo,$id);
		}	
		/* Fin controlador */	

		/*--------- Controlador actualizar datos del cliente ---------*/
		public function actualizar_cliente_controlador(){
			// Recibiendo el ID
			$id=mainModel::decryption($_POST['cliente_id_up']);
			$id=mainModel::limpiar_cadena($id);

			/* Comprobando cliente en la base de datos */	
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");
			if ($check_user->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro el cliente en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_user->fetch();
			}

			$dni=mainModel::limpiar_cadena($_POST['cliente_dni_up']);
			$nombre=mainModel::limpiar_cadena($_POST['cliente_nombre_up']);
			$apellido=mainModel::limpiar_cadena($_POST['cliente_apellido_up']);
			$telefono=mainModel::limpiar_cadena($_POST['cliente_telefono_up']);
			$direccion=mainModel::limpiar_cadena($_POST['cliente_direccion_up']);

			/*== comprobar campos vacios ==*/
			if($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion=="" ){
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
			if(mainModel::verificar_datos("[0-9]{8}",$dni)){
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

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
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

			if ($dni!=$campos['cliente_dni']) {
				$check_dni=mainModel::ejecutar_consulta_simple("SELECT cliente_dni FROM cliente WHERE cliente_dni='$dni'");
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
			$datos_cliente_up=[
				"DNI"=>$dni, 
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Direccion"=>$direccion,
				"ID"=>$id
			];

			if (clienteModelo::actualizar_cliente_modelo($datos_cliente_up)) {
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