<?php
/**
 * Funciones varias
 */
/**
* Devuelve los alquileres girados en cierta fecha
*
* @param date $fecha La fecha
* @return array
*/
function get_recibos_dia($fecha) {
	global $bcdb;
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                WHERE a.creado LIKE '%s%%'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $fecha);
  $recibos = $bcdb->get_results($sql);
	
	$data = $recibos;	
	return $data;
}

/**
 * Trae alquileres hechos por un cliente.
 * @param int $idcliente El cliente
 * @return data;
 */
function get_alquileres_cliente($idcliente) {
	global $bcdb, $bcrs, $pager;
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                WHERE a.idcliente ='%s'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $idcliente);
  $recibos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	
	$data = $recibos;	
	return $data;
}

/**
 * Muestra alquileres en un cierto lugar para cierta fecha.
 * @param type $fechai
 * @param type $fechaf
 * @param type $idlugar
 * @return type 
 */
function get_disponible($fechai, $fechaf, $idlugar) {
	global $bcdb, $bcrs, $pager;
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                WHERE a.idlugar ='%s' 
                AND fecha BETWEEN '%s' AND '%s'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $idlugar, $fechai, $fechaf);
  $recibos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	
	$data = $recibos;	
	return $data;
}

/**
 * Muestra alquileres en un cierto lugar para cierta fecha.
 * @param string $fechai
 * @param string $fechaf
 * @param int $idoperador
 * @return type 
 */
function get_horas_trabajadas($fechai, $fechaf, $idoperador) {
	global $bcdb, $bcrs, $pager;
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                INNER JOIN %s o
                ON m.idoperador = o.id
                WHERE o.id ='%s' 
                AND fecha BETWEEN '%s' AND '%s'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $bcdb->operador, $idoperador, $fechai, $fechaf);
  $recibos = ($pager) ? $bcrs->get_results($sql) : $bcdb->get_results($sql);
	
  $data = $recibos;	
	return $data;
}

/**
 * Trae el gasto de combustible en un rango de fechas.
 * @param string $fechai
 * @param string $fechaf
 * @param string $maquina
 * @return string 
 */
function get_combustible_fechas($fechai, $fechaf, $maquina = "") {
  global $bcdb;
  $sql = sprintf("SELECT SUM(combustiblecan) as total 
        FROM %s
        WHERE anulado = '0'
        AND creado 
        BETWEEN '%s' AND '%s'", 
        $bcdb->alquiler, $fechai, $fechaf);
  
  if (!empty($maquina)) {
    $sql .= sprintf(" AND idmaquina = '%s'", $maquina);
  }
  
  return $bcdb->get_var($sql);
}

function get_ingreso_fechas($fechai, $fechaf, $maquina = "") {
  global $bcdb;
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                WHERE a.fecha BETWEEN '%s' AND '%s'", 
        $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $fechai, $fechaf);
  
  if (!empty($maquina)) {
    $sql .= sprintf(" AND a.idmaquina = '%s'", $maquina);
  }
  return $bcdb->get_results($sql);
}

/**
* Devuelve los alquileres girados en cierta fecha.
*
* @param date $fecha La fecha,
* @param int $maquina El ID de la máquina.
* @return array
*/
function get_reservas_dia($fecha, $maquina = "") {
  global $bcdb;
  $fecha = strftime("%Y-%m-%d", strtotime($fecha));
  $sql = sprintf("SELECT a.*, c.nombres, c.apaterno, c.amaterno, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                WHERE a.fecha LIKE '%s%%'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $fecha);
  
  if (!empty($maquina)) {
    $sql .= sprintf(" AND m.id = '%s'", $maquina);
  }
	$recibos = $bcdb->get_results($sql);
	$data = $recibos;
	
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
  $sql = sprintf("SELECT a.*, c.nombres, c.nombres, c.apaterno, c.amaterno, o.nombres as operador, l.nombre as lugar, m.descripcion as maquina
                FROM %s a
                INNER JOIN %s c
                ON a.idcliente = c.id
                INNER JOIN %s l
                ON a.idlugar = l.id
                INNER JOIN %s m
                ON a.idmaquina = m.id
                INNER JOIN %s o
                ON m.idoperador = o.id
                WHERE a.id = '%s'",
                $bcdb->alquiler, $bcdb->cliente, $bcdb->lugar, $bcdb->maquina, $bcdb->operador, $id);
	$recibo = $bcdb->get_row($sql);
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
	
	// Anula el recibo
	$sql = "UPDATE $bcdb->alquiler 
			SET anulado = '1',
			monto = 0
			WHERE id = '$id'";
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
* Convierte minutos a una cadena de horas y minutos.
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
* Muestra el uso de una máquina
*
* @param int $id_maquina ID
* @return array
*/
function get_alquileres($id_maquina, $fecha_inicio, $fecha_fin) {
	global $bcdb;
	$sql = "SELECT a.id as id_alquiler, c.nombres, c.apaterno, c.amaterno, a.minutos, a.fecha, a.anulado, a.combustiblecan
			FROM $bcdb->alquiler a
			INNER JOIN $bcdb->maquina m
			ON a.idmaquina = m.id
			INNER JOIN $bcdb->cliente c
			ON a.idcliente = c.id
			WHERE a.idmaquina = '$id_maquina'
			AND a.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
			
	$alq = $bcdb->get_results($sql);
	
	$total = 0;
  $combustible = 0;
	$data = array();
	
	if($alq) :
		foreach($alq as $k => $v) :
			$total += $v['minutos'];
      $combustible += $v['combustiblecan'];
		endforeach;
	endif;
	
	$data['alquileres'] = $alq;
	$data['total'] = $total;
	$data['totalcombustible'] = $combustible;
	return $data;
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