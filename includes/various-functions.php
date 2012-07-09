<?php
/**
 * Funciones varias
 */
 
/**
* Devuelve los minutos que alquiló cierta persona en un día
*
* @param int $id_rubro Id del rubro alquiler de maquinarias
* @param int $id_pagador Id de la persona
* @param date $fecha Fecha del día
* @return int
*/
function get_alquiler_dia($id_rubro, $id_pagador, $fecha) {
	global $bcdb;
	$sql = "SELECT a.horas FROM $bcdb->recibos r
			INNER JOIN $bcdb->alquileres a
			ON r.ID = a.id_recibo
			WHERE r.id_rubro = '$id_rubro'
			AND r.id_pagador = '$id_pagador'
			AND r.fecha = '$fecha'
			AND r.anulado != '1'";
	return $bcdb->get_var($sql);
}

/**
* Devuelve los minutos que alquiló cierta persona en un año
*
* @param int $id_rubro Id del rubro alquiler de maquinarias
* @param int $id_pagador Id de la persona
* @param date $anio Este año
* @return int
*/
function get_alquiler_anio($id_rubro, $id_pagador, $anio) {
	global $bcdb;
	$sql = "SELECT SUM(a.horas) AS horas FROM $bcdb->recibos r
			INNER JOIN $bcdb->alquileres a
			ON r.ID = a.id_recibo
			WHERE r.id_rubro = '$id_rubro'
			AND r.id_pagador = '$id_pagador'
			AND r.fecha BETWEEN '$anio-01-01'
			AND '$anio-12-31'";
	return $bcdb->get_var($sql);
}

/**
* Devuelve los minutos que alquiló cierta persona en un día
*
* @param int $id_maquina Id de la máquina
* @param int $id_rubro Id del rubro alquiler de maquinarias
* @param int $id_pagador Id de la persona
* @param date $fecha Fecha del día
* @return int
*/
function get_alquiler_maquina_dia($id_maquina, $id_rubro, $id_pagador, $fecha) {
	global $bcdb;
	$sql = "SELECT a.horas FROM $bcdb->recibos r
			INNER JOIN $bcdb->alquileres a
			ON r.ID = a.id_recibo
			WHERE r.id_rubro = '$id_rubro'
			AND r.id_pagador = '$id_pagador'
			AND r.fecha = '$fecha'
			AND r.anulado != '1'
			AND a.id_maquina = '$id_maquina'";
	return $bcdb->get_var($sql);
}

/**
* Devuelve los minutos que alquiló cierta persona en un año
*
* @param int $id_maquina Id de la máquina
* @param int $id_rubro Id del rubro alquiler de maquinarias
* @param int $id_pagador Id de la persona
* @param date $anio Este año
* @return int
*/
function get_alquiler_maquina_anio($id_maquina, $id_rubro, $id_pagador, $anio) {
	global $bcdb;
	$sql = "SELECT SUM(a.horas) AS horas FROM $bcdb->recibos r
			INNER JOIN $bcdb->alquileres a
			ON r.ID = a.id_recibo
			WHERE r.id_rubro = '$id_rubro'
			AND r.id_pagador = '$id_pagador'
			AND r.fecha BETWEEN '$anio-01-01'
			AND '$anio-12-31'
			AND a.id_maquina = '$id_maquina'";
	return $bcdb->get_var($sql);
}

/**
* Muestra el uso de una máquina
*
* @param int $id_maquina ID
* @return array
*/
function get_alquileres($id_maquina, $fecha_inicio, $fecha_fin) {
	global $bcdb;
	$sql = "SELECT r.ID as id_recibo, p.nombres, a.horas, r.fecha, r.anulado
			FROM $bcdb->alquileres a
			INNER JOIN $bcdb->maquinas m
			ON a.id_maquina = m.ID
			INNER JOIN $bcdb->recibos r
			ON a.id_recibo = r.ID
			INNER JOIN $bcdb->pagadores p
			ON r.id_pagador = p.ID
			WHERE a.id_maquina = '$id_maquina'
			AND r.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			
	$alq = $bcdb->get_results($sql);
	
	$total = 0;
	$data = array();
	
	if($alq) :
		foreach($alq as $k => $v) :
			$total += $v['horas'];
		endforeach;
	endif;
	
	$data['alquileres'] = $alq;
	$data['total'] = $total;
	return $data;
}


/**
* Devuelve los recibos girados en cierta fecha
*
* @param date $fecha La fecha
* @return array
*/
function get_recibos_dia($fecha) {
	global $bcdb;
	$sql = "SELECT re.*, ru.codigo, ru.descripcion, ru.tipo, p.nombres
			FROM $bcdb->recibos re
			INNER JOIN $bcdb->rubros ru
			ON re.id_rubro = ru.ID
			INNER JOIN $bcdb->pagadores p
			ON re.id_pagador = p.ID
			WHERE fecha = '$fecha'
			ORDER BY re.ID";
	$recibos = $bcdb->get_results($sql);
	
	$total = 0;
	if($recibos) :
		foreach($recibos as $k=>$recibo) :
			if($recibo['factura']) $recibo['tipo'] = 1;
			switch($recibo['tipo']) :
				case "0":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('ID', 'id_recibo', $recibo['ID'], $bcdb->internos);
				break;
				case "1":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('id_externo', 'id_recibo', $recibo['ID'], $bcdb->externos);
				break;
			endswitch;
			
			$total += $recibo['monto'];
		endforeach;
	endif;
	
	$data['recibos'] = $recibos;
	$data['total'] = $total;
	
	return $data;
}

/**
* Devuelve los recibos girados a cierto pagador
*
* @param date $id_pagador El pagador
* @return array
*/
function get_recibos_pagador($id_pagador) {
	global $bcdb;
	$sql = "SELECT re.*, ru.codigo, ru.descripcion, ru.tipo
			FROM $bcdb->recibos re
			INNER JOIN $bcdb->rubros ru
			ON re.id_rubro = ru.ID
			WHERE id_pagador = '$id_pagador'";
	$recibos = $bcdb->get_results($sql);
	
	$total = 0;
	if($recibos) :
		foreach($recibos as $k=>$recibo) :
			if($recibo['factura']) $recibo['tipo'] = 1;
			switch($recibo['tipo']) :
				case "0":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('ID', 'id_recibo', $recibo['ID'], $bcdb->internos);
				break;
				case "1":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('id_externo', 'id_recibo', $recibo['ID'], $bcdb->externos);
				break;
			endswitch;
			
			$total += $recibo['monto'];
		endforeach;
	endif;
	
	$data['recibos'] = $recibos;
	$data['total'] = $total;
	
	return $data;
}

/**
* Devuelve los recibos girados a cierto pagador de acuerdo a un rubro
*
* @param date $id_pagador El pagador
* @param int $id_rubro El rubro
* @return array
*/
function get_recibos_pagador_rubro($id_pagador, $id_rubro) {
	global $bcdb;
	$sql = "SELECT re.*, ru.codigo, ru.descripcion, ru.tipo
			FROM $bcdb->recibos re
			INNER JOIN $bcdb->rubros ru
			ON re.id_rubro = ru.ID
			WHERE id_pagador = '$id_pagador'
			AND re.id_rubro = '$id_rubro'";
	$recibos = $bcdb->get_results($sql);
	
	$total = 0;
	if($recibos) :
		foreach($recibos as $k=>$recibo) :
			if($recibo['factura']) $recibo['tipo'] = 1;
			switch($recibo['tipo']) :
				case "0":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('ID', 'id_recibo', $recibo['ID'], $bcdb->internos);
				break;
				case "1":
					$recibos[$k]['nro_recibo'] = get_var_from_field ('id_externo', 'id_recibo', $recibo['ID'], $bcdb->externos);
				break;
			endswitch;
			
			$total += $recibo['monto'];
		endforeach;
	endif;
	
	$data['recibos'] = $recibos;
	$data['total'] = $total;
	
	return $data;
}



/**
* Devuelve los recibos girados en un rango de fechas
*
* @param date $fecha_inicio La fecha inicial
* @param date $fecha_fin La fecha final
* @return array
*/
function get_recibos_per($fecha_inicio, $fecha_fin) {
	global $bcdb;
	$rubros = $bcdb->get_results("SELECT * FROM $bcdb->rubros");
	
	$total = 0;
	foreach($rubros as $k => $rubro) :
		$sql = "SELECT SUM(monto) as subtotal FROM recibos WHERE id_rubro = " . $rubro['ID'];
		$sql .= " AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		
		$rubros[$k]['subtotal'] = $bcdb->get_var($sql);
		$total += $rubros[$k]['subtotal'];
		
		if($rubros[$k]['subtotal'] == 0) unset($rubros[$k]);
	endforeach;
	$data['rubros'] = $rubros;
	$data['total'] = $total;
	
	return $data;
}

/**
* Devuelve los recibos girados en un rango de fechas y de acuerdo a un rubro
*
* @param date $fecha_inicio La fecha inicial
* @param date $fecha_fin La fecha final
* @param int $id_rubro El rubro
* @return array
*/
function get_recibos_rubro($fecha_inicio, $fecha_fin, $id_rubro) {
	global $bcdb;
	
	$total = 0;
	$sql = "SELECT SUM(monto) as subtotal FROM recibos WHERE id_rubro = '$id_rubro'";
	$sql .= " AND fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	
	$total = $bcdb->get_var($sql);
	$data['total'] = $total;
	
	return $data;
}

/**
* Devuelve las personas que pagaron por un rubro en un rango de fechas
*
* @param date $fecha_inicio La fecha inicial
* @param date $fecha_fin La fecha final
* @param int $id_rubro El rubro
* @return array
*/
function get_pagadores_rubro($fecha_inicio, $fecha_fin, $id_rubro) {
	global $bcdb;
	
	$sql = "SELECT pa.documento, pa.nombres, re.fecha, re.ID
			FROM $bcdb->pagadores pa
			INNER JOIN $bcdb->recibos re
			ON pa.ID = re.id_pagador
			WHERE re.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
			AND re.id_rubro = '$id_rubro'";
	
	$data = $bcdb->get_results($sql);
	
	return $data;
}


/**
* Devuelve un recibo con todos sus datos
*
* @param int $id el ID
* @return array
*/
function get_recibo($id) {
	global $bcdb;
	$sql = "SELECT re.ID, re.monto, re.fecha, re.anulado, re.observaciones, re.id_rubro, re.factura,
			ru.tipo, ru.codigo, ru.descripcion,
			p.nombres, p.documento
			FROM $bcdb->recibos re
			INNER JOIN $bcdb->rubros ru
			ON re.id_rubro = ru.ID
			INNER JOIN $bcdb->pagadores p
			ON re.id_pagador = p.ID
			WHERE re.ID = '$id'";
	$recibo = $bcdb->get_row($sql);
	
	
	switch($recibo['tipo']) :
		case "0":
			if(!$recibo['factura']) :
				$recibo['nro_recibo'] = get_var_from_field ('ID', 'id_recibo', $recibo['ID'], $bcdb->internos);
			else:
				$recibo['nro_recibo'] = get_var_from_field ('id_externo', 'id_recibo', $recibo['ID'], $bcdb->externos);
			endif;
			if($recibo['id_rubro'] == get_option('rubro_maquinaria')) :
				$sql = "SELECT a.horas, m.nombre 
						FROM alquileres a
						INNER JOIN maquinas m
						ON a.id_maquina = m.ID
						WHERE a.id_recibo = '" . $recibo['ID'] ."'";
				$alquiler = $bcdb->get_row($sql);
				$recibo['maquina'] = $alquiler['nombre'];
				$recibo['horas'] = $alquiler['horas'];
			endif;
		break;
		case "1":
			$recibo['nro_recibo'] = get_var_from_field ('id_externo', 'id_recibo', $recibo['ID'], $bcdb->externos);
		break;
	endswitch;
	
	
	return $recibo;
}

/**
* Anula un recibo
*
* @param int $id el ID
* @return boolean
*/
function anular_recibo($id) {
	global $bcdb;
	
	// Verifica si es maquinarias
	$rubro = $bcdb->get_var("SELECT id_rubro FROM $bcdb->recibo WHERE ID = '$id'");
	
	// Si es maquinaria pone las horas a cero
	if($rubro == get_option('rubro_maquinaria')):
		$sql = "UPDATE $bcdb->alquileres
				SET horas = 0
				WHERE id_recibo = '$id'";
		$bcdb->query($sql);
	endif;
	
	// Anula el recibo
	$sql = "UPDATE $bcdb->recibos 
			SET anulado = '1',
			monto = 0
			WHERE ID = '$id'";
	return $bcdb->query($sql);
}

/**
* Formatea un número flotante a nuevos soles
*
* @param float $monto El monto
* @return float
*/
function nuevos_soles($monto) {
	return number_format($monto, 2, ".", ",");
}

/**
* Convierte minutos en el formato Hora:Minuto con el rango de 15 minutos
*
* @param int $minutos El número de minutos
* @return array
*/
function convierte_horas($minutos) {
	$resta = 0;
	$h = 0;
	$time = array();
	for($i=1; $i<=$minutos; $i++):
		if($i%30 == 0) :
			
			if($i%60==0) :
				$hora = ($i/60);
				$h = $hora;
				$resta = $hora*60;
				$time[$i] = "$hora h";
			endif;
			
			if($i%60!=0) $time[$i] = $h . " h " . ($i-$resta) . " m";
		endif;
	endfor;
	return $time;
}

/**
* Horas a minutos
*
* @param int $minutos El número de minutos
* @return string
*/
function horas_minutos($minutos) {
	$str = "";
	$horas = (int)($minutos/60);
	if($horas > 0) $str .= "$horas horas ";
	$minutos = $minutos%60;
	if($minutos > 0) $str .= "$minutos minutos";
	return $str;
}

/**
* Guarda un usuario
*
* @param int $idusuario El id del usuario
* @return boolean
*/
function save_user($idusuario, $user_values) {
	global $bcdb, $msg;

	if ( $idusuario && get_item($idusuario, $bcdb->admin) ) {
		unset($user_values['usuario']); // We don't want someone 'accidentally' update usuario
	}		
	
	$user_values['id'] = $idusuario;
	if ( ($query = insert_update_query($bcdb->admin, $user_values)) &&
		$bcdb->query($query) ) {
		if (empty($idusuario))	
			$idusuario = $bcdb->insert_id;
		return $idusuario;
	}
	return false;
}


/**
* Busca pagadores
*
* @param string $palabra El texto a buscar
* @return array
*/
function search_clientes($palabra) {
	global $bcdb;
	$sql = "SELECT * FROM $bcdb->cliente WHERE nombres LIKE '%$palabra%' 
          OR apaterno LIKE '%$palabra%' OR amaterno LIKE '%$palabra%'";
	return $bcdb->get_results($sql);
}

/**
* Es Administrador
*
* @param int $idusuario El id del usuario
* @return boolean
*/
function is_admin ($idusuario) {
	return true;
}

?>