<?php
/**
 * Imprime recibos individuales
 */
require_once('home.php');
require_once('redirect.php');
require(INCLUDE_PATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
	
	function LoadData($id) {
		global $bcdb;
		$data = get_recibo($id);
		return $data;
	}
  
  function Header() {
		$this->SetFont('Times', '' ,12);
		$this->Cell(0, 4, 'MUNICIPALIDAD DISTRITAL DE PUCYURA', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Arial', '' ,12);
		$this->Cell(0, 10, 'PROV. ANTA - DPTO. CUSCO', 0, 0, 'C');
		$this->Ln(10);
	}	

	//Una tabla más completa
	function recibo($header, $data) {
		//krumo($data);
		$hl = 7; // Espacio a la izquierda
    
		$this->Rect(15, 30, 190, 79);
		$this->Ln(7);
    
    $this->SetDrawColor(155, 155, 155);
		
		$this->SetFont('Times', 'B', 14);
		$this->Cell(20, 8, utf8_decode('N° ') . $data['id'], 0, 0, 'R');

		$this->SetFont('Arial', '' ,14);
		$this->Cell(160, 8, strftime('Fecha: %d/%m/%Y', time()), 0, 0, 'R');
		$this->Ln(12);
    
    $this->Line(20, 40, 200, 40);
    
		$this->Cell($hl);
		$this->SetFont('Arial', '' ,16);
		$this->Cell(85, 7, "COMPROBANTE DE ALQUILER");
		$this->SetFont('Arial', '' ,11);
    $this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(25, 7, "NOMBRES: ", 0 ,0);
		$this->Cell(160, 7, utf8_decode(sprintf("%s %s %s", $data['nombres'], $data['apaterno'], $data['amaterno'])), 1);
		$this->Ln(9);
    
    $this->Cell($hl);
		$this->Cell(52, 7, "SE ALQUILA LA MAQUINA: ", 0 ,0);
		$this->Cell(70, 7, utf8_decode($data['maquina']), 1);
    $this->Cell(13, 7, "POR: ", 0 ,0);
		$this->Cell(50, 7, horas_minutos($data['minutos']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(33, 7, "EN EL SECTOR: ", 0 ,0);
		$this->Cell(80, 7, $data['lugar'], 1);
    $this->Cell(16, 7, "EL DIA: ", 0 ,0);
		$this->Cell(56, 7, strftime('%d/%m/%Y', strtotime($data['fecha'])), 1);
		$this->Ln(9);
    
    $this->Cell($hl);
		$this->Cell(36, 7, "RECIBO DE CAJA: ", 0 ,0);
		$this->Cell(12, 7, $data['recibo'], 1);
    $this->Cell(50, 7, "VALE DE COMBUSTIBLE: ", 0 ,0);
		$this->Cell(12, 7, $data['combustiblenro'], 1);
    $this->Cell(75, 7, "------------------------------------------------------", 0 ,0);
    $this->Ln(9);
    $this->Cell($hl);
		$this->Cell(38, 7, "OBSERVACIONES: ", 0 ,0);
		$this->Cell(147, 7, utf8_decode($data['observaciones']), 1);
		$this->Ln(10);
		
		/*$this->Cell($hl);
		$this->Cell(28, 7, "CONCEPTO: ", 0 ,0);
		$this->Cell(157, 7, utf8_decode($data['descripcion']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(28, 7, "ANEXO: ", 0 ,0);
		$this->Cell(157, 7, utf8_decode($data['observaciones']), 1);
		$this->Ln(9);
		
		$this->Cell($hl);
		$this->Cell(28, 7, "SON: ", 0 ,0);
		$this->Cell(157, 7, convertir($data['monto']), 1);
		$this->Ln(9);*/
		
		$cod_hora = strftime("%H:%M:%S", time()-3600) . "(". $_SESSION['loginuser']['id'].")";
		
		$this->Cell($hl);
		$this->SetFont('', '', 10);
		$this->SetFillColor(225,225,225);
	  $this->Cell(10, 7, "", 0, 0, 0, true);
		$this->Cell(50, 8, strftime('%Y%m%d', strtotime($data['fecha'])). " " . $data['id'] . " - MA", 0, 0, 'C');
		$this->Cell(20, 7, "", 0, 0, 0, true);
		$this->Cell(30, 8, $cod_hora, 0, 0, 'C');
		$this->Cell(20, 7, "", 0, 0, 0, true);
		//$this->Cell(30, 8, nuevos_soles($data['monto']), 0, 0, 'C');
		$this->Cell(25, 7, "", 0, 0, 0, true);
	}
}

$pdf = new PDF();
//Títulos de las columnas
$header = array(utf8_decode('Código'), utf8_decode('Descripción'), 'Monto');
//Carga de datos
$data = $pdf->LoadData($_GET['ID']);
$pdf->AddPage();
$pdf->Recibo($header, $data);
$pdf->Output();
?>