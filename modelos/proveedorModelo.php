<?php
	
	require_once "mainModel.php";

	class proveedorModelo extends mainModel{

		/*--------- Modelo agregar proveedor ---------*/
		protected static function agregar_proveedor_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO proveedor(proveedor_dni,proveedor_nombre,proveedor_telefono,proveedor_direccion) VALUES(:DNI,:Nombre,:Telefono,:Direccion)");

			$sql->bindParam(":DNI",$datos['DNI']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Direccion",$datos['Direccion']);
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo eliminar proveedor ---------*/
		protected static function eliminar_proveedor_modelo($id){
			$sql=mainModel::conectar()->prepare("DELETE FROM proveedor WHERE proveedor_id=:ID");
			$sql->bindParam(":ID",$id);
			$sql->execute();

			return $sql;
		}

		/*--------- Modelo datos proveedor ---------*/
		protected static function datos_proveedor_modelo($tipo,$id){
			if ($tipo=="Unico") {
					$sql=mainModel::conectar()->prepare("SELECT * FROM proveedor WHERE proveedor_id=:ID");
					$sql->bindParam(":ID",$id);
					
			}elseif($tipo=="Conteo"){
					$sql=mainModel::conectar()->prepare("SELECT proveedor_id FROM proveedor");
			}
			$sql->execute();

			return $sql;	
		}

		/*--------- Modelo actualizar datos del proveedor ---------*/
		protected static function actualizar_proveedor_modelo($datos){
			$sql=mainModel::conectar()->prepare("UPDATE proveedor SET proveedor_dni=:DNI, proveedor_nombre=:Nombre, proveedor_telefono=:Telefono,proveedor_direccion=:Direccion WHERE proveedor_id=:ID");
			$sql->bindParam(":DNI",$datos['DNI']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Direccion",$datos['Direccion']);
			$sql->bindParam(":ID",$datos['ID']);

			$sql->execute();

			return $sql;

		}

	}