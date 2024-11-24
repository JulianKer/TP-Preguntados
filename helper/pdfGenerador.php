<?php
require('vendor/fpdf/fpdf.php');

class PdfGenerador
{
    public function generarPdf($cantidadJugadores, $cantidadPreguntas, $chart3, $chart4, $chart5)
    {
        // Creo la instancia del PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Aca le asigno el titulo que yo quiero, en este caso le pongo GRAFICOS ADMIN
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Graficos Admin', 0, 1, 'C');


        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Total de jugadores: ' . $cantidadJugadores, 0, 1);


        $pdf->Cell(0, 10, 'Total de preguntas: ' . $cantidadPreguntas, 0, 1);


        $pdf->Ln(10);


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Grafico: Porcentaje de jugadores por sexo', 0, 1);
        $this->insertarImagenDesdeBase64($pdf, $chart3);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Grafico: Cantidad de preguntas por categoria', 0, 1);
        $this->insertarImagenDesdeBase64($pdf, $chart4);


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Grafico: Cantidad de partidas por jugador', 0, 1);
        $this->insertarImagenDesdeBase64($pdf, $chart5);


        $pdf->Output();
    }

    private function insertarImagenDesdeBase64($pdf, $base64Image)
    {
        if ($base64Image) {
            $img = explode(',', $base64Image, 2)[1];
            $pic = 'data://text/plain;base64,' . $img;
            $pdf->Image($pic, null, null, 150, 0, 'PNG');
            $pdf->Ln(10);
        }
    }
}



