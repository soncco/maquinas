<?php

/**
 * Imprime recibos individuales
 */
require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
	
	
	function LoadData() {
		global $bcdb;
		$data = get_ingreso_fechas($this->fechai, $this->fechaf, $this->maquina);
		return $data;
	}
	
	function Header() {
    $this->SetFont('Times', '' ,12);
		$this->Cell(0, 4, 'MUNICIPALIDAD DISTRITAL DE PUCYURA', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Arial', '' ,12);
		$this->Cell(0, 10, 'PROV. ANTA - DPTO. CUSCO', 0, 0, 'C');
    $this->Ln();
    $this->Cell(0, 10, 'Lista de alquileres', 0, 0, 'C');
		$this->Ln(10);
		$this->SetFont('Arial', '' ,12);
		$this->Cell(6);
		$this->Cell(1, 4, strftime('Fecha: del %d de %B del %Y al ', strtotime($this->fechai)) . strftime('%d de %B del %Y', strtotime($this->fechaf)));
		$this->Ln(10);
	}
	
	function Footer() {
		//Posición: a 1,5 cm del final
		$this->SetY(-13);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Número de página
		$this->Cell(0, 10, utf8_decode('Pág. ') . $this->PageNo() . '/{nb}' , 0, 0, 'C');
	}
	
	// Resultados
	function Informe($header, $data) {
		$hl = 6;
		//Anchuras de las columnas
		$w = array(10, 45, 25, 75, 28, 22, 21, 25);
		$h = 7; // Alto de las columnas
		//Cabeceras
		$this->SetFont('', 'B', '10');
		$this->Cell($hl);
		for($i=0; $i<count($header); $i++)
			$this->Cell($w[$i], $h, $header[$i], 1, 0, 'C');
		$this->Ln();
		//Datos
		$this->SetFont('', '', '10');
		if($data) :
      $total = 0;
			foreach($data as $k =>$v) :
				$this->Cell($hl);
				$this->Cell($w[0], $h, $v['id'], 'LRB', 0, 'R');
				$this->Cell($w[1], $h, utf8_decode($v['maquina']), 'LRB');
				$this->Cell($w[2], $h, utf8_decode($v['lugar']), 'LRB');
				$this->Cell($w[3], $h, ($v['anulado']) ? "ANULADO" : utf8_decode(sprintf('%s %s %s', $v['nombres'], $v['apaterno'], $v['amaterno'])), 'LRB', 0);
				$this->Cell($w[4], $h, horas_minutos($v['minutos']),'LRB');
				$this->Cell($w[5], $h, strftime('%d/%m/%Y', strtotime($v['fecha'])) ,'LRB');
				$this->Cell($w[6], $h, ($v['recibo']) ,'LRB', 0, 'R');
				$this->Cell($w[7], $h, ($v['monto']) ,'LRB', 0, 'R');
				$this->Ln();
        $total += $v['monto'];
			endforeach;
      $this->Cell($hl);
      $this->Cell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5]+$w[6], $h, 'Total de ingresos (S/.)', 'R', 0, 'R');
      $this->Cell($w[7], $h, number_format($total, '2', '.', ','), 'LRB', 0, 'R');
		else:
			$this->Cell(array_sum($w),8, utf8_decode('No se ha registrado ningún alquiler en esta fecha'));
		endif;
	}
}

$pdf = new PDF('L');
$pdf->AliasNbPages();
//Títulos de las columnas
$header = array('Nro', utf8_decode('Máquina'), 'Lugar', 'Cliente', 'Tiempo', 'Para', 'Nro Recibo', 'Monto');
//Carga de datos
$fechai = $_GET['fechai'];
$pdf->fechai = $fechai;
$fechaf = $_GET['fechaf'];
$pdf->fechaf = $fechaf;
$maquina = $_GET['maquina'];
$pdf->maquina = $maquina;
$data = $pdf->LoadData();
$pdf->AddPage();

$pdf->Informe($header, $data);

$pdf->Output();
?>