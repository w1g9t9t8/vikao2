<?php
    if ($_SESSION['privilegio_spm']<1 || $_SESSION['privilegio_spm']>2) {
            echo $lc->forzar_cierre_sesion_controlador();
            exit();
        }
?>

<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR GASTO
    </h3>

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a  href="<?php echo SERVERURL; ?>gastos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR GASTO</a>
        </li>
        <li>
            <a  href="<?php echo SERVERURL; ?>gastos-gastos/"><i class="far fa-calendar-alt"></i> &nbsp; GASTOS</a>
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
<?php 
        //Comprobando si hay usuarios o administradores 
        require_once "./controladores/gastoControlador.php";
        $ins_gasto= new gastoControlador();
        
        $datos_gasto=$ins_gasto->datos_gasto_controlador("Unico",$pagina[1]);
        

        if ($datos_gasto->rowCount()==1){

          $campos=$datos_gasto->fetch();
          if ($campos['gasto_estado']=="Hola") {
         ?>     
          <div class="alert alert-danger text-center" role="alert">
                <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
                <p class="mb-0">Lo sentimos, no podemos actualizar la gasto debido a que esta cancelada.</p>
            </div>
        
   
    <?php }else{

          ?>

   
	<div class="container-fluid form-neon">
<!-- 
        <?php if ($campos['reservacion_pagado']!=$campos['reservacion_total']) {
            
          ?>
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR NUEVO PAGO A ESTA RESERVACIÓN</p>
            <p class="text-center">Este préstamo presenta un pago pendiente por la cantidad de <strong><?php echo MONEDA.number_format(($campos['revervacion_total']-$campos['revervacion_pagado']),2,'.',','); ?></strong>, puede agregar un pago a este préstamo haciendo clic en el siguiente botón.</p>
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalPago"><i class="far fa-money-bill-alt"></i> &nbsp; Agregar pago</button>
            </p>
        </div>
    <?php }

          ?> -->
        <div class="container-fluid">
            <?php 
              require_once "./controladores/proveedorControlador.php";
                $ins_proveedor= new proveedorControlador();
                
                $datos_proveedor=$ins_proveedor->datos_proveedor_controlador("Unico",$lc->encryption($campos['proveedor_id']));

                $datos_proveedor=$datos_proveedor->fetch();
                require_once "./controladores/usuarioControlador.php";
                $ins_usuario= new usuarioControlador();
                
                $datos_usuario=$ins_usuario->datos_usuario_controlador("Unico",$lc->encryption($campos['usuario_id']));

                $datos_usuario=$datos_usuario->fetch();
             ?>
            <div class="row">
            
                <div class="col-12 col-md-8">
                    <div class="form-group">
                    <span class="roboto-medium">PROVEEDOR:</span> 
                    &nbsp; <?php echo $datos_proveedor['proveedor_nombre']." - ".$datos_proveedor['proveedor_dni']; ?>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="form-group">
                <span class="roboto-medium" >COMPRADOR:</span> 
                &nbsp; <?php echo $datos_usuario['usuario_nombre']." ".$datos_usuario['usuario_apellido']." - ".$datos_usuario['usuario_dni']; ?>
            </div>
            </div>
        </div>
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>N°</th>
                            <th>CODIGO</th>                                                        
                            <th>NOMBRE</th> 
                            <th>DETALLE</th>
                            <th>COSTO</th>
                            <th>CANTIDAD</th>
                            <th>SUBTOTAL</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        require_once "./controladores/itemControlador.php";
                            $ins_item= new itemControlador();

                            $datos_detalle=$ins_gasto->datos_gasto_controlador("Detalle",$lc->encryption($campos['gasto_codigo']));
                            $datos_detalle=$datos_detalle->fetchAll();

                            foreach($datos_detalle as $itemsunit){
                                 $datos_item=$ins_item->datos_item_controlador("Unico",$lc->encryption($itemsunit['item_id']));

                                $datos_item=$datos_item->fetch();
                         ?>
                        <tr class="text-center" >
                            <td> <?php echo $i++; ?> </td>
                            <td><?php echo $datos_item['item_codigo']; ?></td>
                            <td><?php echo $datos_item['item_nombre']; ?></td>
                            <td><?php echo $datos_item['item_detalle']; ?></td>
                            <td><?php echo MONEDA.number_format($itemsunit['detalle_gasto_costo_unitario'],2,'.',','); ?></td>                       
                            <td><?php echo $itemsunit['detalle_gasto_cantidad']; ?></td>
                            <td><?php echo  MONEDA.number_format($itemsunit['detalle_gasto_costo_total'],2,'.',','); ?></td>
                            
                        </tr>
                    <?php } ?>

                    <tr class="text-center bg-light">
                            <td><strong>TOTAL</strong></td>
                            <td colspan="4"></td>
                            <td><strong><?php echo $campos['gasto_cantidad']; ?> items</strong></td>
                            
                            <td><strong><?php echo MONEDA.number_format($campos['gasto_total'],2,'.','') ?></strong></td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
		<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/gastoAjax.php" method="POST" data-form="update" autocomplete="off">
            <input type="hidden" name="gasto_id_up" value="<?php echo $pagina[1] ?>">
            <fieldset>
                <legend><i class="far fa-clock"></i> &nbsp; Fecha y hora de gasto</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="gasto_fecha_inicio">Fecha de gasto</label>
                                
                                <input type="date" class="form-control" readonly="" id="gasto_fecha_inicio" value="<?php echo date("Y-m-d",strtotime($campos['gasto_fecha'])); ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="gasto_hora_inicio">Hora de gasto</label>
                                <input type="text" class="form-control" readonly="" id="gasto_hora_inicio" value="<?php echo date("H:i",strtotime($campos['gasto_hora'])); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
           
            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="gasto_estado" class="bmd-label-floating">*** Estado ***</label>
                                <select class="form-control" name="gasto_estado_up" id="gasto_estado">
                                    <option value="Aprobado" <?php if ($campos['gasto_estado']=="Aprobado") {
                                    echo 'selected=""';
                                } ?> >Aprobado <?php if($campos['gasto_estado']=="Aprobado"){ echo '(Actual)';} ?></option>
                                    <option value="Cancelado" <?php if ($campos['gasto_estado']=="Cancelado") {
                                    echo 'selected=""';
                                } ?>>Cancelado <?php if($campos['gasto_estado']=="Cancelado"){ echo '(Actual)';} ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="gasto_observacion" class="bmd-label-floating">*** Observación ***</label>
                                <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{8,400}" class="form-control" name="gasto_observacion_up" id="gasto_observacion" maxlength="400" value="<?php echo $campos['gasto_observacion'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <br><br><br>
            <p class="text-center" style="margin-top: 40px;">
                <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
            </p>
        </form>
	</div>

    <!-- MODAL PAGOS -->
    <!-- <div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-labelledby="ModalPago" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <form class="modal-content FormularioAjax" action="<?php echo SERVERURL;?>ajax/gastoAjax.php" method="POST" data-form="save" autocomplete="off">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPago">Agregar pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" >
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr class="text-center bg-dark">
                                    <th>FECHA</th>
                                    <th>MONTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $datos_detalle=$ins_gasto->datos_gasto_controlador("Detalle",$lc->encryption($campos['gasto_codigo']));

                                    if ($datos_detalle->rowCount()>0) {
                                        $datos_detalle=$datos_detalle->fetchAll();
                                        foreach($datos_detalle as $detalles){
                                            echo '<tr class="text-center">
                                                    <td>'.date("d-m-Y",strtotime($pagos['detalle_fecha'])).'</td>
                                                    <td>'.MONEDA.$pagos['pago_total'].'</td>
                                                </tr>';
                                        }
                                    }else{
                                 ?>
                                <tr class="text-center">
                                    <td colspan="2">No hay pagos registrados</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <input type="hidden" name="pago_codigo_reg" value="<?php echo $lc->encryption($campos['gasto_codigo']); ?>">
                        <div class="form-group">
                            <label for="pago_monto_reg" class="bmd-label-floating">Monto en <?php echo MONEDA; ?></label>
                            <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="pago_monto_reg" id="pago_monto_reg" maxlength="10" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-info btn-sm" >Agregar pago</button> &nbsp;&nbsp; 
                    <button type="button" class="btn btn-raised btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div> -->
  <?php }

        } else { 
    ?>  
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>
<?php } ?>
</div>
