<?php
/**
 * Verificación de permisos
 * También revisa si es que la sesión es activa, si no es así envía a la página de login
 */
 
// Páginas permitidas a los que no son administradores
$allowed = array(
	 "/",
	 "/index.php",
	 "/recibos.php",
	 "/informes.php",
	 "/pagador.php",
	 "/traer-daily.php",
	 "/traer-per.php",
	 "/traer-rubro.php",
	 "/ver-recibos.php",
	 "/print-recibo.php",
	 "/print-daily.php",
	 "/print-per.php"
 );
 
// Redirección al login
if(!isset($session_active)) {
	header("Location: ". BASE_URL . "login.php?r=" . $_SERVER['PHP_SELF']);
	exit();
}

// Error
if(!is_admin($_SESSION['loginuser']['ID'])) {
	if(!in_array($_SERVER['PHP_SELF'], $allowed)) {
		header("Location: ". BASE_URL . "error.php");
		exit();
	}
}
?>