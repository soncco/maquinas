<?php
/**
 * Registra Recibos
 */
 
 	
	require_once('home.php');
	require_once('redirect.php');
	
	$postback = isset($_POST['submit']);
	
	$error = false;
	$es_alquiler = false;
	// Si es que el formulario se ha enviado
	if($postback) :
		$recibo = array(
			'monto' => $_POST['monto'],
			'observaciones' => $_POST['observaciones'],
			'id_pagador' => $_POST['id_pagador'],
			'id_rubro' => $_POST['id_rubro'],
			'factura' => isset($_POST['factura']) ? 1 : 0
		);
		
		$meta = array(
			'tipo_recibo' => $_POST['tipo_recibo']
		);
		
		$fecha = strftime("%Y-%m-%d", $_POST['now']);
		
		/**
		  * Verificación
		  */
		  
		// Alquiler de maquinarias
						
		// Las maquinarias están identificadas con el ID 13	
		if($recibo['id_rubro'] == get_option('rubro_maquinaria')) :
			$es_alquiler = true;
			$horas = $_POST['horas'];
			
			// Verificamos si es que la misma persona alquiló una maquinaria el mismo día
			$limite_dia = get_alquiler_maquina_dia($_POST['id_maquina'], get_option('rubro_maquinaria'), $recibo['id_pagador'], $fecha);
			if ((int)$limite_dia>0) :
				$error = true;
				$msg = "<strong>" . $_POST['pagador'] . "</strong> ya alquiló esta máquina el día de hoy, no puede alquilar otra vez.";
			endif;
			
			// Verificamos si es que la persona tiene aun horas para usar una máquina
			
			$limite_anio = get_alquiler_maquina_anio($_POST['id_maquina'], get_option('rubro_maquinaria'), $recibo['id_pagador'], date("Y", $_POST['now']));
			
			if(((int)$limite_anio+$horas)>(get_option('limite_anio')*60)) :
				$error = true;
				$restantes = (get_option('limite_anio')*60) - $limite_anio;
				$restantes = horas_minutos($restantes);
				$msg = "<strong>" . $_POST['pagador'] . "</strong> sólo tiene <strong>$restantes</strong> disponibles para alquilar. Corrija el número de horas";
			endif;
			
			if((int)$limite_anio>=get_option('limite_anio')*60) :
				$error = true;
				$msg = "<strong>" . $_POST['pagador'] . "</strong> ha llegado al límite de horas permitidas por año (" . get_option('limite_anio') ." horas). Ya no puede alquilar otra vez hasta el siguiente año.";
			endif;
			
			
		endif;
		
		if (empty($recibo['monto']) || 
			empty($recibo['id_pagador']) ||
			empty($recibo['id_rubro']) ||
			(isset($_POST['id_externo']) && empty($_POST['id_externo'])) ||
			(isset($_POST['id_maquina']) && empty($_POST['id_maquina'])) ||
			(isset($_POST['horas']) && empty($_POST['horas']))
			) :
			$error = true;
			$msg = "Ingrese la información obligatoria.";
		else :
			if(!$error) :
				$recibo['fecha'] = $fecha;
				$recibo = array_map('strip_tags', $recibo);
				// Guarda el recibo
				$id = save_item(0, $recibo, $bcdb->recibos);
				
				// Tipo de recibo 
				if($id) :
					$datos = array(
						'anio' => date("Y", $_POST['now']),
						'id_recibo' => $id
					);
					switch ($meta['tipo_recibo']) :
						case 0 : // Recibo Interno
							$datos['ID'] = save_item(0, $datos, $bcdb->internos);
							
							
							
						break;
						case 1 : // Recibo Externo
							$datos['id_externo'] = $_POST['id_externo'];
							$datos['ID'] = save_item(0, $datos, $bcdb->externos);
						break;
					endswitch;
					
					if($es_alquiler) :
						$alquiler = array(
							'id_maquina' => $_POST['id_maquina'],
							'id_recibo' => $id,
							'horas' => $_POST['horas']
						);
						$alquiler['ID'] = save_item(0, $alquiler, $bcdb->alquileres);
					endif;
				endif;
				
				
				if($id && $datos['ID']) :
					$msg = "La información se guardó correctamente.";
					safe_redirect("ver-recibos.php?ID=$id&saved=1");
					exit();
				else:
					$error = true;
					$msg = "Hubo un error al guardar la información, intente nuevamente.";
				endif;
			endif;
		endif;
	endif;
	
	// Trae las rubros
	$rubros = get_items($bcdb->rubros);
	
	// Trae las máquinas
	$maquinas = get_items($bcdb->maquinas);
	
	$now = time();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/jquery.autocomplete.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" /> 
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/localdata.php"></script>
<script type='text/javascript' src="/scripts/jquery.bgiframe.min.js"></script> 
<script type='text/javascript' src="/scripts/jquery.ajaxQueue.js"></script> 
<script type="text/javascript" src="/scripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/scripts/thickbox.js"></script>
<script type="text/javascript" src="/scripts/jquery.validate.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		/**
		 * 'pagadores' está definido en /scripts/localdata.php
		 * 
		 * Funciones que generan el autocompletado.
		 * *****************************************
		 * Si es que se da click a algún resultado se escoge a esa persona
		 * como la que paga.
		 */
		$("#pagador").autocomplete(pagadores, {
			matchContains: true,
			minChars: 0,				  
			formatItem: function(item) {
				return item.nombres;
			}
		}).result(function(event, item) {
			$("#id_pagador").attr("value", item.id);
		});
		
		/**
		 * Pide un número de recibo si es que es un rubro externo
		 * 
		 */		 
		 // Índices de rubros externos
		 var externos = [<?php 
				// Genera un arreglo JSON de los índices de rubros
				// que son de tipo externo
			 	if ($rubros) :
					$tipos = "";
					foreach($rubros as $rubro):
						if($rubro['tipo']) $tipos .= "'" . $rubro['tipo'] . "',";
					endforeach;
					$tipos = substr($tipos, 0, -1);
					echo $tipos;
				endif;
			?>];
		 
		$('#factura').bind('click', function() {
			//alert();
			if($(this).attr('checked')) {
				$("#id_externo").removeAttr('disabled');
				$(".externos").fadeIn();
				$("#tipo_recibo").val(1);
			} else {
				// Revisar
				if(jQuery.inArray($("#id_rubro").val(), externos) == -1) {
					$("#id_externo").attr('disabled', 'disabled');
					$(".externos").fadeOut();
					$("#tipo_recibo").val(0);
				}
			}
		});
		 
		$("#id_rubro").bind('change', function(){
			var id_rubro = $(this).val();
			
			/**
			 * Verifica si el rubro pertenece a un rubro externo
			 * y muestra el formulario de rubros externos
			 *
			 */	
			if(jQuery.inArray(id_rubro, externos) != -1) {
				$("#id_externo").removeAttr('disabled');
				$(".externos").fadeIn();
				$("#tipo_recibo").val(1);
			} else {
				if(!$("#factura").attr('checked')) {
					$("#id_externo").attr('disabled', 'disabled');
					$(".externos").fadeOut();
					$("#tipo_recibo").val(0);
				}
			}
			
			/**
			 * Muestra el formulario de alquiler de maquinarias
			 * 
			 */	
			const MAQUINARIAS = <?php print get_option('rubro_maquinaria'); ?>; // ID de el rubro de alquiler de maquinarias
			if(id_rubro == MAQUINARIAS) {
				$("#id_maquina").removeAttr('disabled');
				$("#horas").removeAttr('disabled');
				$(".maquinarias").fadeIn();
			} else {
				$("#id_maquina").attr('disabled', 'disabled');
				$("#horas").attr('disabled', 'disabled');
				$(".maquinarias").fadeOut();
			}
		});
		
		// Oculta campos de recibos externos
		$("#id_externo").attr('disabled', 'disabled');
		$(".externos").css('display', 'none');
		
		// Oculta campos de alquiler de maquinarias
		$("#id_maquina").attr('disabled', 'disabled');
		$("#horas").attr('disabled', 'disabled');
		$(".maquinarias").css('display', 'none');
		
		$("#frmrecibo").validate();
		
		/**
		 * Varios
		 */	
		$('#pagador').focus();
	});
</script>
<title>Recibos | Sistema de Caja</title>
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
        	<p class="align-center"><img src="images/coins.png" alt="Pagos" /></p>
        </div>
        <div id="content" class="grid_13">
        	<h1>Recibos</h1>
            <?php if (isset($msg)): ?>
            	<p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
            <?php endif; ?>
            <p class="eContainer">
            </p>
            <form name="frmrecibo" id="frmrecibo" method="post" action="recibos.php">
            	<fieldset>
                	<legend>Datos del recibo</legend>
                    <p>Hoy es: <strong><?php print utf8_encode(strftime("%a, %d de %B del %Y", $now)); ?></strong></p>
                    <p>
                    	<label for="pagador">Recibí de: <span class="required">*</span>:</label>
                        <input type="text" name="pagador" id="pagador" size="60" />
                        <a href="pagador.php?placeValuesBeforeTB_=savedValues&TB_iframe=true&width=480&height=250&modal=true" class="thickbox">Agregar Nuevo</a>
                    </p>
                    <p>
                    	<label for="id_rubro">Por concepto de: <span class="required">*</span>:</label>
                        <select name="id_rubro" id="id_rubro">
                        	<option value="" selected="selected">Seleccione un rubro</option>
                        	<?php foreach ($rubros as $rubro) : ?>
                            <option value="<?php print $rubro['ID']; ?>"><?php print $rubro['descripcion']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <input type="checkbox" name="factura" id="factura" value="1" /> <label for="factura">Se emitirá una factura</label>
                    </p>
                    <p class="externos">
                        <label for="id_externo"><strong>Recibo <abbr title="Número">Nro.</abbr>:</strong> <span class="required">*</span></label>
                        <input type="text" name="id_externo" id="id_externo" size="10" maxlength="20" />
                    </p>
                    <p class="maquinarias">
                    	<label for="id_maquina"><strong>Máquina</strong>: <span class="required">*</span>:</label>
                        <select name="id_maquina" id="id_maquina">
                        	<option value="" selected="selected">Seleccione una máquina</option>
                        	<?php foreach ($maquinas as $maquina) : ?>
                            <option value="<?php print $maquina['ID']; ?>"><?php print $maquina['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="horas"><strong>Minutos</strong>: <span class="required">*</span>:</label>
                        <select name="horas" id="horas">
                        	<option value="" selected="selected">Cantidad de horas</option>
                            <?php $tiempo = hora_quince(get_option('limite_dia')*60) ?>
                        	<?php foreach ($tiempo as $k=>$v) : ?>
                            <option value="<?php print $k; ?>"><?php print $v; ?></option>
                            <?php endforeach; ?>
                        </select>                  
                    </p>
                    <p>
                    	<label for="monto">Monto <abbr title="Nuevos Soles">S/.</abbr>: <span class="required">*</span>:</label>
                        <input type="text" name="monto" id="monto" size="20" class="required number" title="Ingresa un monto correcto" />
                    </p>
                    <p>
                    	<label for="observaciones">Observaciones:</label><br />&nbsp;
                        <textarea rows="8" cols="95" name="observaciones" id="observaciones"></textarea>
                    </p>
                    <p class="align-center">
                    	<button type="submit" name="submit" id="submit">Guardar</button>
                        <input type="hidden" name="id_pagador" id="id_pagador" value="" />
                        <input type="hidden" name="now" id="now" value="<?php print $now; ?>" />
                        <input type="hidden" name="tipo_recibo" id="tipo_recibo" value="0" />
                    </p>
                </fieldset>
            </form>
            
            
        </div><div class="clear"></div>
        <?php include "footer.php"; ?>
    </div>
</body>
</html>