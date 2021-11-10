<?php
	
	class vistasModelo{

		/*--------- Modelo obtener vistas ---------*/
		protected static function obtener_vistas_modelo($vistas){
			$listaBlanca=["clienteList","clienteRegistrar","client-search","client-update","company","Inicio","habitacionList","habitacionRegistrar","item-search","item-list","item-new","item-update","venta-cancelado","reservacion-new","reservation-pending","venta-buscar","venta-update","usuarioList","reservation-reservation","usuarioRegistrar","user-search","user-update","productoList","productoRegistrar","producto-update","proveedorRegistrar","proveedorList","venta-venta","venta-new","gastos-new","gastos-gastos","gastos-cancelado","gasto-update","gastos-buscar","proveedor-search","producto-search","proveedor-update","clientTop"];
			if(in_array($vistas, $listaBlanca)){
				if(is_file("./vistas/contenidos/".$vistas."-view.php")){
					$contenido="./vistas/contenidos/".$vistas."-view.php";
				}else{
					$contenido="404";
				}
			}elseif($vistas=="login" || $vistas=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}
			return $contenido;
		}
	}