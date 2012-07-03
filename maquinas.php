<?php
/**
 * Registra Máquinas
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$maquina = array(
			'nombre' => $_POST['nombre'],
			'uit' => $_POST['uit'],
			'tarifa' => $_POST['tarifa'],
			'tiempo' => $_POST['tiempo']);
		
		// Verificación
		if (empty($maquina['nombre']) ||
			empty($maquina['tarifa'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$maquina = array_map('strip_tags', $maquina);
			// Guarda la máquina
			$id = save_item(0, $maquina, $bcdb->maquinas);
			
			if($id) :
				$msg = "La información se guardó correctamente.";
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
	
	// Trae las máquinas
	$pager = true;
	$maquinas = get_items($bcdb->maquinas);
	
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
		$(".click").editable("/datos-maquina.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
		$('#nombre').focus();
//		$("#frmmaquina").validate();
	});
</script>
<title>Máquinas | Sistema de Caja</title>
</head>

<body>
	<div class="container_16">
   	  <div id="header">
       	<h1 id="logo"> <a href="/"><span>Sistema de Caja</span></a> </h1>
<?php include "menutop.php"; ?>
          <?php if(isset($_SESSION['loginuser'])) : ?>
          <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
          <?php endif; ?>
        </div>
        <div class="clear"></div>
        
        <div id="icon" class="grid_3">
        	<p class="align-center"><img src="images/maquina.png" alt="Máquinas" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Máquinas</h1>
            <?php if (isset($msg)): ?>
            	<p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
            <?php endif; ?>
            <form name="frmmaquina" id="frmmaquina" method="post" action="maquinas.php">
            	<fieldset class="collapsible">
                	<legend>Información de la máquina</legend>
                    <p>
                    	<label for="nombre">Nombre <span class="required">*</span>:</label>
                        <input type="text" name="nombre" id="nombre" maxlength="200" size="45" />
                    </p>
                    <p>
                    	<label for="uit">% UIT:</label>
                        <input type="text" name="uit" id="uit" size="15" class="required number" />
                    </p>
                    <p>
                    	<label for="tarifa">Tarifa S/. <span class="required">*</span>:</label>
                        <input type="text" name="tarifa" id="tarifa" size="15" class="required number" />
                    </p>
                    <p>
                    	<label>La tarifa se cobra por:</label>
                        <input type="radio" name="tiempo" id="hora" value="H" checked="checked" /> <label for="hora">Hora</label>
                        <input type="radio" name="tiempo" id="dia" value="D" /> <label for="dia">Día</label>
                    </p>
                    <p class="align-center">
                    	<button type="submit" name="submit" id="submit">Guardar</button>
                    </p>
                </fieldset>
            </form>
            
            <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
                <legend>Máquinas existentes</legend>
                <p class="war">Las máquinas se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
                <table>
                    <thead>
                        <tr>
                            <th>Máquina</th>
                            <th>% UIT</th>
                            <th>Tarifa</th>
                            <th>Cobrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($maquinas): ?>
                        <?php $alt = "even"; ?>
                        <?php foreach($maquinas as $k=> $maquina): ?>
                        <tr class="<?php print $alt ?>">
                            <th><span class="click" id="nombre-<?php print $maquina['ID']; ?>"><?php print $maquina['nombre']; ?></span></td>
                            <td><?php print $maquina['uit']; ?></td>
                            <td><?php print $maquina['tarifa']; ?></td>
                            <td><?php print ($maquina['tiempo']=='D') ? 'Día' : 'Hora'; ?></td>
                            <td><a href="ver-usomaquina.php?id_maquina=<?php print $maquina['ID']; ?>">Ver reporte</a></td>
                            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="<?php print $alt; ?>">
                            <td colspan="5">No existen datos</th>
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