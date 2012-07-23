<?php
/**
 * Inicialización de variables e inclusión de archivos
 */
include_once('config.php');

include_once(INCLUDE_PATH . 'ez_sql.php');

# Tablas
$table_prefix = "";
$bcdb->alquiler       = $table_prefix . 'alquiler';
$bcdb->cliente        = $table_prefix . 'cliente';
$bcdb->combustible    = $table_prefix . 'combustible';
$bcdb->lugar          = $table_prefix . 'lugar';
$bcdb->mantenimiento  = $table_prefix . 'mantenimiento';
$bcdb->maquina        = $table_prefix . 'maquina';
$bcdb->operador       = $table_prefix . 'operador';
$bcdb->opciones       = $table_prefix . 'opciones';
$bcdb->admin          = $table_prefix . 'admin';

# Funciones independientes
include_once(INCLUDE_PATH . 'formatting-functions.php');
include_once(INCLUDE_PATH . 'pager.class.php');
include_once(INCLUDE_PATH . 'user-functions.php');
include_once(INCLUDE_PATH . 'item-functions.php');
include_once(INCLUDE_PATH . 'various-functions.php');
include_once(INCLUDE_PATH . 'numbertotext.php');
include_once(INCLUDE_PATH . 'krumo/class.krumo.php');

# Iniciamos
send_headers();

global_sanitize();

$pager = false;

$_SERVER['PHP_SELF'] = htmlspecialchars(preg_replace('`(\.php).*$`', '$1', $_SERVER['PHP_SELF']), ENT_QUOTES, 'utf-8');
$self = $_SERVER['PHP_SELF'];

?>