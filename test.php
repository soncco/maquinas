<?php
/**
 * Opciones del Sistema
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$sql = "ALTER TABLE  `alquiler` ADD  `monto` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0.00' AFTER  `recibo` ;";

	print ($bcdb->query($sql)) ? "Actualización realizada" : "No se pueda actualizar";	
	
?>