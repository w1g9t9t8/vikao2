<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS

</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a  href="<?php echo SERVERURL; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR VENTA</a>
        </li>
        <li>
            <a href="<?php echo SERVERURL; ?>venta-venta/"><i class="far fa-calendar-alt"></i> &nbsp; VENTAS</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVERURL; ?>venta-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CANCELADOS</a>
        </li>
        <li>
            <a  href="<?php echo SERVERURL; ?>venta-buscar/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSQUEDA</a>
        </li>

    </ul>
</div>

 <div class="container-fluid">
    <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Ingresa el término de busqueda?</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
                    </div>
                </div>
            </div>
        </div>
    <?php 
        require_once "./controladores/ventaControlador.php";
        $ins_venta= new ventaControlador();

        echo $ins_venta->paginador_venta_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$pagina[0],"Cancelado","","");
    ?>                
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#inputSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>