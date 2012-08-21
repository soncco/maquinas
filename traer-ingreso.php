<?php
/**
 * Devuelve Informes AJAX
 */
	require_once('home.php');
	require_once('redirect.php');
	
	
	$fechai = strftime("%Y-%m-%d %H:%M:%S", strtotime($_POST['fechai']));
	$fechaf = strftime("%Y-%m-%d 23:59:59", strtotime($_POST['fechaf']));
  $maquina = $_POST['maquina'];
	
	$data = get_ingreso_fechas($fechai, $fechaf, $maquina);
	
?>
<table>
<caption>Informe de ingresos desde 
  <?php print utf8_encode(strftime("%a, %d de %B del %Y", strtotime($fechai))); ?> hasta
  <?php print utf8_encode(strftime("%a, %d de %B del %Y", strtotime($fechai))); ?>
</caption>
<thead>
  <tr>
    <th>Nro.</th>
    <th>Maquina</th>
    <th>Lugar</th>
    <th>Cliente</th>
    <th>Tiempo</th>
    <th>Para</th>
    <th>Monto (S/.)</th>
    <th>Acciones</th>
  </tr>
</thead>
<tbody>
  <?php if ($data): ?>
  <?php $alt = "even"; ?>
  <?php
    $total = 0;
    foreach($data as $k=> $recibo): 
  ?>
  <tr class="<?php print $alt ?>">
    <th><?php print $recibo['id']; ?></th>
    <th><?php print $recibo['maquina']; ?></th>
    <td><?php print $recibo['lugar']; ?></td>
    <td><?php print sprintf('%s %s %s', $recibo['nombres'], $recibo['apaterno'], $recibo['amaterno']); ?></td>
    <td><?php print horas_minutos($recibo['minutos']); ?></td>
    <td><?php print strftime('%d/%m/%Y', strtotime($recibo['fecha'])); ?></td>
    <td><?php print ($recibo['monto']); ?></td>
    <td><a href="ver-recibos.php?id=<?php print $recibo['id']; ?>">Detalles</a></td>
    <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
    <?php $total += $recibo['monto']; ?>
  </tr>
  <?php endforeach; ?>
  <tr>
    <th colspan="6" class="align-right">Total de ingresos (S/.):</th>
    <th><?php print number_format($total, 2, '.', ',') ?></th>
  </tr>
  <?php else: ?>
  <tr class="<?php print $alt; ?>">
    <th colspan="8">No se ha registrado ningún pago en este rango de fechas.</th>
  </tr>
  <?php endif; ?>
</tbody>
</table>
<p class="align-center">
  <button type="button" name="print-ingreso" id="print-ingreso">Imprimir</button>
</p>