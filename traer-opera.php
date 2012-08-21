<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$fechai = strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fechai']));
	$fechaf = strftime("%Y-%m-%d 23:59:59", strtotime($_POST['fechaf']));
  $idoperador = $_POST['idoperador'];
  
  $operador = get_item($idoperador, $bcdb->operador);
	
	$data = get_horas_trabajadas($fechai, $fechaf, $idoperador);
  
  $horas = 0;
  if ($data) {
    foreach ($data as $alquiler) {
      $horas += $alquiler['minutos'];
    }	
  }
?>
<h4>Informe del <?php print utf8_encode(strftime("%d/%m/%Y", strtotime($_POST['fechai']))); ?>
 al <?php print utf8_encode(strftime("%d/%m/%Y", strtotime($_POST['fechaf']))); ?> del trabajador <?php print $operador['nombres']; ?></h4>
<p>El total de horas trabajadas son: <strong><?php print horas_minutos($horas); ?></strong></p>

