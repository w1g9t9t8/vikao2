<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; TOP CLIENTES
	</h3>

</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a href="<?php echo SERVERURL; ?>clienteRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>clienteList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTES</a>
		</li>
		<li>
			<a class="active"   href="<?php echo SERVERURL; ?>clientTop/"><i class="fas fa-medal"></i> &nbsp; TOP CLIENTES</a>
		</li>
	</ul>	
</div>

<div class="container-fluid">

	<?php 
        require_once "./controladores/ventaControlador.php";
        $ins_venta= new ventaControlador();
        echo $ins_venta->paginador_clientetop_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$pagina[0],"Aprobado","","");
    ?>
</div>