<?php
/**
 * Registra Rubros
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$rubro = array(
			'codigo' => $_POST['codigo'],
			'descripcion' => $_POST['descripcion']);
		
		// Verificación
		if (empty($rubro['codigo']) || 
			empty($rubro['descripcion'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$rubro = array_map('strip_tags', $rubro);
			// Guarda el rubro
			$id = save_item(0, $rubro, $bcdb->rubros);
			
			if($id) :
				$msg = "La información se guardó correctamente.";
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
	endif;
	
	// Trae las rubros
	$pager = true;
	$rubros = get_items($bcdb->rubros);
	
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
		$('#codigo').focus();
		
		$(".click").editable("/datos-rubro.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
	});
</script>
<title>Rubros | Sistema de Caja</title>
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
        	<p class="align-center"><img src="images/rubros.png" alt="Rubros" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Rubros</h1>
            <?php if (isset($msg)): ?>
            	<p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
            <?php endif; ?>
            <form name="frmrecibo" id="frmrecibo" method="post" action="rubros.php">
            	<fieldset class="collapsible">
                	<legend>Información del rubro</legend>
                    <p>
                    	<label for="codigo">Código <span class="required">*</span>:</label>
                        <input type="text" name="codigo" id="codigo" maxlength="20" size="30" />
                    </p>
                    <p>
                    	<label for="descripcion">Descripción <span class="required">*</span>:</label>
                        <input type="text" name="descripcion" id="descripcion" maxlength="200" size="45" />
                    </p>
                    <p>
                    	<label>Tipo:</label>
                        <input type="radio" name="tipo" id="tipo_interno" value="0" checked="checked" /> <label for="tipo_interno">Interno</label>
                        <input type="radio" name="tipo" id="tipo_externo" value="1" /> <label for="tipo_externo">Externo</label>
                    </p>
                    <p class="align-center">
                    	<button type="submit" name="submit" id="submit">Guardar</button>
                    </p>
                </fieldset>
            </form>
            
            <fieldset class="<?php if(!isset($_GET['PageIndex'])): ?>collapsibleClosed<?php else: ?>collapsible<?php endif; ?>">
                <legend>Rubros existentes</legend>
                <p class="war">Los rubros se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rubros): ?>
                        <?php $alt = "even"; ?>
                        <?php foreach($rubros as $k=> $rubro): ?>
                        <tr class="<?php print $alt ?>">
                        	<th><?php print $rubro['ID']; ?></th>
                            <th><span class="click" id="codigo-<?php print $rubro['ID']; ?>"><?php print $rubro['codigo']; ?></span></th>
                            <td><span class="click" id="descripcion-<?php print $rubro['ID']; ?>"><?php print $rubro['descripcion']; ?></span></td>
                            <td><?php print $tipos_recibo[$rubro['tipo']]; ?></td>
                            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="<?php print $alt; ?>">
                            <td colspan="4">No existen datos</th>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php include "pager.php"; ?>
            </fieldset>
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>