<?php

	if($peticionAjax){
		require_once "../modelos/itemModelo.php";
	}else{
		require_once "./modelos/itemModelo.php";
	}

	class itemControlador extends itemModelo{

		/*--------- Controlador agregar item ---------*/
		public function agregar_item_controlador(){

			$codigo=mainModel::limpiar_cadena($_POST['item_codigo_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['item_nombre_reg']);
			$stock=mainModel::limpiar_cadena($_POST['item_stock_reg']);
			$estado=mainModel::limpiar_cadena($_POST['item_estado_reg']);
			$detalle=mainModel::limpiar_cadena($_POST['item_detalle_reg']);
			$tipo=mainModel::limpiar_cadena($_POST['item_tipo_reg']);


			/*== comprobar campos vacios ==*/
			if($codigo=="" || $nombre=="" || $detalle=="" || $estado=="" || $tipo==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if($tipo!="Producto" && $tipo!="Servicio"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El tipo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if ($tipo=="Servicio") {
				$stock=0;
			}
			if($estado!="Activo" && $estado!="Inactivo"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La estado no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El Codigo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($estado!="Activo" && $estado!="Inactivo" ){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El estado no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				
			}

			if($stock!=""){
				if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La stock no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			

			/*== Comprobando codigo ==*/
			$check_codigo=mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo='$codigo'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El codigo ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			$datos_item_reg=[
				"Codigo"=>$codigo,
				"Nombre"=>$nombre,
				"Stock"=>$stock,
				"Estado"=>$estado,
				"Detalle"=>$detalle,
				"Tipo"=>$tipo,
			];

			$agregar_item=itemModelo::agregar_item_modelo($datos_item_reg);

			if($agregar_item->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"item registrado",
					"Texto"=>"Los datos de la item han sido registrados con exito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el item",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin controlador */





		/*== Controlador paginador item ==*/
		public function paginador_item_controlador($pagina,$registros,$privilegio,$url,$busqueda){


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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item WHERE item_codigo LIKE '%$busqueda%' OR item_nombre LIKE '%$busqueda%' OR item_stock  LIKE '%$busqueda%' OR item_tipo LIKE '%$busqueda%' OR item_estado LIKE '%$busqueda%' OR item_detalle LIKE '%$busqueda%' ORDER BY item_nombre ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM item  ORDER BY item_nombre ASC LIMIT $inicio,$registros";
			}

			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();
			/*if($datos['item_estado']=="Habilitada"){$condestado=="primary"}else{$condestado=="warning"}*/

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas = ceil($total/$registros);

			$tabla.='<div class="table-responsive">
				<table class="table table-dark table-sm">
					<thead>
						<tr class="text-center roboto-medium">
							<th>N°</th>
							<th>CODIGO</th>
							<th>NOMBRE</th>
							<th>STOCK</th>
							<th>TIPO</th>
							<th>ESTADO</th>
							<th>DETALLE</th>
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
							<td>'.$rows['item_codigo'].'</td>
							<td>'.$rows['item_nombre'].'</td>
							<td>'.$rows['item_stock'].'</td>
							<td>'.$rows['item_tipo'].'</td>
							<td><span class="badge badge-'.$rows['item_estado'].'">'.$rows['item_estado'].'</span></td>
							<td>'.$rows['item_detalle'].'</td>
							
							<td>
								<a href="'.SERVERURL.'item-update/'.mainModel::encryption($rows['item_id']).'/" class="btn btn-success">
										<i class="fas fa-edit"></i>	
								</a>
							</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/itemAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="item_id_del" value="'.mainModel::encryption($rows['item_id']).'">
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
					$tabla.='<p class="text-right">Habitaciones desde  '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */


		/*--------- Controlador datos del item ---------*/
		public function datos_item_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return itemModelo::datos_item_modelo($tipo,$id);
		}	
		/* Fin controlador */


		/* Controlador Eliminar item */		
		public function eliminar_item_controlador(){


			/* Recibiendo ID del item */		
			$id=mainModel::decryption($_POST['item_id_del']);
			$id=mainModel::limpiar_cadena($id);


			/* Comprobando item en la base de datos */	
			$check_item=mainModel::ejecutar_consulta_simple("SELECT item_id FROM item WHERE item_id='$id'");
			if ($check_item->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"El item que intenta eliminar no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}
			$eliminar_item=itemModelo::eliminar_item_modelo($id);

			if ($eliminar_item->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"item Eliminado",
					"Texto"=>"El item fue eliminado correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar el item, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
	}/* Fin controlador */

	/*--------- Controlador actualizar datos de la item ---------*/
		public function actualizar_item_controlador(){
			// Recibiendo el ID
			$id=mainModel::decryption($_POST['item_id_up']);
			$id=mainModel::limpiar_cadena($id);
 			
			/* Comprobando item en la base de datos */	
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id'");
			if ($check_user->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro la item en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_user->fetch();
			}
			$codigo=mainModel::limpiar_cadena($_POST['item_codigo_up']);
			$nombre=mainModel::limpiar_cadena($_POST['item_nombre_up']);
			$tipo=mainModel::limpiar_cadena($_POST['item_tipo_up']);
			$estado=mainModel::limpiar_cadena($_POST['item_estado_up']);
			$stock=mainModel::limpiar_cadena($_POST['item_stock_up']);
			$detalle=mainModel::limpiar_cadena($_POST['item_detalle_up']);

		/*== comprobar campos vacios ==*/
			if($codigo=="" || $nombre=="" || $detalle=="" || $estado=="" || $tipo==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if($tipo!="Producto" && $tipo!="Servicio"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El tipo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if ($tipo=="Servicio") {
				$stock=0;
			}
			if($estado!="Activo" && $estado!="Inactivo"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La estado no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El Codigo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El NOMBRE no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El detalle no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($estado!="Activo" && $estado!="Inactivo" ){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El estado no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				
			}

			if($stock!=""){
				if(mainModel::verificar_datos("[0-9]{1,9}",$stock)){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La stock no coincide con el formato solicitado",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}
			}

			

			/*== Comprobando codigo ==*/
			$check_codigo=mainModel::ejecutar_consulta_simple("SELECT item_codigo FROM item WHERE item_codigo='$codigo' AND item_id!='$id'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El codigo ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
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
			$datos_item_up=[
				"Codigo"=>$codigo,
				"Nombre"=>$nombre,
				"Tipo"=>$tipo,
				"Estado"=>$estado,
				"Detalle"=>$detalle,
				"Stock"=>$stock,
				"ID"=>$id
			];

			if (itemModelo::actualizar_item_modelo($datos_item_up)) {
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