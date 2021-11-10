
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR VENTA
    </h3>

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR VENTA</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-venta/"><i class="far fa-calendar-alt"></i> &nbsp; VENTAS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-buscar/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSQUEDA</a>
        </li>

    </ul>
</div>

<div class="container-fluid">
	<div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR CLIENTE Y PRODUCTO</p>
            <p class="text-center">

                <?php if (empty($_SESSION['datos_cliente'])) {
                     ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCliente"><i class="fas fa-user-plus"></i> &nbsp; Agregar cliente</button>

                <?php } ?>
                

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalProducto"><i class="fas fa-box-open"></i> &nbsp; Agregar producto</button>
           
            </p>
            <div>
                <span class="roboto-medium">CLIENTE:</span> 
                <?php if (empty($_SESSION['datos_cliente'])) {
                     ?>
                <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un cliente</span>
            <?php }else{ ?>
      			<form class="FormularioAjax" action="<?php echo SERVERURL;?>ajax/ventaAjax.php" method="POST" data-form="loans" style="display: inline-block !important;">
                    <input type="hidden" name="id_eliminar_cliente" value="<?php echo $_SESSION['datos_cliente']['ID']?>">
                	<?php echo $_SESSION['datos_cliente']['Nombre']." ".$_SESSION['datos_cliente']['Apellido']." (".$_SESSION['datos_cliente']['DNI'].")"; ?>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                </form>
                <?php } ?>

            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>N°</th>
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>TIPO</th>
                            
                            <th>COSTO</th>
                            <th>CANTIDAD</th>
                            <th>SUBTOTAL</th>
                            <th>DETALLE</th>                            
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                            if (isset($_SESSION['datos_producto']) && count($_SESSION['datos_producto'])>=1) {
                                
                                $_SESSION['venta_total']=0;
                                $_SESSION['venta_producto']=0;
                               

                                foreach($_SESSION['datos_producto'] as $productos){
                                    $subtotal=$productos['Cantidad']*($productos['Costo']);
                                    $costoNum=$productos['Costo'];

                                    $costoNum=number_format($costoNum,2,'.','');

                                    $subtotal=number_format($subtotal,2,'.','');

                        ?>
                        <tr class="text-center" >
                            <td> <?php echo $i++ ?> </td>
                            <td> <?php echo $productos['Codigo'] ?> </td>
                            <td><?php echo $productos['Nombre'] ?></td>
                            <td><?php echo $productos['Tipo'] ?></td>
                           
                             <td><?php echo MONEDA.$productos['Costo'] ?></td>
                            <td><?php echo $productos['Cantidad'] ?></td>
                            <td><?php echo MONEDA.$subtotal; ?></td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="PESO= <?php echo $productos['Peso']; ?>" data-content="STOCK= <?php echo $productos['Stock']; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            
                            <td>
                                <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ventaAjax.php" method="POST" data-form="venta" autocomplete="off">
                                    <input type="hidden" name="id_eliminar_producto" value="<?php echo $productos['ID'];  ?>">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php   
                              
                                $_SESSION['venta_total']+=$subtotal;
                                $_SESSION['venta_producto']+=$productos['Cantidad'];    
                                }
                        ?>
                        <tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="4"></td>
                            <td><strong><?php echo $_SESSION['venta_producto']; ?> productos</strong></td>
                            
                            <td><strong><?php echo MONEDA.number_format($_SESSION['venta_total'],2,'.','') ?></strong></td>
                            <td colspan="2"></td>
                        </tr>

                        <?php
                            }else{
                                $_SESSION['venta_total']=0;
                                $_SESSION['venta_producto']=0;
                         ?>

                         <tr class="text-center" >
                            <td colspan="9">No has seleccionado items</td>
                        </tr>

                         <?php
                            }
                         ?>
                    </tbody>
                </table>
            </div>
        </div>
		<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ventaAjax.php" method="POST" data-form="save" autocomplete="off">
			<fieldset>
				<legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
				<div class="container-fluid">
					<div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="venta_observacion" class="bmd-label-floating">Observación</label>
                                <input type="hidden" name="venta_fecha_pago_reg" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                <input type="hidden" class="form-control" name="venta_hora_pago_reg" value="<?php echo date("H:i"); ?>" >

                                <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}" class="form-control" name="venta_observacion_reg" id="venta_observacion" maxlength="400">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="venta_estado" class="bmd-label-floating">Estado</label>
                                            <select class="form-control" name="venta_estado_reg" id="venta_estado">
                                                <option value="Aprobado" selected="" >Aprobado</option>
                                            </select>
                                        </div>
                                    </div>
					</div>
				</div>
			</fieldset>
			<br><br><br>
			<p class="text-center" style="margin-top: 40px;">
				<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
				&nbsp; &nbsp;
				<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
			</p>
		</form>
	</div>
</div>


<!-- MODAL CLIENTE -->
<div class="modal fade" id="ModalCliente" tabindex="-1" role="dialog" aria-labelledby="ModalCliente" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCliente">Agregar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_cliente" class="bmd-label-floating">DNI, Nombre, Apellido, Telefono</label>
                        <input onkeyup="buscar_cliente()" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_cliente" id="input_cliente" maxlength="30" autocomplete="off">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_clientes">
                    
                                
                            
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_cliente()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL Producto -->
<div class="modal fade" id="ModalProducto" tabindex="-1" role="dialog" aria-labelledby="ModalProducto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalProducto">Agregar producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_producto" class="bmd-label-floating">Código</label>
                        <input onkeyup="buscar_producto()" type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_producto" id="input_producto" maxlength="30" autocomplete="off">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_productos">
                    
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL AGREGAR Producto -->
<div class="modal fade" id="ModalAgregarProducto" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarProducto" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ventaAjax.php" method="POST" data-form="default" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarProducto">Ingrese la cantidad del producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_producto" id="id_agregar_producto">
                <div class="container-fluid">
                    <div class="row">


                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de producto</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" >
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick="modal_buscar_producto()">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once "./vistas/inc/venta.php";
 ?>