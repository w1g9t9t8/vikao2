
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE
	</h3>

</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>clientRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>clienteList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CLIENTES</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CLIENTE</a>
		</li>
		<li>
			<a   href="<?php echo SERVERURL; ?>clientTop/"><i class="fas fa-medal"></i> &nbsp; TOP CLIENTES</a>
		</li>
	</ul>	
</div>

<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/clienteAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_dni" class="bmd-label-floating">DNI</label>
							<input type="text" pattern="[0-9]{8}" class="form-control" name="cliente_dni_reg" id="cliente_dni" maxlength="27">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_nombre" class="bmd-label-floating">Nombre</label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="cliente_nombre_reg" id="cliente_nombre" maxlength="40">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="cliente_apellido" class="bmd-label-floating">Apellido</label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="cliente_apellido_reg" id="cliente_apellido" maxlength="40">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="cliente_telefono" class="bmd-label-floating">Celular</label>
							<input type="text" pattern="[0-9]{9}" class="form-control" name="cliente_telefono_reg" id="cliente_telefono" maxlength="20">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="cliente_direccion" class="bmd-label-floating">Dirección</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="cliente_direccion_reg" id="cliente_direccion" maxlength="150">
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
			&nbsp; &nbsp;
			<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
		</p>
	</form>
</div>