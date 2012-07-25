<?php
/**
 * Muestra los pagos de un determinado pagador
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$idcliente = $_GET['idcliente'];
  
  $cliente = get_item($idcliente, $bcdb->cliente);
  $pager = false;
	$data = get_alquileres_cliente($idcliente);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" /> 
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	});
</script>
<title>Ver Alquileres | Alquiler de máquinas</title>
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
        	<p class="align-center"><img src="images/clients.png" alt="Pagos" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Informes</h1>
        <table>
            <caption>
            Informe del cliente <?php print utf8_encode(sprintf('%s %s %s', $cliente['nombres'], $cliente['apaterno'], $cliente['amaterno'])); ?>
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
          <?php include "pager.php"; ?>
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>