<?php
/**
 * Muestra Recibos
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$saved = isset($_GET['saved']);
	
	if($saved) :
		$msg = "La información se guardó correctamente";
	endif;
	
	$id = $_GET['id'];
	
	$recibo = get_recibo($id);
	
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
<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * Imprime el recibo
		 * 
		 */
		$('#print').bind('click', function(){
			ID = $('#id').val();
			window.open('print-recibo.php?ID='+ID, 'print', 'location=0, status=0, width=800, height=600');
		}); 
		
		// Anula un recibo
		$('#anular').bind('click', function(){
			if(window.confirm('¿Estás seguro de anular este recibo? Esta acción no se puede deshacer.')) {
				ID = $('#id').val();
				$.ajax({
				   type: "POST",
				   url: "anular-recibo.php",
				   data: "ID=" + ID,
				   success: function(msg){
					 $('#frmrecibo').prepend('<p class="error">Este recibo ha sido anulado.</p>');
					 $('#anular').attr('disabled', 'disabled');
					 $('#anular').css('display', 'none');
					 $('#monto').text('0.00');
				   },
				});
			}
		}); 
	});
</script>
<title>Alquiler | Alquiler de máquinas</title>
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
    <p class="align-center"><img src="images/report.png" alt="Recibos" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Alquiler</h1>
    <?php if (isset($msg)): ?>
    <p class="msg"><?php print $msg; ?></p>
    <?php endif; ?>
    <?php if ($recibo['anulado']) : ?>
    <p class="error"> Este alquiler está anulado. </p>
    <?php endif; ?>
    <form name="frmrecibo" id="frmrecibo" method="post" action="recibos.php">
      <fieldset>
        <legend>Alquiler Nro. <?php print $recibo['id']; ?></legend>
        <p>
          <label for="fecha">Fecha:</label>
          <strong><?php print strftime("%d %b %Y", strtotime($recibo['fecha'])); ?></strong>
          <label for="recibo">Recibo de caja: </label>
          <strong><?php print $recibo['recibo']; ?></strong>
        </p>
        <p>
          <label for="cliente">Cliente:</label>
          <strong><?php print sprintf("%s %s %s", $recibo['nombres'], $recibo['apaterno'], $recibo['amaterno']); ?></strong>
        </p>
        <p>
          <label for="idlugar">Sector o comunidad:</label>
          <strong><?php print $recibo['lugar']; ?></strong>
        </p>
        <p>
          <label for="idmaquina">Máquina:</label>
          <strong><?php print $recibo['maquina']; ?></strong>
          <label for="minutos">Tiempos:</label>
          <strong><?php print horas_minutos($recibo['minutos']); ?></strong> 
        </p>
        <p>
          <label for="combustiblenro">Vale de combustible Nro:</label>
          <strong><?php print $recibo['combustiblenro']; ?></strong>
          <label for="combustiblecan">Cantidad de combustible:</label>
          <strong><?php print $recibo['combustiblecan']; ?></strong>
        </p>
        <p>
          <label for="observaciones">Observaciones:</label><br />&nbsp;
           <strong><?php print $recibo['observaciones']; ?></strong>
        </p>
        <p class="align-center">
          <button type="button" name="print" id="print">Imprimir</button>
          <?php if (!$recibo['anulado']) : ?>
          <button type="button" name="anular" id="anular">Anular</button>
          <?php endif; ?>
          <input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
        </p>
      </fieldset>
    </form>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>