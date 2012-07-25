<?php
/**
 * Informes.
 */
	require_once('home.php');
	require_once('redirect.php');

	$now = time();
	$fecha = strftime("%Y-%m-%d", $now);

	$data = get_recibos_dia($fecha);

	// Trae las máquinas.
	$maquinas = get_items($bcdb->maquina);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/theme/ui.all.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript" src="/scripts/jquery.calendar.js"></script>
<script type="text/javascript" src="/scripts/jquery.ui.all.min.js"></script>
<script type="text/javascript" src="/scripts/tabs.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Funciones de impresión
		 * 
		 */
		 
		 // Imprime los reportes diarios
		$('#print-daily').bind('click', function(){
			fecha = $('#fecha').val();
			window.open('print-daily.php?fecha='+fecha, 'print', 'location=0, status=0, width=800, height=600');
		});
		
		// Imprime los reportes por fecha
		$('#print-per').bind('click', function() {
			var fecha_inicio = $("#fecha-inicio").val();
			var fecha_fin = $("#fecha-fin").val();
			window.open('print-per.php?fecha-inicio='+fecha_inicio+'&fecha-fin='+fecha_fin, 'print', 'location=0, status=0, width=800, height=600');
		});
		
		simg = '<img src="images/loading.gif" alt="Cargando" id="simg" />';
		
		// Muestra reportes diarios según fecha
		$('#change-daily').bind('click', function(){
			$(this).after(simg);
			var fecha = $("#fecha-daily").val();
			$('#fecha').val(fecha);
			$.ajax({
			   type: "POST",
			   url: "traer-daily.php",
			   data: "fecha=" + fecha,
			   success: function(msg){
          $('#daily-results').empty();
          $('#daily-results').append(msg);
          $('#simg').remove();
			   }
			 });
		});
    
    // Muestra los alquileres de cierta fecha
		$('#change-reservas').bind('click', function(){
			$(this).after(simg);
			var fecha = $("#fecha-reservas").val();
			$('#fecha').val(fecha);
			$.ajax({
			   type: "POST",
			   url: "traer-reservas.php",
			   data: "fecha=" + fecha,
			   success: function(msg){
          $('#reserva-results').empty();
          $('#reserva-results').append(msg);
          $('#simg').remove();
			   }
			 });
		});
	});
</script>
<title>Informes | Alquiler de máquinas</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Alquiler de máquinas</span></a> </h1>
    <?php include "menutop.php"; ?>
    <?php if(isset($_SESSION['loginuser'])) : ?>
    <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
    <?php endif; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/report.png" alt="Informes" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Informes</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <ul class="i-tabs">
      <li><a href="#i-diario">Diario</a></li>
      <li><a href="#i-reservas">Reservas</a></li>
    </ul>
    <div class="i-tab-container">
      <div id="i-diario" class="i-tab-content">
        <p class="msg">Este es el reporte diario de alquileres.</p>
        <fieldset class="collapsibleClosed">
          <legend>Cambiar fecha</legend>
          <p>
            <label for="fecha-daily">Escoja la fecha del informe:</label>
            <input type="text" name="fecha-daily" id="fecha-daily" class="date" value="<?php print $fecha; ?>" />
            <button type="button" name="change-daily" id="change-daily" class="small">Cambiar</button>
          </p>
        </fieldset>
        <div id="daily-results">
          <table>
            <caption>
            Informe del <?php print utf8_encode(strftime("%a, %d de %B del %Y", $now)); ?>
            </caption>
            <thead>
              <tr>
                <th>Nro.</th>
                <th>Maquina</th>
                <th>Lugar</th>
                <th>Cliente</th>
                <th>Tiempo</th>
                <th>Para</th>
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
                <td><?php print strftime('%d/%m/%Y', strtotime($recibo['fecha'])); ?></td>
                <td><a href="ver-recibos.php?id=<?php print $recibo['id']; ?>">Detalles</a></td>
                <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
              </tr>
              <?php endforeach; ?>
              <?php else: ?>
              <tr class="<?php print $alt; ?>">
                <th colspan="7">No se ha registrado ningún pago en esta fecha</th>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <p class="align-center">
          <button type="button" name="print-daily" id="print-daily">Imprimir</button>
          <input type="hidden" name="fecha" id="fecha" value="<?php print $fecha; ?>" />
        </p>
      </div>
      <div id="i-reservas" class="i-tab-content">
        <p class="msg">Este es el reporte de reservas.</p>
        <fieldset class="collapsible">
          <legend>Cambiar fecha</legend>
          <p>
            <label for="fecha-reservas">Escoja la fecha del informe:</label>
            <input type="text" name="fecha-reservas" id="fecha-reservas" class="date" value="<?php print $fecha; ?>" />
            <button type="button" name="change-reservas" id="change-reservas" class="small">Cambiar</button>
          </p>
        </fieldset>
        <div id="reserva-results"> </div>
        <p class="align-center">
          <button type="button" name="print-reservas" id="print-reservas" style="display: none;">Imprimir</button>
        </p>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>