<?php
require './traitement/connect.php';
require './fpdf/fpdf.php';

class PDF extends FPDF
{

    public function Header()
    {
        $this->SetMargins(10, 10);
        $this->SetFont('Arial', 'B', 14);
        $this->Image('./assets/img/images.png', 10, 10, 30, 30);
        $this->SetY(50);
        $this->Cell(0, 10, "LISTE DES MEMBRES DE L'UNITE TECHNIQUE", 0, 1, 'C');
        $this->Ln(10);


        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(50, 10, 'NOM', 1, 0, 'L', true);
        $this->Cell(50, 10, 'PRENOM', 1, 0, 'L', true);
        $this->Cell(50, 10, 'DATE DE NAISSANCE', 1, 0, 'L', true);
        $this->Cell(30, 10, 'CONTACT', 1, 1, 'L', true);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);


$sql = "SELECT * FROM membres ORDER BY id DESC";
$result = $con->query($sql);

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $row['nom']), 1, 0, 'L');
        $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $row['prenom']), 1, 0, 'L');
        $pdf->Cell(50, 10, date_format(date_create($row['datenaiss']), "d-m-Y"), 1, 0, 'L');
        $pdf->Cell(30, 10, $row['telephone'], 1, 1, 'L');
    }
} else {
    $pdf->Cell(0, 10, 'Aucun membre trouve.', 1, 1, 'L');
}

$pdf->Output();
?>