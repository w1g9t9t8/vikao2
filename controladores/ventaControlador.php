<?php

	if($peticionAjax){
		require_once "../modelos/ventaModelo.php";
	}else{
		require_once "./modelos/ventaModelo.php";
	}

	class ventaControlador extends ventaModelo{

		/*--------- Controlador buscar cliente ---------*/
		public function buscar_cliente_venta_controlador(){
			/*--------- Recuperar Texto ---------*/
			$cliente=mainModel::limpiar_cadena($_POST['buscar_cliente']);

			/*--------- Comprobar Texto ---------*/
			if ($cliente=="") {
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes ingresar algun dato
                    </p>
                </div>';
                exit();
			}

			/*--------- Seleccionando clientes en bd---------*/

			$datos_cliente=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_dni LIKE '%$cliente%' OR cliente_nombre LIKE '%$cliente%' OR cliente_apellido LIKE '%$cliente%' OR cliente_telefono LIKE '%$cliente%' ORDER BY cliente_nombre ASC");

			if ($datos_cliente->rowCount()>=1) {
				$datos_cliente=$datos_cliente->fetchAll();

				$tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
				foreach($datos_cliente as $rows){
					$tabla.='<tr class="text-center">
                                    <td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].' - '.$rows['cliente_dni'].'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="agregar_cliente('.$rows['cliente_id'].')"><i class="fas fa-user-plus"></i></button>
                                    </td>
                                </tr>';
				}
				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$cliente.'”</strong>
                    </p>
                </div>';
                exit();
			}
		}//Fin controlador

		/*--------- Controlador agregar cliente ---------*/
		public function agregar_cliente_venta_controlador(){
			/*--------- Recuperar Texto ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_agregar_cliente']);

			/*--------- Comprobando clientes en bd---------*/

			$check_cliente=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE cliente_id='$id'");

			if ($check_cliente->rowCount()<=0) {

				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se encontró el cliente seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();


				
			}else{
				$campos=$check_cliente->fetch();
				
			}

			/*--------- Iniciado la sesión ---------*/

			session_start(['name'=>'SPM']);

			if (empty($_SESSION['datos_cliente'])) {
				$_SESSION['datos_cliente']=[
					"ID"=>$campos['cliente_id'],
					"DNI"=>$campos['cliente_dni'],
					"Nombre"=>$campos['cliente_nombre'],
					"Apellido"=>$campos['cliente_apellido']
				];	
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cliente agregado correctamente",
					"Texto"=>"El cliente se agregó correctamente",
					"Tipo"=>"success"
					];
					echo json_encode($alerta);		
			}	else{
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se agrego el cliente a la venta",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
			}		

		}//Fin controlador

		/*--------- Controlador eliminar cliente ---------*/
		public function eliminar_cliente_venta_controlador(){
			
			/*--------- Iniciado la sesión ---------*/
			session_start(['name'=>'SPM']);	

			unset($_SESSION['datos_cliente']);

			if (empty($_SESSION['datos_cliente'])) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Cliente Eliminado",
					"Texto"=>"Se elimino al cliente correctamente",
					"Tipo"=>"success"
					];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo eliminar al cliente",
					"Tipo"=>"error"
					];
			}
			echo json_encode($alerta);

		}//Fin controlador


		/*--------- Controlador buscar producto ---------*/
		public function buscar_producto_venta_controlador(){
			/*--------- Recuperar Texto ---------*/
			$producto=mainModel::limpiar_cadena($_POST['buscar_producto']);

			/*--------- Comprobar Texto ---------*/
			if ($producto=="") {
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes ingresar algun dato
                    </p>
                </div>';
                exit();
			}

			/*--------- Seleccionando productos en bd---------*/

			$datos_producto=mainModel::ejecutar_consulta_simple("SELECT * FROM producto WHERE (producto_codigo LIKE '%$producto%' OR producto_nombre LIKE '%$producto%' OR producto_stock LIKE '%$producto%') AND (producto_estado='Activo') ORDER BY producto_nombre ASC");

			if ($datos_producto->rowCount()>=1) {
				$datos_producto=$datos_producto->fetchAll();

				$tabla='<div class="table-responsive"><table class="table table-hover table-bordered table-sm"><tbody>';
				foreach($datos_producto as $rows){
					$tabla.='<tr class="text-center">
                                    <td>'.$rows['producto_codigo'].' - '.$rows['producto_nombre'].' || Disponible: '.$rows['producto_stock'].' unidades</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="modal_agregar_producto('.$rows['producto_id'].')"><i class="fas fa-box-open"></i></button>
                                    </td>
                                </tr>';
				}
				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún producto en el sistema que coincida con <strong>“'.$producto.'”</strong> Comprueba si esta Activo por favor
                    </p>
                </div>';
                exit();
			}
		}//Fin controlador


		/*--------- Controlador agregar producto ---------*/
		public function agregar_producto_venta_controlador(){

			/*--------- Recuperar id del producto ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_agregar_producto']);

			/*--------- Comprobando producto en bd---------*/
			$check_producto=mainModel::ejecutar_consulta_simple("SELECT * FROM producto WHERE producto_id='$id' AND producto_estado='Activo' ");
			if ($check_producto->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se encontro el producto, intente nuevamente.",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
			}else{
				$campos=$check_producto->fetch();
			}

			/*--------- Recuperando detalles de venta---------*/
			/*$formato=mainModel::limpiar_cadena($_POST['detalle_formato']);
			$tiempo=mainModel::limpiar_cadena($_POST['detalle_tiempo']);
			$costo=mainModel::limpiar_cadena($_POST['detalle_costo_tiempo']);*/
			$cantidad=mainModel::limpiar_cadena($_POST['detalle_cantidad']);
		


			/*== comprobar campos vacios ==*/
			if($cantidad=="" || $cantidad=="0"){
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

			/*== comprobar stock ==*/
			if($cantidad>$campos['producto_stock']){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No puedes ingresar una cantidad que sobrepase el stock",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			session_start(['name'=>'SPM']);
			if (empty($_SESSION['datos_producto'][$id])) {
				

				$_SESSION['datos_producto'][$id]=[
					"ID"=>$campos['producto_id'],
					"Codigo"=>$campos['producto_codigo'],
					"Nombre"=>$campos['producto_nombre'],					
					"Costo"=>$campos['producto_costo'],
					"Tipo"=>$campos['producto_tipo'],
					"Peso"=>$campos['producto_peso'].$campos['producto_unidad'],
					"Unidad"=>$campos['producto_unidad'],
					"Stock"=>$campos['producto_stock'],					

					"Cantidad"=>$cantidad,
					
				];
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Producto Agregado",
					"Texto"=>"El producto se agregó correctamente.",
					"Tipo"=>"success"
				];
				echo json_encode($alerta);
				exit();
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El producto que intenta agregar ya se encuentra seleccionado",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

		}//Fin controlador


		/*--------- Controlador eliminar producto ---------*/
		public function eliminar_producto_venta_controlador(){

			/*--------- Recuperar Texto ---------*/
			$id=mainModel::limpiar_cadena($_POST['id_eliminar_producto']);
			
			/*--------- Iniciado la sesión ---------*/
			session_start(['name'=>'SPM']);	

			unset($_SESSION['datos_producto'][$id]);

			if (empty($_SESSION['datos_producto'][$id])) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"Producto Eliminado",
					"Texto"=>"Se elimino el producto correctamente",
					"Tipo"=>"success"
					];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se pudo eliminar al producto",
					"Tipo"=>"error"
					];
			}
			echo json_encode($alerta);

		}//Fin controlador


			/*--------- agregar venta ---------*/
		public function agregar_venta_controlador(){

				session_start(['name'=>'SPM']);
				

				/*--------- comprobando Productos ---------*/
				if ($_SESSION['venta_producto']==0) {
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se seleccionó ningun producto",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

				/*--------- comprobando Cliente ---------*/
				if (empty($_SESSION['datos_cliente'])) {
					$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se seleccionó ningún cliente",
					"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}


				/*--------- Recibiendo Datos de Formulario ---------*/
				$fecha_pago=mainModel::limpiar_cadena($_POST['venta_fecha_pago_reg']);
				$hora_pago=mainModel::limpiar_cadena($_POST['venta_hora_pago_reg']);

				$estado=mainModel::limpiar_cadena($_POST['venta_estado_reg']);
				$observacion=mainModel::limpiar_cadena($_POST['venta_observacion_reg']);


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
				$total_venta=number_format($_SESSION['venta_total'],2,'.','');

				
				$fecha_pago=date("Y-m-d",strtotime($fecha_pago));

				
				$hora_pago=date("H:i",strtotime($hora_pago));

				

				/*--------- Generando codigo de venta, si el venta no tiene datos= 1, si tenemos un registro= 2---------*/
				$correlativo=mainModel::ejecutar_consulta_simple("SELECT venta_id FROM venta");

				$correlativo=($correlativo->rowCount())+1;

				$codigo=mainModel::generar_codigo_aleatorio("VK",7,$correlativo);



				/*--------- Agregar venta--------*/
				$datos_venta_reg=[
					"Codigo"=>$codigo,
					"Fecha"=>$fecha_pago,
					"Hora"=>$hora_pago,
					"Cantidad"=>$_SESSION['venta_producto'],
					"Total"=>$total_venta,
					"Estado"=>$estado,
					"Observacion"=>$observacion,
					"Usuario"=>$_SESSION['id_spm'],
					"Cliente"=>$_SESSION['datos_cliente']['ID']
				];

					

				$agregar_venta=ventaModelo::agregar_venta_modelo($datos_venta_reg);

				if ($agregar_venta->rowCount()!=1) {
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado(Error:001)",
						"Texto"=>"No se pudo registrar la venta",
						"Tipo"=>"error"
						];
						echo json_encode($alerta);
						exit();
				}


			/*--------- Agregar Detalle venta --------*/
				$errores_detalle=0;
				foreach($_SESSION['datos_producto'] as $productos){
					$subtotalunitario=$productos['Costo']*$productos['Cantidad'];
					$costo=number_format($subtotalunitario,2,'.','');
					$cantidad=$productos['Cantidad'];
					$descripcion=$productos['ID']." - ".$productos['Nombre'];
					$preciounitario=$productos['Costo'];
					$stocknuevo=$productos['Stock']-$productos['Cantidad'];

					$datos_detalle_reg=[
						"Cantidad"=>$productos['Cantidad'],
						"Costo"=>$costo,
						"Descripcion"=>$descripcion,
						"Venta"=>$codigo,
						"Producto"=>$productos['ID'],
						"Preciounitario"=>$preciounitario
					];



					$agregar_detalle=ventaModelo::agregar_detalle_modelo($datos_detalle_reg);


					$sql=mainModel::conectar()->prepare("UPDATE producto SET producto_stock=:Stock WHERE producto_id=:ID");
					$sql->bindParam(":Stock",$stocknuevo);
					$sql->bindParam(":ID",$productos['ID']);

					$sql->execute();


					if ($agregar_detalle->rowCount()!=1) {
						$errores_detalle=1;
						break;
					}
				}


				if ($errores_detalle==0) {
					unset($_SESSION['datos_cliente']);
					unset($_SESSION['datos_producto']);					
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"Venta Registrada",
						"Texto"=>"Los datos de la Venta han sido registrados en el sistema",
						"Tipo"=>"success"
						];
				}else{
					ventaModelo::eliminar_venta_modelo($codigo,"Detalle");
					ventaModelo::eliminar_venta_modelo($codigo,"Venta");
						$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado(Error:003)",
						"Texto"=>"No se pudo registrar el detalle",
						"Tipo"=>"error"
						];
						
				}
				echo json_encode($alerta);

				
				
			}//Fin controlador

			/*--------- Controlador datos venta ---------*/
		public function datos_venta_controlador($tipo,$id){
				$tipo=mainModel::limpiar_cadena($tipo);

				$id=mainModel::decryption($id);
				$id=mainModel::limpiar_cadena($id);

				return ventaModelo::datos_venta_modelo($tipo,$id);
			}//Fin controlador

			

			/*--------- Controlador paginador venta ---------*/
			public function paginador_venta_controlador($pagina,$registros,$privilegio,$url,$tipo,$fecha_inicio,$fecha_final){

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

			$campos="venta.venta_id, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_total, venta.venta_cantidad, venta.venta_estado,venta.venta_observacion, venta.usuario_id, venta.cliente_id, cliente.cliente_nombre, cliente.cliente_apellido,cliente.cliente_dni";

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
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos  FROM venta INNER JOIN cliente ON venta.cliente_id=cliente.cliente_id WHERE (venta.venta_fecha BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY venta.venta_fecha DESC LIMIT $inicio,$registros";
			}
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM venta INNER JOIN cliente ON venta.cliente_id=cliente.cliente_id WHERE venta.venta_estado='$tipo' ORDER BY venta.venta_fecha,venta.venta_hora DESC LIMIT $inicio,$registros"; 	
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
							<th>CLIENTE</th>
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

					$_SESSION['venta_total']=0;
                    $_SESSION['venta_producto']=0;

					foreach($datos as $rows){
									$subtotal=$rows['venta_total'];
                                    

                                    

                                    $subtotal=number_format($subtotal,2,'.','');
						$tabla.='
						<tr class="text-center" >
							<td>'.$contador.'</td>
							<td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'</td>
							<td>'.$rows['cliente_dni'].'</td>
							<td>'.date("d-m-Y",strtotime($rows['venta_fecha'])).'</td>
							<td>'.date("H:i",strtotime($rows['venta_hora'])).'</td>
							<td>'.$rows['venta_cantidad'].'</td>
							<td>S/. '.$rows['venta_total'].'</td>';

							// if ($rows['venta_pagado']<$rows['venta_total']) {
							// 	$tabla.='<td>Pendiente: <span class="badge badge-danger">'.MONEDA.number_format(($rows['venta_total']-$rows['venta_pagado']),2,'.',',').'</span></td>'; 

							// }else{
							// 	$tabla.='<td><span class="badge badge-light">Cancelado</span></td>';
							// }
							

							
									$tabla.='
									<td>
										<a href="'.SERVERURL.'venta-update/'.mainModel::encryption($rows['venta_id']).'/" class="btn btn-success">
												<i class="fas fa-edit"></i>	
										</a>
									</td>';
					
								
						
							if($privilegio==1){
							$tabla.='<td>
								<form class="FormularioAjax" action="'.SERVERURL.'ajax/ventaAjax.php" method="POST" data-form="delete" autocomplete="off">
									<input type="hidden" name="venta_codigo_del" value="'.mainModel::encryption($rows['venta_codigo']).'">
									<button type="submit" class="btn btn-warning">
											<i class="far fa-trash-alt"></i>
									</button>
								</form>
							</td>';
						}
						$_SESSION['venta_total']+=$subtotal;
                        $_SESSION['venta_producto']+=$rows['venta_cantidad'];
							$tabla.='</tr>
							
							';
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
						$_SESSION['venta_total']=0;
                    $_SESSION['venta_producto']=0;
					}
					
				}

				$tabla.='<tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="4"></td>
                            <td><strong>'.$_SESSION['venta_producto'].' productos</strong></td>
                            
                            <td><strong>'.MONEDA.number_format($_SESSION['venta_total'],2,'.','').'</strong></td>
                            <td colspan="2"></td>
                        </tr></tbody></table></div>';


				if ($total>=1 && $pagina<=$Npaginas) {
					$tabla.='<p class="text-right">Mostrando ventas '.$reg_inicio.' - '.$reg_final.' de un total de '.$total.'</p>';
					$tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);
				}
				return $tabla;

		} /* Fin controlador */


		/*--------- Controlador actualizar datos de la venta ---------*/
		public function actualizar_venta_controlador(){
			// Recibiendo el ID

			
			$id=mainModel::decryption($_POST['venta_id_up']);
			$id=mainModel::limpiar_cadena($id);
 			
			/* Comprobando venta en la base de datos */	
			$check_venta=mainModel::ejecutar_consulta_simple("SELECT * FROM venta WHERE venta_id='$id'");
			if ($check_venta->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se encontro la venta en el sistema",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit(); 
			}else{
				$campos=$check_venta->fetch();
			}

			$estado=mainModel::limpiar_cadena($_POST['venta_estado_up']);
			$observacion=mainModel::limpiar_cadena($_POST['venta_observacion_up']);
			

			/*== comprobar campos vacios ==*/
			if( $estado=="" || $observacion=="" ){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debes llenar el estado y la observación para poder actualizar la venta",
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
			$datos_venta_up=[
				
				"Estado"=>$estado,
				"Observacion"=>$observacion,
				"ID"=>$id
			];

			if (ventaModelo::actualizar_venta_modelo($datos_venta_up)) {
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

		/* Controlador Eliminar venta */		
		public function eliminar_venta_controlador(){

			/* Recibiendo ID del venta */		
			$codigo=mainModel::decryption($_POST['venta_codigo_del']);
			$codigo=mainModel::limpiar_cadena($codigo);

			

			/* Comprobando venta en la base de datos */	
			$check_venta=mainModel::ejecutar_consulta_simple("SELECT venta_codigo FROM venta WHERE venta_codigo='$codigo'");
			if ($check_venta->rowCount()<=0) {
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"La venta que intenta eliminar no existe en el sistema",
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

			$eliminar_venta1=ventaModelo::eliminar_venta_modelo($codigo,"Detalle");
			$eliminar_venta3=ventaModelo::eliminar_venta_modelo($codigo,"Venta");

			if ($eliminar_venta1->rowCount()==1 && $eliminar_venta3->rowCount()==1) {
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"venta Eliminada",
					"Texto"=>"La venta fue Eliminada correctamente.",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"Ocurrio un error inesperado",
					"Texto"=>"No se pudo eliminar la venta, intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			echo json_encode($alerta);
		}
		/* Fin controlador */


		/*--------- Controlador paginador venta ---------*/
			public function paginador_clientetop_controlador($pagina,$registros,$privilegio,$url,$tipo,$fecha_inicio,$fecha_final){

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

			$campos="venta.venta_id, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_total, venta.venta_cantidad, venta.venta_estado,venta.venta_observacion, venta.usuario_id, venta.cliente_id, cliente.cliente_nombre, cliente.cliente_apellido,cliente.cliente_dni";

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
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos  FROM venta INNER JOIN cliente ON venta.cliente_id=cliente.cliente_id WHERE (venta.venta_fecha BETWEEN '$fecha_inicio' AND '$fecha_final') ORDER BY venta.venta_fecha DESC LIMIT $inicio,$registros";
			}
			}else{
				$consulta="SELECT venta.cliente_id, COUNT( venta.cliente_id ) AS total,cliente.cliente_nombre,cliente.cliente_apellido,cliente.cliente_telefono,cliente.cliente_direccion FROM  venta INNER JOIN cliente ON venta.cliente_id=cliente.cliente_id GROUP BY venta.cliente_id ORDER BY total DESC LIMIT $inicio,$registros"; 	
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
							<th>CLIENTE</th>
							<th>CELULAR</th>
							<th>DIRECCIÓN</th>
							<th>N° COMPRAS</th>
							
                            ';
							
							$tabla.='</tr>
					</thead>
					<tbody id="myTable">';
				if ($total>=1 && $pagina<=$Npaginas) {
					
					$contador=$inicio+1;
					$reg_inicio=$inicio+1;

					

					foreach($datos as $rows){
								
						$tabla.='
						<tr class="text-center" >
							<td>'.$contador.'</td>

							<td>'.$rows['cliente_nombre'].' '.$rows['cliente_apellido'].'</td>
							<td>'.$rows['cliente_telefono'].'</td>
							<td>'.$rows['cliente_direccion'].'</td>
							<td>'.$rows['total'].' Compras</td>
							';

							// if ($rows['venta_pagado']<$rows['venta_total']) {
							// 	$tabla.='<td>Pendiente: <span class="badge badge-danger">'.MONEDA.number_format(($rows['venta_total']-$rows['venta_pagado']),2,'.',',').'</span></td>'; 

							// }else{
							// 	$tabla.='<td><span class="badge badge-light">Cancelado</span></td>';
							// }
							

							
	
							$tabla.='</tr>
							
							';
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
						
					}
					
				}

				return $tabla;

		} /* Fin controlador */


	}