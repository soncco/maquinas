<?php
/**
 * Inicialización de variables e inclusión de archivos
 */
include_once('config.php');

include_once(INCLUDE_PATH . 'ez_sql.php');

# Tablas
$table_prefix = "";
$bcdb->rubros				= $table_prefix . 'rubros';
$bcdb->pagadores			= $table_prefix . 'pagadores';
$bcdb->recibos				= $table_prefix . 'recibos';
$bcdb->internos				= $table_prefix . 'internos';
$bcdb->externos				= $table_prefix . 'externos';
$bcdb->maquinas				= $table_prefix . 'maquinas';
$bcdb->alquileres			= $table_prefix . 'alquileres';
$bcdb->opciones				= $table_prefix . 'opciones';
$bcdb->admin  				= $table_prefix . 'admin';

# Funciones independientes
include_once(INCLUDE_PATH . 'formatting-functions.php');
include_once(INCLUDE_PATH . 'pager.class.php');
include_once(INCLUDE_PATH . 'user-functions.php');
include_once(INCLUDE_PATH . 'item-functions.php');
include_once(INCLUDE_PATH . 'various-functions.php');
include_once(INCLUDE_PATH . 'numbertotext.php');

# Iniciamos
send_headers();

global_sanitize();

$pager = false;

$_SERVER['PHP_SELF'] = htmlspecialchars(preg_replace('`(\.php).*$`', '$1', $_SERVER['PHP_SELF']), ENT_QUOTES, 'utf-8');
$self = $_SERVER['PHP_SELF'];

?>