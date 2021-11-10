<section class="full-box nav-lateral">
	<div class="full-box nav-lateral-bg show-nav-lateral"></div>
	<div class="full-box nav-lateral-content">
		<figure class="full-box nav-lateral-avatar">
			<i class="far fa-times-circle show-nav-lateral"></i>
			<img src="<?php echo SERVERURL; ?>vistas/assets/avatar/Avatar.png" class="img-fluid" alt="Avatar">
			<figcaption class="roboto-medium text-center">
				<?php 
				echo $_SESSION['nombre_spm']." ".$_SESSION['apellido_spm'];
			?> <br><small class="roboto-condensed-light">
				<?php 
				echo $_SESSION['usuario_spm'];
			?>
			</small>
			</figcaption>
		</figure>
		<div class="full-box nav-lateral-bar"></div>
		<nav class="full-box nav-lateral-menu">
			<ul>
				<li>
					<a href="<?php echo SERVERURL; ?>Inicio/"><i class="fas fa-home"></i> &nbsp; Inicio</a>
				</li>
				
				
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Clientes <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>clienteRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Cliente</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>clienteList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>client-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</a>
						</li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-cookie-bite"></i> &nbsp; Productos <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>productoRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Producto</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>productoList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Productos</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>producto-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Producto</a>
						</li>
					</ul>
				</li>
				

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-cart-plus"></i> &nbsp; Ventas <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>venta-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Venta</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>venta-venta/"><i class="far fa-calendar-alt"></i> &nbsp; Lista de Ventas</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>venta-cancelado/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Ventas Canceladas</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>venta-buscar/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Venta</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-hand-holding-usd"></i> &nbsp; Gastos <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>gastos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Gastos</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>gastos-gastos/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Gastos</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>gastos-buscar/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Gastos</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-truck"></i> &nbsp; Proveedores <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>proveedorRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Proveedor</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>proveedorList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Proveedores</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>proveedor-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Proveedores</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas fa-pallet fa-fw"></i> &nbsp; Items <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar item</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de items</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar item</a>
						</li>
					</ul>
				</li>
				<?php 
				//permiso de sesiones para usuario 1
					if ($_SESSION['privilegio_spm']==1) {
				?>
				<li>
					<a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp; Usuarios <i class="fas fa-chevron-down"></i></a>
					<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>usuarioRegistrar/"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>usuarioList/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de usuarios</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar usuario</a>
						</li>
					</ul>
				</li>
					<?php
			}
			//fin de permiso
			?>
				
			</ul>
		</nav>
	</div>
</section>