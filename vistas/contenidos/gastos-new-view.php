
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR GASTO
    </h3>

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>gastos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR GASTO</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gastos-gastos/"><i class="far fa-calendar-alt"></i> &nbsp; GASTOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gastos-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>gastos-buscar/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSQUEDA</a>
        </li>

    </ul>
</div>

<div class="container-fluid">
	<div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR PROVEEDOR E ITEM</p>
            <p class="text-center">

                <?php if (empty($_SESSION['datos_proveedor'])) {
                     ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalProveedor"><i class="fas fa-user-plus"></i> &nbsp; Agregar proveedor</button>

                <?php } ?>
                

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem"><i class="fas fa-box-open"></i> &nbsp; Agregar item</button>
           
            </p>
            <div>
                <span class="roboto-medium">proveedor:</span> 
                <?php if (empty($_SESSION['datos_proveedor'])) {
                     ?>
                <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un proveedor</span>
            <?php }else{ ?>
      			<form class="FormularioAjax" action="<?php echo SERVERURL;?>ajax/gastoAjax.php" method="POST" data-form="loans" style="display: inline-block !important;">
                    <input type="hidden" name="id_eliminar_proveedor" value="<?php echo $_SESSION['datos_proveedor']['ID']?>">
                	<?php echo $_SESSION['datos_proveedor']['Nombre']."  (".$_SESSION['datos_proveedor']['DNI'].")"; ?>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                </form>
                <?php } ?>

            </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>N°</th>
                            <th>NOMBRE</th>
                            <th>CÓDIGO</th>
                            
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
                            if (isset($_SESSION['datos_item']) && count($_SESSION['datos_item'])>=1) {
                                
                                $_SESSION['gasto_total']=0;
                                $_SESSION['gasto_item']=0;
                                

                                foreach($_SESSION['datos_item'] as $items){
                                    $subtotal=$items['Cantidad']*($items['Costo']);
                                    $costoNum=$items['Costo'];

                                    $costoNum=number_format($costoNum,2,'.','');

                                    $subtotal=number_format($subtotal,2,'.','');

                        ?>
                        <tr class="text-center" >
                            <td> <?php echo $i++ ?> </td>
                            <td><?php echo $items['Nombre'] ?></td>
                            <td><?php echo $items['Codigo'] ?></td>
                           
                             <td><?php echo MONEDA.$items['Costo'] ?></td>
                            <td><?php echo $items['Cantidad'] ?></td>
                            <td><?php echo MONEDA.$subtotal; ?></td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="DETALLE= <?php echo $items['Detalle']; ?>" data-content="STOCK= <?php echo $items['Stock']; ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                            
                            <td>
                                <form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="gasto" autocomplete="off">
                                    <input type="hidden" name="id_eliminar_item" value="<?php echo $items['ID'];  ?>">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php   
                              
                                $_SESSION['gasto_total']+=$subtotal;
                                $_SESSION['gasto_item']+=$items['Cantidad'];    
                                }
                        ?>
                        <tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="3"></td>
                            <td><strong><?php echo $_SESSION['gasto_item']; ?> items</strong></td>
                            
                            <td><strong><?php echo MONEDA.number_format($_SESSION['gasto_total'],2,'.','') ?></strong></td>
                            <td colspan="2"></td>
                        </tr>

                        <?php
                            }else{
                                $_SESSION['gasto_total']=0;
                                $_SESSION['gasto_item']=0;
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
		<form class="FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="save" autocomplete="off">
			<fieldset>
				<legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
				<div class="container-fluid">
					<div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="gasto_observacion" class="bmd-label-floating">Observación</label>
                                <input type="hidden" name="gasto_fecha_pago_reg" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                <input type="hidden" class="form-control" name="gasto_hora_pago_reg" value="<?php echo date("H:i"); ?>" >

                                <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}" class="form-control" name="gasto_observacion_reg" id="gasto_observacion" maxlength="400">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="gasto_estado" class="bmd-label-floating">Estado</label>
                                            <select class="form-control" name="gasto_estado_reg" id="gasto_estado">
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


<!-- MODAL proveedor -->
<div class="modal fade" id="ModalProveedor" tabindex="-1" role="dialog" aria-labelledby="ModalProveedor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalProveedor">Agregar proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_proveedor" class="bmd-label-floating">DNI, RUC, Nombre, Telefono</label>
                        <input onkeyup="buscar_proveedor()" type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_proveedor" id="input_proveedor" maxlength="30" autocomplete="off">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_proveedores">
                    
                                
                            
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="buscar_proveedor()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL Item -->
<div class="modal fade" id="ModalItem" tabindex="-1" role="dialog" aria-labelledby="ModalItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalItem">Agregar Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_item" class="bmd-label-floating">Código</label>
                        <input onkeyup="buscar_item()" type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_item" id="input_item" maxlength="30" autocomplete="off">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_items">
                    
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


<!-- MODAL AGREGAR item -->
<div class="modal fade" id="ModalAgregarItem" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarItem" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="default" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarItem">Ingrese la cantidad y el costo del item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_item" id="id_agregar_item">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Costo de item</label>
                                <input type="num" pattern="[0-9 .]{1,7}" class="form-control" name="detalle_costo_tiempo" id="detalle_costo_tiempo" maxlength="7" required="" >
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de item</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required=""  value="1">
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick="modal_buscar_item()">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once "./vistas/inc/gasto.php";
 ?>