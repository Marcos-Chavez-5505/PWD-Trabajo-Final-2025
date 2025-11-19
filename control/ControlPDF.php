<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/PWD-TP-FINAL/configuracion.php";
require $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/util/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PWD-TP-FINAL/util/vendor/setasign/fpdf/fpdf.php';
// use FPDF;

class ControlPDF {

    /**
     * Recibe el ID de la compra y el estado
     * Genera un PDF y lo guarda en una carpeta del proyecto
     * 
     * @param int $idCompra
     * @param string $idEstadoTipo
     * @return string //PDF
     */
public function generarPdf($idCompra, $estado){
    $pdf = new FPDF();
    $pdf->AddPage();

    // === TÍTULO PRINCIPAL ===
    $pdf->SetFont('Arial', 'B', 28);
    $pdf->SetTextColor(255, 128, 0); // Naranja
    $pdf->SetXY(10, 10); // Parte superior izquierda
    $pdf->Cell(0, 15, 'El Guapo Gamer', 0, 1, 'L');

    // === SUBTÍTULO ===
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetTextColor(0, 0, 0); // Negro
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Estado de compra', 0, 1, 'L');

    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(8);

    // === CUERPO DEL PDF ===
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 8, 'ID Compra: ' . (int)$idCompra, 0, 1);
    $pdf->Cell(0, 8, 'Estado actual: ' . (string)$estado, 0, 1);

    $pdf->Ln(5);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(10);

    // === SALUDO FINAL EN ITÁLICA + FECHA ===
    $pdf->SetFont('Arial', 'I', 12);

    $saludo = "Saludos del equipo de El Guapo Gamer,";
    $fecha = date('d/m/Y');

    $pdf->Cell(0, 8, $saludo . '  ' . $fecha, 0, 1);

    // === GUARDAR ARCHIVO ===
    $file = ROOT . 'email/factura_' . $idCompra . '.pdf';
    $pdf->Output('F', $file);

    return $file;
}
}
