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
	
	// Trae las pagadores
	
	$buscar = isset($_GET['buscar']);
	if($buscar):
		$palabra = trim($_GET['s']);
		$pagadores = search_pagadores($palabra);
	else:
		$pager = true;
		$pagadores = get_items($bcdb->pagadores, "nombres");
		// Paginación
		$results = @$bcrs->get_navigation();
	endif;
	
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
<script type="text/javascript" src="/scripts/jquery.jeditable.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".click").editable("/datos-pagador.php", {
			indicator : "Guardando...",
			tooltip   : "Click para editar..."
		});
	});
</script>
<title>Pagadores | Sistema de Caja</title>
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
        	<p class="align-center"><img src="images/clients.png" alt="Pagadores" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Pagadores</h1>
            <?php if (isset($msg)): ?>
            	<p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
            <?php endif; ?>
            <form name="search" id="search" method="get" action="pagadores.php">
               <fieldset <?php if(!$buscar): ?>class="collapsibleClosed"<?php endif; ?>>
                <legend>Buscar pagador</legend>
                <p>
                    <label for="s">Ingrese una o más palabras:</label>
                    <input type="text" name="s" id="s" <?php if($buscar): ?>value="<?php print $palabra; ?>"<?php endif; ?> />
                    <button name="buscar" id="buscar" type="submit">Buscar</button>
                </p>
               </fieldset>
           </form>
            <fieldset>
                <legend>Lista de Pagadores</legend>
                <p class="war">Los pagadores se pueden editar, sin embargo tenga cuidado al hacerlo ya que se pueden confundir datos existentes.</p>
                <table>
                	<?php if($buscar): ?>
						<caption>Mostrando resultados con: "<?php print $palabra; ?>"</caption>
					<?php endif; ?>
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Nombres</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pagadores): ?>
                        <?php $alt = "even"; ?>
                        <?php foreach($pagadores as $k=> $pagador): ?>
                        <tr class="<?php print $alt ?>">
                            <th><span class="click" id="documento-<?php print $pagador['ID']; ?>"><?php print $pagador['documento']; ?></span></th>
                            <td><span class="click" id="nombres-<?php print $pagador['ID']; ?>"><?php print $pagador['nombres']; ?></span></td>
                            <td><a href="ver-pagos.php?id_pagador=<?php print $pagador['ID']; ?>">Ver pagos</a></td>
                            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr class="<?php print $alt; ?>">
                            <td colspan="3">No existen datos</th>
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