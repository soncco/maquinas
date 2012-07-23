<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fecha = $_POST['fecha'];
	
	$data = get_reservas_dia($fecha);
	
?>
<table>
<caption>Informe del <?php print utf8_encode(strftime("%a, %d de %B del %Y", strtotime($fecha))); ?></caption>
<thead>
  <tr>
    <th>Nro.</th>
    <th>Maquina</th>
    <th>Lugar</th>
    <th>Cliente</th>
    <th>Tiempo</th>
    <th>Acciones</th>
  </tr>
</thead>
<tbody>
  <?php if ($data): ?>
  <?php $alt = "even"; ?>
  <?php
    foreach($data as $k=> $recibo): 
      if($recibo['anulado']) $alt = "error";
  ?>
  <tr class="<?php print $alt ?>">
    <th><?php print $recibo['id']; ?></th>
    <th><?php print $recibo['maquina']; ?></th>
    <td><?php print $recibo['lugar']; ?></td>
    <td><?php print ($recibo['anulado']) ? "ANULADO" : sprintf('%s %s %s', $recibo['nombres'], $recibo['apaterno'], $recibo['amaterno']); ?></td>
    <td><?php print horas_minutos($recibo['minutos']); ?></td>
    <td><a href="ver-recibos.php?id=<?php print $recibo['id']; ?>">Detalles</a></td>
    <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
  </tr>
  <?php endforeach; ?>
  <?php else: ?>
  <tr class="<?php print $alt; ?>">
    <th colspan="6">No se ha registrado ningún pago en esta fecha</th>
  </tr>
  <?php endif; ?>
</tbody>
</table>