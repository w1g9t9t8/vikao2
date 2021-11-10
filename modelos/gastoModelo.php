<?php
	
	require_once "mainModel.php";

	class gastoModelo extends mainModel{

		/*--------- Modelo agregar gasto ---------*/
		protected static function agregar_gasto_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO gasto(gasto_codigo,gasto_fecha,gasto_hora,gasto_cantidad,gasto_total,gasto_estado,gasto_observacion,usuario_id,proveedor_id) VALUES(:Codigo,:Fecha,:Hora,:Cantidad,:Total,:Estado,:Observacion,:Usuario,:Proveedor)");

			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->bindParam(":Fecha",$datos['Fecha']);
			$sql->bindParam(":Hora",$datos['Hora']);
			$sql->bindParam(":Cantidad",$datos['Cantidad']);
			$sql->bindParam(":Total",$datos['Total']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Observacion",$datos['Observacion']);
			$sql->bindParam(":Usuario",$datos['Usuario']);
			$sql->bindParam(":Proveedor",$datos['Proveedor']);
			$sql->execute();

			return $sql;
		}

		

		/*--------- Modelo agregar detalle ---------*/
		protected static function agregar_detalle_modelo($datos){
			
			$sql=mainModel::conectar()->prepare("INSERT INTO detalle_gasto(detalle_gasto_cantidad,detalle_gasto_costo_unitario,detalle_gasto_costo_total,detalle_gasto_descripcion,gasto_codigo,item_id) VALUES(:Cantidad,:Unitario,:Costo,:Descripcion,:Gasto,:Item)");

			$sql->bindParam(":Cantidad",$datos['Cantidad']);
			$sql->bindParam(":Unitario",$datos['Unitario']);	
			$sql->bindParam(":Costo",$datos['Costo']);	
			$sql->bindParam(":Descripcion",$datos['Descripcion']);	
			$sql->bindParam(":Gasto",$datos['Gasto']);
			$sql->bindParam(":Item",$datos['Item']);
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo eliminar gasto ---------*/
		protected static function eliminar_gasto_modelo($codigo,$tipo){
			
			if ($tipo=="Gasto") {
				$sql=mainModel::conectar()->prepare("DELETE FROM gasto WHERE gasto_codigo=:Codigo");
			}elseif ($tipo=="Detalle") {

				$campos="item_id, detalle_gasto_cantidad";
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM detalle_gasto WHERE gasto_codigo='$codigo'";
				$conexion = mainModel::conectar();
				$datos = $conexion->query($consulta);
				$datos = $datos->fetchAll();


				foreach($datos as $rows){

					$cantidad=$rows['detalle_gasto_cantidad'];
					$items=$rows['item_id'];
					
				}

				$campos2="item_stock,item_tipo";
				$consulta2="SELECT SQL_CALC_FOUND_ROWS $campos2 FROM item WHERE item_id='$items'";
				$conexion2 = mainModel::conectar();
				$datos2 = $conexion2->query($consulta2);
				$datos2 = $datos2->fetchAll();

				foreach($datos2 as $rows2){

					$cantidadactual=$rows2['item_stock'];
					$tipo=$rows2['item_tipo'];
					
				}

				if ($tipo=="Producto") {

					$cantidad=$cantidadactual-$cantidad;

					$actualizar=mainModel::conectar()->prepare("UPDATE item SET item_stock=:Stock WHERE item_id=:ID");
					$actualizar->bindParam(":Stock",$cantidad);
					$actualizar->bindParam(":ID",$items);

					$actualizar->execute();
				}

				$sql=mainModel::conectar()->prepare("DELETE FROM detalle_gasto WHERE gasto_codigo=:Codigo");

			}

			$sql->bindParam(":Codigo",$codigo);	
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo seleccionar datos gasto---------*/
		protected static function datos_gasto_modelo($tipo,$id){
			if ($tipo=="Unico") {
				/*$campos="gasto.gasto_id, gasto.gasto_codigo, gasto.gasto_fecha, gasto.gasto_hora, gasto.gasto_total, gasto.gasto_cantidad, gasto.gasto_estado, gasto.gasto_observacion gasto.usuario_id, gasto.cliente_id, cliente.cliente_nombre, cliente.cliente_apellido,cliente.cliente_dni";*/
				$sql=mainModel::conectar()->prepare("SELECT * FROM gasto WHERE gasto_id=:ID");
				$sql->bindParam(":ID",$id);	
			}else if($tipo=="Conteo_Aprobado"){
				$sql=mainModel::conectar()->prepare("SELECT gasto_id FROM gasto WHERE gasto_estado='Aprobado'");
			}else if($tipo=="Conteo_Cancelado"){
				$sql=mainModel::conectar()->prepare("SELECT gasto_id FROM gasto WHERE gasto_estado='Cancelado'");
			}else if($tipo=="Conteo"){
				$sql=mainModel::conectar()->prepare("SELECT gasto_id FROM gasto");
			}else if($tipo=="Detalle"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM detalle_gasto WHERE gasto_codigo=:Codigo");
				$sql->bindParam(":Codigo",$id);	
			}

			
			$sql->execute();

			return $sql;
		}

			/*--------- Modelo actualizar datos de la gasto ---------*/
		protected static function actualizar_gasto_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE gasto SET gasto_estado=:Estado, gasto_observacion=:Observacion WHERE gasto_id=:ID");
			
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Observacion",$datos['Observacion']);
			$sql->bindParam(":ID",$datos['ID']);

			$sql->execute();

			return $sql;

		}

		
	}