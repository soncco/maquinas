<?php
/**
 * Modifica los valores de un lugar
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
	$bcdb->query("UPDATE $bcdb->lugar SET $field = '$value' WHERE id = '$id'");
	
	// Escribimos
	print $bcdb->get_var("SELECT $field FROM $bcdb->lugar WHERE id = '$id'");
?>