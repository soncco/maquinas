<?php
	require_once('../home.php');
	header("Content-type: text/javascript");
	$pagadores = get_items($bcdb->pagadores);
?>
var pagadores = [
<?php
	$str = "";
	if ($pagadores) :
		foreach($pagadores as $k => $pagador) {
			$dni = ($pagador['documento']) ? $pagador['documento'] : "S/D";
			$str .= "{id: '" . $pagador['ID'] . "', nombres: '" . addslashes($pagador['nombres']) . " - (" . $dni . ")'},";
		}
	endif;
	//$str .= "{id: '0', nombres: 'Agregar nuevo'}";
	$str = substr($str, 0, -1);
	echo $str;
?>
];