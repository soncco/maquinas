<?php
/**
 * Modifica los valores de un rubro
 */
	require_once('home.php');
	require_once('redirect.php');
	
	// Campo
	$field = $_POST['id'];
	$field = explode("-", $field);

	// ID
	$id = $field[1];
	$field = $field[0];
	
	$value = $_POST['value'];
	
	// Actualizamos
	$bcdb->query("UPDATE $bcdb->rubros SET $field = '$value' WHERE ID = '$id'");
	
	// Escribimos
	print $bcdb->get_var("SELECT $field FROM $bcdb->rubros WHERE ID = '$id'");
?>