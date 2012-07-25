<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fechai = strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fechai']));
	$fechaf = strftime("%Y-%m-%d 23:59:59", strtotime($_POST['fechaf']));
	
	$total = get_combustible_fechas($fechai, $fechaf);
	
?>
<p>La cantidad de combustible consumida desde 
  <strong><?php print strftime("%d/%m/%Y", strtotime($_POST['fechai'])); ?></strong> hasta 
    <strong><?php print strftime("%d/%m/%Y", strtotime($_POST['fechaf'])); ?></strong> es 
    <strong><?php print $total; ?></strong> galones.</p>