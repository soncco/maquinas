<?php
/**
 * Definici�n de constantes usadas en el sitio
 */
define("BASE_PATH", dirname(__FILE__) . "/");
define("INCLUDE_PATH", BASE_PATH . "includes/");

include_once(INCLUDE_PATH . 'functions.php');
define("BASE_URL", site_url());
define("IMAGES_URL", BASE_URL . "images/");
define("STYLES_URL", BASE_URL . "css/");
define("SCRIPTS_URL", BASE_URL . "scripts/");

define("CHARSET", "UTF-8");
define("NUM_ITEMS", 10); // N�mero de items mostrados en la p�ginaci�n

error_reporting(E_ALL);

setlocale(LC_ALL, '');

/**
 * Par�metros de la base de datos
 */
$db_params = array(
	'db_host' => 'localhost',
	'db_name' => 'caja',
	'db_user' => 'root',
	'db_pass' => 'root'
);

/**
  * Tipo de rubros
  */
$tipos_recibo = array(
	'0' => 'Interno',
	'1' => 'Externo'
);

/**
  * Tipo de usuario
  */
$autipos = array(
		'1'=>"Administrador",
		'2'=>"Registrador"
	);
?>