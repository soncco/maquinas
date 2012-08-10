<?php
/**
 * Registra Lugares
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$lugar = array(
			'nombre' => $_POST['nombre'],
    );
		
		// Verificación
		if (empty($lugar['nombre'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$lugar = array_map('strip_tags', $lugar);
			// Guarda el lugar
			$id = save_item(0, $lugar, $bcdb->lugar);
			
			if($id) :
				$msg = "La información se guardó correctamente.";
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
	
	// Trae los lugares
	$pager = true;
	$lugares = get_items($bcdb->lugar);
	
	// Paginación
	
	$results = @$bcrs->get_navigation();
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
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#nombre').focus();		
		$(".click").editable("/datos-lugares.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
	});
</script>
<title>Lugares | Alquiler de máquinas</title>
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
    <p class="align-center"><img src="images/rubros.png" alt="Rubros" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Lugares</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmlugares" id="frmlugares" method="post" action="lugares.php">
      <fieldset class="collapsible">
        <legend>Información del lugar</legend>
        <p>
          <label for="nombre">Lugar: <span class="required">*</span>:</label>
          <input type="text" name="nombre" id="nombre" maxlength="45" size="40" />
        </p>
        <p class="align-center">
          <button type="submit" name="submit" id="submit">Guardar</button>
        </p>
      </fieldset>
    </form>
    <fieldset class="collapsible">
      <legend>Lugares existentes</legend>
      <p class="war">Los lugares se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
      <table>
        <thead>
          <tr>
            <th>Identificador</th>
            <th>Lugar</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($lugares): ?>
          <?php $alt = "even"; ?>
          <?php foreach($lugares as $k=> $lugar): ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $lugar['id']; ?></th>
            <th><span class="click" id="nombre-<?php print $lugar['id']; ?>"><?php print $lugar['nombre']; ?></span></th>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <th colspan="2">No existen datos</th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php include "pager.php"; ?>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>