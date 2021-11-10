<?php

	if($peticionAjax){
		require_once "../modelos/gastoModelo.php";
	}else{
		require_once "./modelos/gastoModelo.php";
	}

	class gastoControlador extends gastoModelo{

		/*--------- Controlador buscar proveedor ---------*/
		public function buscar_proveedor_gasto_controlador(){
			/*--------- Recuperar Texto ---------*/
			$proveedor=mainModel::limpiar_cadena($_POST['buscar_proveedor']);

			/*--------- Comprobar Texto ---------*/
			if ($proveedor=="") {
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes ingresar algun dato
                    </p>
                </div>';
                exit();
			}

			/*--------- Seleccionando proveedores en bd---------*/

			$datos_proveedor=mainModel::ejecutar_consulta_simple("SELECT * FROM proveedor WHERE proveedor_dni LIKE '%$proveedor%' OR proveedor_nombre LIKE '%$proveedor%'  OR proveedor_telefono LIKE '%$proveedor%' ORDER BY proveedor_nombre ASC");

			if ($datos_proveedor->rowCount()>=1) {
				$datos_proveedor=$datos_proveedor->fetchAll();

				$tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
				foreach($datos_proveedor as $rows){
					$tabla.='<tr class="text-center">
                                    <td>'.$rows['proveedor_nombre'].' - '.$rows['proveedor_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="agregar_proveedor('.$rows['proveedor_id'].')"><i class="fas fa-user-plus"></i></button>
                                    </td>
                                </tr>';
				}
				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún proveedor en el sistema que coincida con <strong>“'.$proveedor.'”</strong>
                    </p>
                </div>';
                exit();
			}
		}//Fin controlador

		/*--------- Controlador agregar proveedor ---------*/
		public function agregar_proveedor_gasto_controlador(){
			/*--------- Recuperar Texto ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_agregar_proveedor']);

			/*--------- Comprobando proveedores en bd---------*/

			$check_proveedor=mainModel::ejecutar_consulta_simple("SELECT * FROM proveedor WHERE proveedor_id='$id'");

			if ($check_proveedor->rowCount()<=0) {

				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se encontró el proveedor seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();


				
			}else{
				$campos=$check_proveedor->fetch();
				
			}

			/*--------- Iniciado la sesión ---------*/

			session_start(['name'=>'SPM']);

			if (empty($_SESSION['datos_proveedor'])) {
				$_SESSION['datos_proveedor']=[
					"ID"=>$campos['proveedor_id'],
					"DNI"=>$campos['proveedor_dni'],
					"Nombre"=>$campos['proveedor_nombre']
				];	
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"proveedor agregado correctamente",
					"Texto"=>"El proveedor se agregó correctamente",
					"Tipo"=>"success"
					];
					echo json_encode($alerta);		
			}	else{
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se agrego el proveedor a la gasto",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
			}		

		}//Fin controlador

		/*--------- Controlador eliminar proveedor ---------*/
		public function eliminar_proveedor_gasto_controlador(){
			
			/*--------- Iniciado la sesión ---------*/
			session_start(['name'=>'SPM']);	

			unset($_SESSION['datos_proveedor']);

			if (empty($_SESSION['datos_proveedor'])) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"proveedor Eliminado",
					"Texto"=>"Se elimino al proveedor correctamente",
					"Tipo"=>"success"
					];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo eliminar al proveedor",
					"Tipo"=>"error"
					];
			}
			echo json_encode($alerta);

		}//Fin controlador


		/*--------- Controlador buscar item ---------*/
		public function buscar_item_gasto_controlador(){
			/*--------- Recuperar Texto ---------*/
			$item=mainModel::limpiar_cadena($_POST['buscar_item']);

			/*--------- Comprobar Texto ---------*/
			if ($item=="") {
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes ingresar algun dato
                    </p>
                </div>';
                exit();
			}

			/*--------- Seleccionando items en bd---------*/

			$datos_item=mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE (item_codigo LIKE '%$item%' OR item_nombre LIKE '%$item%' OR item_stock LIKE '%$item%') AND (item_estado='Activo') ORDER BY item_nombre ASC");

			if ($datos_item->rowCount()>=1) {
				$datos_item=$datos_item->fetchAll();

				$tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
				foreach($datos_item as $rows){
					$tabla.='<tr class="text-center">
                                    <td>'.$rows['item_codigo'].' - '.$rows['item_nombre'].' || Disponible: '.$rows['item_stock'].' unidades</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="modal_agregar_item('.$rows['item_id'].')"><i class="fas fa-box-open"></i></button>
                                    </td>
                                </tr>';
				}
				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún item en el sistema que coincida con <strong>“'.$item.'”</strong> Comprueba si esta Activo por favor
                    </p>
                </div>';
                exit();
			}
		}//Fin controlador


		/*--------- Controlador agregar item ---------*/
		public function agregar_item_gasto_controlador(){

			/*--------- Recuperar id del item ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_agregar_item']);

			/*--------- Comprobando item en bd---------*/
			$check_item=mainModel::ejecutar_consulta_simple("SELECT * FROM item WHERE item_id='$id' AND item_estado='Activo' ");
			if ($check_item->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se encontro el item, intente nuevamente.",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
			}else{
				$campos=$check_item->fetch();
			}

			/*--------- Recuperando detalles de gasto---------*/
			$costo=mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);
			$cantidad=mainModel::limpiar_cadena($_POST['detalle_cantidad']);
		
			


			/*== comprobar campos vacios ==*/
			if($cantidad==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No has llenado todos los campos que son obligatorios",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if($costo==""){
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
			if(mainModel::verificar_datos("[0-9]{1,7}",$cantidad)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La cantidad no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			/*== Verificando integridad de los datos ==*/
			if(mainModel::verificar_datos("[0-9 .]{1,7}",$costo)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El costo no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			session_start(['name'=>'SPM']);
			if (empty($_SESSION['datos_item'][$id])) {
				

				$_SESSION['datos_item'][$id]=[
					"ID"=>$campos['item_id'],
					"Codigo"=>$campos['item_codigo'],	
					"Nombre"=>$campos['item_nombre'],	
					"Stock"=>$campos['item_stock'],					
					"Estado"=>$campos['item_estado'],
					"Detalle"=>$campos['item_detalle'],
					"Tipo"=>$campos['item_tipo'],
									

					"Cantidad"=>$cantidad,
					"Costo"=>$costo,
					
				];
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"item Agregado",
					"Texto"=>"El item se agregó correctamente.",
					"Tipo"=>"success"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El item que intenta agregar ya se encuentra seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

		}//Fin controlador


		/*--------- Controlador eliminar item ---------*/
		public function eliminar_item_gasto_controlador(){

			/*--------- Recuperar Texto ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_eliminar_item']);
			
			/*--------- Iniciado la sesión ---------*/
			session_start(['name'=>'SPM']);	

			unset($_SESSION['datos_item'][$id]);

			if (empty($_SESSION['datos_item'][$id])) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"item Eliminado",
					"Texto"=>"Se elimino el item correctamente",
					"Tipo"=>"success"
					];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo eliminar al item",
					"Tipo"=>"error"
					];
			}
			echo json_encode($alerta);

		}//Fin controlador


			/*--------- agregar gasto ---------*/
		public function agregar_gasto_controlador(){

				session_start(['name'=>'SPM']);
				

				/*--------- comprobando items ---------*/
				if ($_SESSION['gasto_item']==0) {
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se seleccionó ningun item",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

				/*--------- comprobando proveedor ---------*/
				if (empty($_SESSION['datos_proveedor'])) {
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se seleccionó ningún proveedor",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}


				/*--------- Recibiendo Datos de Formulario ---------*/
				$fecha_pago=mainModel::limpiar_cadena($_POST['gasto_fecha_pago_reg']);
				$hora_pago=mainModel::limpiar_cadena($_POST['gasto_hora_pago_reg']);

				$estado=mainModel::limpiar_cadena($_POST['gasto_estado_reg']);
				$observacion=mainModel::limpiar_cadena($_POST['gasto_observacion_reg']);


				/*--------- Comprobando Integridad de datos---------*/
				if(mainModel::verificar_fecha($fecha_pago)){ 
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No modifique la fecha por defecto por favor",
						"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
				}

				if(mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora_pago)){ 
					$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No modifique la hora por defecto por favor",
							"Tipo"=>"error"
							];
							echo json_encode($alerta);
							exit();
				}

				

				if ($observacion!="") {
				if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}",$observacion)){ 
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La observacion no coincide con el formato",
						"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}
				} 
				if ($estado!="Aprobado" ) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El estado no coincide con el formato",
						"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
					}	


				/*--------- Formateando las Fechas, horas y totales---------*/
				$total_gasto=number_format($_SESSION['gasto_total'],2,'.','');

				
				$fecha_pago=date("Y-m-d",strtotime($fecha_pago));

				
				$hora_pago=date("H:i",strtotime($hora_pago));

				

				/*--------- Generando codigo de gasto, si el gasto no tiene datos= 1, si tenemos un registro= 2---------*/
				$correlativo=mainModel::ejecutar_consulta_simple("SELECT gasto_id FROM gasto");

				$correlativo=($correlativo->rowCount())+1;

				$codigo=mainModel::generar_codigo_aleatorio("VK",7,$correlativo);



				/*--------- Agregar gasto--------*/
				$datos_gasto_reg=[
					"Codigo"=>$codigo,
					"Fecha"=>$fecha_pago,
					"Hora"=>$hora_pago,
					"Cantidad"=>$_SESSION['gasto_item'],
					"Total"=>$total_gasto,
					"Estado"=>$estado,
					"Observacion"=>$observacion,
					"Usuario"=>$_SESSION['id_spm'],
					"Proveedor"=>$_SESSION['datos_proveedor']['ID']
				];

					

				$agregar_gasto=gastoModelo::agregar_gasto_modelo($datos_gasto_reg);

				if ($agregar_gasto->rowCount()!=1) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado(Error:001)",
						"Texto"=>"No se pudo registrar la gasto",
						"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
				}

			/*--------- Agregar Detalle gasto --------*/
				$errores_detalle=0;
				foreach($_SESSION['datos_item'] as $items){
					$subtotalunitario=$items['Costo']*$items['Cantidad'];
					$costo=number_format($subtotalunitario,2,'.','');
					$cantidad=$items['Cantidad'];
					$descripcion=$items['Codigo']." - ".$items['Nombre'];
					$preciounitario=$items['Costo'];
					$stocknuevo=$items['Stock']+$items['Cantidad'];


					$datos_detalle_reg=[
						"Cantidad"=>$items['Cantidad'],
						"Unitario"=>$preciounitario,
						"Costo"=>$costo,
						"Descripcion"=>$descripcion,
						"Gasto"=>$codigo,
						"Item"=>$items['ID']
					];



					$agregar_detalle=gastoModelo::agregar_detalle_modelo($datos_detalle_reg);

					if ($items['Tipo']=="Producto") {
						$sql=mainModel::conectar()->prepare("UPDATE item SET item_stock=:Stock WHERE item_id=:ID");
						$sql->bindParam(":Stock",$stocknuevo);
						$sql->bindParam(":ID",$items['ID']);

						$sql->execute();
					}

					if ($agregar_detalle->rowCount()!=1) {
						$errores_detalle=1;
						break;
					}
				}


				if ($errores_detalle==0) {
					unset($_SESSION['datos_proveedor']);
					unset($_SESSION['datos_item']);					
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Gasto Registrado",
						"Texto"=>"Los datos de la gasto han sido registrados en el sistema",
						"Tipo"=>"success"
						];
				}else{
					gastoModelo::eliminar_gasto_modelo($codigo,"Detalle");
					gastoModelo::eliminar_gasto_modelo($codigo,"Gasto");
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado(Error:003)",
						"Texto"=>"No se pudo registrar el detalle",
						"Tipo"=>"error"
						];
						
				}
				echo json_encode($alerta);
				
			}//Fin controlador

			/*--------- Controlador datos gasto ---------*/
		public function datos_gasto_controlador($tipo,$id){
				$tipo=mainModel::limpiar_cadena($tipo);

				$id=mainModel::decryption($id);
				$id=mainModel::limpiar_cadena($id);

				return gastoModelo::datos_gasto_modelo($tipo,$id);
			}//Fin controlador

			

			/*--------- Controlador paginador gasto ---------*/
			public function paginador_gasto_controlador($pagina,$registros,$privilegio,$url,$tipo,$fecha_inicio,$fecha_final){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);

			$url=mainModel::limpiar_cadena($url);
			$url=SERVERURL.$url."/";

			$tipo=mainModel::limpiar_cadena($tipo);

			$fecha_inicio=mainModel::limpiar_cadena($fecha_inicio);
			$fecha_final=mainModel::limpiar_cadena($fecha_final);
			$tabla="";

			$pagina=(isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio=($pagina>0) ? (($pagina*$registros)-$registros) : 0 ;

			if ($tipo=="Busqueda") {
				if (mainModel::verificar_fecha($fecha_inicio) || mainModel::verificar_fecha($fecha_final)) {
					return '
						<div class="alert alert-danger text-center" role="alert">
							<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
							<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
							<p class="mb-0">Lo sentimos, no podemos realizar la busqueda ya que ingreso una fecha invalida.</p>
						</div>
					';
					exit();
				}
			}

			$campos="gasto.gasto_id, gasto.gasto_codigo, gasto.gasto_fecha, gasto.gasto_hora, gasto.gasto_total, gasto.gasto_cantidad, gasto.gasto_estado,gasto.gasto_observacion, gasto.usuario_id, gasto.proveedor_id, proveedor.proveedor_nombre, proveedor.proveedor_dni";

			if ($tipo=="Busqueda" && $fecha_inicio!="" && $fecha_final!="") {
				if($fecha_inicio>$fecha_final){
					return '
						<div class="alert alert-danger text-center" role="alert">
							<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
							<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
							<p class="mb-0">La fecha de inicio no puede ser mayor a la final.</p>
						</div>
					';
					exit();
				}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos  FROM gasto INNER JOIN proveedor ON gasto.proveedor_id=proveedor.proveedor_id WHERE (gasto.gasto_fecha BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY gasto.gasto_fecha DESC LIMIT $inicio,$registros";
			}
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM gasto INNER JOIN proveedor ON gasto.proveedor_id=proveedor.proveedor_id WHERE gasto.gasto_estado='$tipo' ORDER BY gasto.gasto_fecha,gasto.gasto_hora DESC LIMIT $inicio,$registros"; 	
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
							<th>PROVEEDOR</th>
							<th>DNI</th>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>CANTIDAD</th>
                            <th>COSTO</th>';
							if($privilegio==1 || $privilegio==2){
								$tabla.='<th>ACTUALIZAR</th>';
							}
							if($privilegio==1 ){
								$tabla.='<th>ELIMINAR</th>';
							}
							$tabla.='</tr>
					</thead>
					<tbody id="myTable">';
				if ($total>=1 && $pagina<=$Npaginas) {
					
					$contador=$inicio+1;
					$reg_inicio=$inicio+1;

					$_SESSION['gasto_total']=0;
                    $_SESSION['gasto_item']=0;

					foreach($datos as $rows){
									$subtotal=$rows['gasto_total'];
                                    

                                    

                                    $subtotal=number_format($subtotal,2,'.','');
						$tabla.='
						<tr class="text-center" >
							<td>'.$contador.'</td>
							<td>'.$rows['proveedor_nombre'].' </td>
							<td>'.$rows['proveedor_dni'].'</td>
							<td>'.date("d-m-Y",strtotime($rows['gasto_fecha'])).'</td>
							<td>'.date("H:i",strtotime($rows['gasto_hora'])).'</td>
							<td>'.$rows['gasto_cantidad'].'</td>
							<td>S/. '.$rows['gasto_total'].'</td>';

							// if ($rows['gasto_pagado']<$rows['gasto_total']) {
							// 	$tabla.='<td>Pendiente: <span class="badge badge-danger">'.MONEDA.number_format(($rows['gasto_total']-$rows['gasto_pagado']),2,'.',',').'</span></td>'; 

							// }else{
							// 	$tabla.='<td><span class="badge badge-light">Cancelado</span></td>';
							// }
							

							
									$tabla.='
									<td>
										<a href="'.SERVERURL.'gasto-update/'.mainModel::encryption($rows['gasto_id']).'/" class="btn btn-success">
												<i class="fas fa-edit"></i>	
										</a>
									</td>';
					
								
						
							if($privilegio==1){
							$tabla.='<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/gastoAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="gasto_codigo_del" value="'.mainModel::encryption($rows['gasto_codigo']).'">
									<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
									</button>
								</form>
							</td>';
						}
						$_SESSION['gasto_total']+=$subtotal;
                        $_SESSION['gasto_item']+=$rows['gasto_cantidad'];
							$tabla.='</tr>';
						$contador++;
					}
					$reg_final=$contador-1;
				}else{
					if ($total>=1) {
						$tabla.='<tr class="text-center">
						<td colspan="10">
						<a href="'.$url.'" class=="btn btn-raised btn-primary btn-sm"> Haga click aquí para recargar la lista</a>
						</td> 
						</tr>';
					}else{
						$tabla.='<tr class="text-center">
						<td colspan="10">No hay registros en el sistema</td> 
						</tr>';
						$_SESSION['gasto_total']=0;
                    $_SESSION['gasto_item']=0;
					}
					
				}

				$tabla.='<tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="4"></td>
                            <td><strong>'.$_SESSION['gasto_item'].' productos</strong></td>
                            
                            <td><strong>'.MONEDA.number_format($_SESSION['gasto_total'],2,'.','').'</strong></td>
                            <td colspan="2"></td>
                        </tr></tbody></table></div>';


				if ($total>=1 && $pagina<=$Npaginas) {
					$tabla.='<p class="text-right">Mostrando gastos '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */
		
		/* Controlador Eliminar gasto */		
		public function eliminar_gasto_controlador(){

			/* Recibiendo ID del gasto */		
			$codigo=mainModel::decryption($_POST['gasto_codigo_del']);
			$codigo=mainModel::limpiar_cadena($codigo);

			

			/* Comprobando gasto en la base de datos */	
			$check_gasto=mainModel::ejecutar_consulta_simple("SELECT gasto_codigo FROM gasto WHERE gasto_codigo='$codigo'");
			if ($check_gasto->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"La gasto que intenta eliminar no existe en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}

			/* Comprobando privilegios */	

			session_start(['name'=>'SPM']);
			if ($_SESSION['privilegio_spm']!=1) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"Usted no cuenta con los permisos necesarios para realizar esta acción",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
				}

			$eliminar_gasto1=gastoModelo::eliminar_gasto_modelo($codigo,"Detalle");
			$eliminar_gasto3=gastoModelo::eliminar_gasto_modelo($codigo,"Gasto");

			if ($eliminar_gasto1->rowCount()==1 && $eliminar_gasto3->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Gasto Eliminado",
					"Texto"=>"El gasto fue eliminado correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar el gasto, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}
		/* Fin controlador */


/*--------- Controlador actualizar datos de la gasto ---------*/
		public function actualizar_gasto_controlador(){
			// Recibiendo el ID

			
			$id=mainModel::decryption($_POST['gasto_id_up']);
			$id=mainModel::limpiar_cadena($id);
 			
			/* Comprobando gasto en la base de datos */	
			$check_gasto=mainModel::ejecutar_consulta_simple("SELECT * FROM gasto WHERE gasto_id='$id'");
			if ($check_gasto->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro la gasto en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_gasto->fetch();
			}

			$estado=mainModel::limpiar_cadena($_POST['gasto_estado_up']);
			$observacion=mainModel::limpiar_cadena($_POST['gasto_observacion_up']);
			

			/*== comprobar campos vacios ==*/
			if( $estado=="" || $observacion=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debes llenar el estado y la observación para poder actualizar la gasto",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
			
			if($estado!="Aprobado" && $estado!="Cancelado"){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El estado no coincide con el formato solicitado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			if(mainModel::verificar_datos("[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{8,400}",$observacion)){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La observación no coincide con el formato solicitado",
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
			$datos_gasto_up=[
				
				"Estado"=>$estado,
				"Observacion"=>$observacion,
				"ID"=>$id
			];

			if (gastoModelo::actualizar_gasto_modelo($datos_gasto_up)) {
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