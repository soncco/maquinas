<?php
/**
 * Modifica los valores de una máquina
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
	$bcdb->query("UPDATE $bcdb->maquinas SET $field = '$value' WHERE ID = '$id'");
	
	// Escribimos
	print $bcdb->get_var("SELECT $field FROM $bcdb->maquinas WHERE ID = '$id'");
?>