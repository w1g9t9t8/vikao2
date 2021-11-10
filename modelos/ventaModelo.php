<?php
	
	require_once "mainModel.php";

	class ventaModelo extends mainModel{

		/*--------- Modelo agregar venta ---------*/
		protected static function agregar_venta_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO venta(venta_codigo,venta_fecha,venta_hora,venta_cantidad,venta_total,venta_estado,venta_observacion,usuario_id,cliente_id) VALUES(:Codigo,:Fecha,:Hora,:Cantidad,:Total,:Estado,:Observacion,:Usuario,:Cliente)");

			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->bindParam(":Fecha",$datos['Fecha']);
			$sql->bindParam(":Hora",$datos['Hora']);
			$sql->bindParam(":Cantidad",$datos['Cantidad']);
			$sql->bindParam(":Total",$datos['Total']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Observacion",$datos['Observacion']);
			$sql->bindParam(":Usuario",$datos['Usuario']);
			$sql->bindParam(":Cliente",$datos['Cliente']);
			$sql->execute();

			return $sql;
		}

		

		/*--------- Modelo agregar detalle ---------*/
		protected static function agregar_detalle_modelo($datos){
			
			$sql=mainModel::conectar()->prepare("INSERT INTO detalle(detalle_cantidad,detalle_costo,venta_codigo,producto_id,detalle_descripcion,detalle_preciounitario) VALUES(:Cantidad,:Costo,:Venta,:Producto,:Descripcion,:Preciounitario)");

			$sql->bindParam(":Cantidad",$datos['Cantidad']);
			$sql->bindParam(":Costo",$datos['Costo']);		
			$sql->bindParam(":Venta",$datos['Venta']);
			$sql->bindParam(":Producto",$datos['Producto']);
			$sql->bindParam(":Descripcion",$datos['Descripcion']);
			$sql->bindParam(":Preciounitario",$datos['Preciounitario']);
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo eliminar venta ---------*/
		protected static function eliminar_venta_modelo($codigo,$tipo){
			
			if ($tipo=="Venta") {
				$sql=mainModel::conectar()->prepare("DELETE FROM venta WHERE venta_codigo=:Codigo");
			}elseif ($tipo=="Detalle") {
				$campos="producto_id, detalle_cantidad";
				$consulta="SELECT SQL_CALC_FOUND_ROWS $campos FROM detalle WHERE venta_codigo='$codigo'";
				$conexion = mainModel::conectar();
				$datos = $conexion->query($consulta);
				$datos = $datos->fetchAll();


				foreach($datos as $rows){

					$cantidad=$rows['detalle_cantidad'];
					$productos=$rows['producto_id'];
					
				}

				$campos2="producto_stock";
				$consulta2="SELECT SQL_CALC_FOUND_ROWS $campos2 FROM producto WHERE producto_id='$productos'";
				$conexion2 = mainModel::conectar();
				$datos2 = $conexion2->query($consulta2);
				$datos2 = $datos2->fetchAll();

				foreach($datos2 as $rows2){

					$cantidadactual=$rows2['producto_stock'];
					
				}
				$cantidad=$cantidadactual+$cantidad;

					$actualizar=mainModel::conectar()->prepare("UPDATE producto SET producto_stock=:Stock WHERE producto_id=:ID");
					$actualizar->bindParam(":Stock",$cantidad);
					$actualizar->bindParam(":ID",$productos);

					$actualizar->execute();

				$sql=mainModel::conectar()->prepare("DELETE FROM detalle WHERE venta_codigo=:Codigo");
			}

			$sql->bindParam(":Codigo",$codigo);	
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo seleccionar datos venta---------*/
		protected static function datos_venta_modelo($tipo,$id){
			if ($tipo=="Unico") {
				/*$campos="venta.venta_id, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_total, venta.venta_cantidad, venta.venta_estado, venta.venta_observacion venta.usuario_id, venta.cliente_id, cliente.cliente_nombre, cliente.cliente_apellido,cliente.cliente_dni";*/
				$sql=mainModel::conectar()->prepare("SELECT * FROM venta WHERE venta_id=:ID");
				$sql->bindParam(":ID",$id);	
			}else if($tipo=="Conteo_Aprobado"){
				$sql=mainModel::conectar()->prepare("SELECT venta_id FROM venta WHERE venta_estado='Aprobado'");
			}else if($tipo=="Conteo_Cancelado"){
				$sql=mainModel::conectar()->prepare("SELECT venta_id FROM venta WHERE venta_estado='Cancelado'");
			}else if($tipo=="Conteo"){
				$sql=mainModel::conectar()->prepare("SELECT venta_id FROM venta");
			}else if($tipo=="Detalle"){
				$sql=mainModel::conectar()->prepare("SELECT * FROM detalle WHERE venta_codigo=:Codigo");
				$sql->bindParam(":Codigo",$id);	
			}

			
			$sql->execute();

			return $sql;
		}


		/*--------- Modelo actualizar datos de la venta ---------*/
		protected static function actualizar_venta_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE venta SET venta_estado=:Estado, venta_observacion=:Observacion WHERE venta_id=:ID");
			
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Observacion",$datos['Observacion']);
			$sql->bindParam(":ID",$datos['ID']);

			$sql->execute();

			return $sql;

		}

		
	}