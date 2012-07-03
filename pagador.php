<?php
/**
 * Registra un nuevo pagador
 */
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	$error = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$pagador = array(
			'nombres' => $_POST['nombres'],
			'documento' => $_POST['documento']);
		
		// Verificación
		if (empty($pagador['nombres'])) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
		
			$pagador = array_map('strip_tags', $pagador);
			// Guarda el pagador
			$id = save_item(0, $pagador, $bcdb->pagadores);
			
			if($id) :
				safe_redirect("recibos.php", "js");
				$msg = "La información se guardó correctamente.";
				exit();
			else:
				$error = true;
				$msg = "Hubo un error al guardar la información, intente nuevamente.";
			endif;
		endif;
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
<script type="text/javascript">
	$(document).ready(function() {
		$('#nombres').focus();
	});
</script>
<title>Rubros | Sistema de Caja</title>
</head>

<body class="single">
    <h1>Nuevo Pagador</h1>
    <?php if (isset($msg)): ?>
        <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmrecibo" id="frmrecibo" method="post" action="pagador.php">
        <fieldset>
            <legend>Información del pagador</legend>
            <p>
                <label for="nombres">Nombres <span class="required">*</span>:</label>
                <input type="text" name="nombres" id="nombres" maxlength="255" size="40" />
            </p>
            <p>
                <label for="documento">Documento:</label>
                <input type="text" name="documento" id="documento" maxlength="11" size="20" />
            </p>
            <p class="align-center">
                <button type="submit" name="submit" id="submit">Guardar</button>
                <button type="button" name="cancel" id="cancel" onclick="self.parent.tb_remove();">Cancelar</button>
            </p>
        </fieldset>
    </form>

</body>
</html>