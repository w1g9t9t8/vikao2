<?php

	if($peticionAjax){
		require_once "../modelos/productoModelo.php";
	}else{
		require_once "./modelos/productoModelo.php";
	}

	class productoControlador extends productoModelo{

		/*--------- Controlador agregar producto ---------*/
		public function agregar_producto_controlador(){


			$codigo=mainModel::limpiar_cadena($_POST['producto_codigo_reg']);
			$tipo=mainModel::limpiar_cadena($_POST['producto_tipo_reg']);
			$costo=mainModel::limpiar_cadena($_POST['producto_costo_reg']);
			$estado=mainModel::limpiar_cadena($_POST['producto_estado_reg']);
			$unidad=mainModel::limpiar_cadena($_POST['producto_unidad_reg']);
			$porcentaje=mainModel::limpiar_cadena($_POST['producto_porcentaje_reg']);
			$nombre=mainModel::limpiar_cadena($_POST['producto_nombre_reg']);
			$peso=mainModel::limpiar_cadena($_POST['producto_peso_reg']);
			$stock=mainModel::limpiar_cadena($_POST['producto_stock_reg']);

			

			/*== comprobar campos vacios ==*/
			if($codigo=="" || $tipo=="" || $costo=="" || $estado=="" || $porcentaje=="" || $nombre=="" || $unidad==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			

			if($unidad!="kg." && $unidad!="g."){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La unidad no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
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

			if(mainModel::verificar_datos("[0-9 .]{1,9}",$costo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			/*== Comprobando codigo ==*/
			$check_codigo=mainModel::ejecutar_consulta_simple("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El Codigo ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[0-9-]{1,5}",$stock)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El stock no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($tipo!="Taza" && $tipo!="Consumo" && $tipo!="Hojuelas"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El tipo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($porcentaje!="50" && $porcentaje!="75" && $porcentaje!="100"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El porcentaje no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}



			$datos_producto_reg=[
				"Codigo"=>$codigo,
				"Tipo"=>$tipo,
				"Costo"=>$costo,
				"Estado"=>$estado,
				"Porcentaje"=>$porcentaje,
				"Nombre"=>$nombre,
				"Peso"=>$peso,
				"Unidad"=>$unidad,
				"Stock"=>$stock
			];

			$agregar_producto=productoModelo::agregar_producto_modelo($datos_producto_reg);

			if($agregar_producto->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"producto registrado",
					"Texto"=>"Los datos de la producto han sido registrados con exito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido registrar el producto",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		} /* Fin controlador */

		/*== Controlador paginador producto ==*/
		public function paginador_producto_controlador($pagina,$registros,$privilegio,$url,$busqueda){

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
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%' OR producto_stock LIKE '%$busqueda%' OR producto_tipo LIKE '%$busqueda%' OR producto_costo LIKE '%$busqueda%' OR producto_estado LIKE '%$busqueda%' OR producto_porcentaje LIKE '%$busqueda%' ORDER BY producto_id ASC LIMIT $inicio,$registros";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM producto ORDER BY producto_nombre ASC LIMIT $inicio,$registros";
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
							<th>N°</th>
							<th>CÓDIGO</th>
							<th>NOMBRE</th>
							<th>STOCK</th>
							<th>TIPO</th>
							<th>COSTO</th>
							<th>ESTADO</th>
							<th>PORCENTAJE</th>
							<th>ACTUALIZAR</th>
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
							<td>'.$rows['producto_codigo'].'</td>
							<td>'.$rows['producto_nombre'].'</td>
							<td>'.$rows['producto_stock'].'</td>
							<td>'.$rows['producto_tipo'].'</td>
							<td>'.MONEDA.number_format($rows['producto_costo'],2,'.',',').'</td>
							<td>'.$rows['producto_estado'].'</td>
							<td>'.$rows['producto_porcentaje'].'%</td>
							<td>
								<a href="'.SERVERURL.'producto-update/'.mainModel::encryption($rows['producto_id']).'/" class="btn btn-success">
										<i class="fas fa-sync-alt"></i>	
								</a>
							</td>
							<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/productoAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="producto_id_del" value="'.mainModel::encryption($rows['producto_id']).'">
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
					$tabla.='<p class="text-right">Hacbitaciones desde  '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */

		/* Controlador Eliminar producto */		
		public function eliminar_producto_controlador(){

			/* Recibiendo ID del producto */		
			$id=mainModel::decryption($_POST['producto_id_del']);
			$id=mainModel::limpiar_cadena($id);

			

			/* Comprobando producto en la base de datos */	
			$check_producto=mainModel::ejecutar_consulta_simple("SELECT producto_id FROM producto WHERE producto_id='$id'");
			if ($check_producto->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"La producto que intenta eliminar no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}



			$eliminar_producto=productoModelo::eliminar_producto_modelo($id);

			if ($eliminar_producto->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Producto Eliminado",
					"Texto"=>"El producto fue eliminada correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar el Producto, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}
		/* Fin controlador */

		/*--------- Controlador datos del producto ---------*/
		public function datos_producto_controlador($tipo,$id){
			$tipo=mainModel::limpiar_cadena($tipo);

			$id=mainModel::decryption($id);
			$id=mainModel::limpiar_cadena($id);

			return productoModelo::datos_producto_modelo($tipo,$id);
		}	
		/* Fin controlador */

		/*--------- Controlador actualizar datos de la producto ---------*/
		public function actualizar_producto_controlador(){
			// Recibiendo el ID
			$id=mainModel::decryption($_POST['producto_id_up']);
			$id=mainModel::limpiar_cadena($id);
 			
			/* Comprobando producto en la base de datos */	
			$check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM producto WHERE producto_id='$id'");
			if ($check_user->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro la producto en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_user->fetch();
			}
			$codigo=mainModel::limpiar_cadena($_POST['producto_codigo_up']);
			$nombre=mainModel::limpiar_cadena($_POST['producto_nombre_up']);
			$tipo=mainModel::limpiar_cadena($_POST['producto_tipo_up']);
			$costo=mainModel::limpiar_cadena($_POST['producto_costo_up']);
			$peso=mainModel::limpiar_cadena($_POST['producto_peso_up']);
			$unidad=mainModel::limpiar_cadena($_POST['producto_unidad_up']);
			$estado=mainModel::limpiar_cadena($_POST['producto_estado_up']);
			$porcentaje=mainModel::limpiar_cadena($_POST['producto_porcentaje_up']);
			$stock=mainModel::limpiar_cadena($_POST['producto_stock_up']);

				/*== comprobar campos vacios ==*/
			if($codigo=="" || $tipo=="" || $costo=="" || $estado=="" || $porcentaje=="" || $nombre=="" || $unidad==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			

			if($unidad!="kg." && $unidad!="g."){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La unidad no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
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

			if(mainModel::verificar_datos("[0-9 .]{1,9}",$costo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El APELLIDO no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}


			/*== Comprobando codigo ==*/
			$check_codigo=mainModel::ejecutar_consulta_simple("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo' AND producto_id!='$id'");
			if($check_codigo->rowCount()>0){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El Codigo ingresado ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			if(mainModel::verificar_datos("[0-9-]{1,5}",$stock)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El stock no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($tipo!="Taza" && $tipo!="Consumo" && $tipo!="Hojuelas"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El tipo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($porcentaje!="50" && $porcentaje!="75" && $porcentaje!="100"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El porcentaje no coincide con el formato solicitado",
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
			$datos_producto_up=[
				"Codigo"=>$codigo,
				"Nombre"=>$nombre,
				"Tipo"=>$tipo,
				"Costo"=>$costo,
				"Peso"=>$peso,
				"Unidad"=>$unidad,
				"Estado"=>$estado,
				"Porcentaje"=>$porcentaje,
				"Stock"=>$stock,
				"ID"=>$id
			];

			if (productoModelo::actualizar_producto_modelo($datos_producto_up)) {
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