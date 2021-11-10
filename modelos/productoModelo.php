<?php
	
	require_once "mainModel.php";

	class productoModelo extends mainModel{

		/*--------- Modelo agregar producto ---------*/
		protected static function agregar_producto_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO producto(producto_codigo,producto_tipo,producto_costo,producto_estado,producto_porcentaje,producto_nombre,producto_peso,producto_unidad,producto_stock) VALUES(:Codigo,:Tipo,:Costo,:Estado,:Porcentaje,:Nombre,:Peso,:Unidad,:Stock)");

			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->bindParam(":Tipo",$datos['Tipo']);
			$sql->bindParam(":Costo",$datos['Costo']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Porcentaje",$datos['Porcentaje']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Peso",$datos['Peso']);
			$sql->bindParam(":Unidad",$datos['Unidad']);
			$sql->bindParam(":Stock",$datos['Stock']);
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo eliminar producto ---------*/
		protected static function eliminar_producto_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM producto WHERE producto_id=:ID");
			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		}
		/*--------- Modelo datos producto ---------*/
		protected static function datos_producto_modelo($tipo,$id){
			if ($tipo=="Unico") {
					$sql=mainModel::conectar()->prepare("SELECT * FROM producto WHERE producto_id=:ID");
					$sql->bindParam(":ID",$id);
					
			}elseif($tipo=="Conteo"){
					$sql=mainModel::conectar()->prepare("SELECT producto_id FROM producto");
			}
			$sql->execute();

			return $sql;	
		}

		/*--------- Modelo actualizar datos de la producto ---------*/
		protected static function actualizar_producto_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE producto SET producto_codigo=:Codigo, producto_nombre=:Nombre,producto_tipo=:Tipo, producto_costo=:Costo, producto_peso=:Peso, producto_unidad=:Unidad, producto_estado=:Estado, producto_porcentaje=:Porcentaje, producto_stock=:Stock WHERE producto_id=:ID");
			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Tipo",$datos['Tipo']);
			$sql->bindParam(":Costo",$datos['Costo']);
			$sql->bindParam(":Peso",$datos['Peso']);
			$sql->bindParam(":Unidad",$datos['Unidad']);
			$sql->bindParam(":Estado",$datos['Estado']);
			$sql->bindParam(":Porcentaje",$datos['Porcentaje']);
			$sql->bindParam(":Stock",$datos['Stock']);
			$sql->bindParam(":ID",$datos['ID']);

			$sql->execute();

			return $sql;

		}

		/*--------- Modelo actualizar stock del producto ---------*/
		protected static function actualizar_stock_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE producto SET producto_stock=:Stock WHERE producto_id=:ID");
			$sql->bindParam(":Stock",$datos['Stock']);
			$sql->bindParam(":ID",$datos['ID']);

			$sql->execute();

			return $sql;

		}


	}