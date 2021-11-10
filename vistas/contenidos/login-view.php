<div class="login-container">
	<div class="login-content">
		<div class="logo">
			<p>BIENVENIDOS A <span>VIKAO</span></p>
		</div>
		<p class="text-center-log">
			Ingrese sus datos
		</p>
		<form action="" method="POST" autocomplete="off" >
			<div class="form-group">
				<label for="UserName" class="bmd-label-floating colorlog"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
				<input type="text" class="form-control text-light" id="UserName" name="usuario_log" pattern="[a-zA-Z0-9]{7,35}" maxlength="35" required="" >
			</div>
			<div class="form-group">
				<label for="UserPassword" class="bmd-label-floating colorlog"><i class="fas fa-key"></i> &nbsp; Contraseña</label>
				<input type="password" class="form-control text-light" id="UserPassword" name="clave_log" pattern="[a-zA-Z0-9$@.-]{7,30}" maxlength="100" required="" >
			</div>
			<button type="submit" class="btn-login text-center">LOG IN</button>
		</form>
	</div>
</div>
<?php

if(isset($_POST['usuario_log']) && isset($_POST['clave_log'])){
	require_once "./controladores/loginControlador.php";
	$ins_login=new loginControlador();
	echo $ins_login->iniciar_sesion_controlador();
}
	?>
