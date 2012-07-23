<?php
/**
 * Varias funciones
 */

/**
 * Devuelve todas las opciones
 *
 * @return array
 */
function get_options () {
	global $bcdb;
	$results = $bcdb->get_results("SELECT * FROM $bcdb->opciones");
	$options = array();
	foreach($results as $k => $v) {
		$options[$v['nombre']] = $v['descripcion'];
	}
	return $options;
	
}

/**
 * Devuelve la descripci�n de una opci�n
 *
 * @param string $option opci�n a traer
 * @return string
 */
function get_option ($option) {
	global $bcdb;
	return $bcdb->get_var("SELECT descripcion FROM $bcdb->opciones WHERE nombre = '$option'");
}

/**
 * Guarda la descripci�n de una opci�n
 *
 * @param string $option opci�n
 * @param string $value nuevo valor
 * @return boolean
 */
function save_option($option, $value) {
	global $bcdb;
	return $bcdb->query("UPDATE $bcdb->opciones SET descripcion = '$value' WHERE nombre = '$option'");
}

?>