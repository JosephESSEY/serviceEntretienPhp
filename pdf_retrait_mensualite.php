<?php
session_start();
require './traitement/connect.php';
require('fpdf/fpdf.php');
$anne = date('Y');
$psts = $con->prepare('SELECT SUM(montant) AS totalcotise FROM cotisation  WHERE anne=:anne');
$psts->execute([
    "anne" => $anne
]);
$tot=$psts->fetch(PDO::FETCH_ASSOC);


class PDF extends FPDF
{




    function Header()
    {

        $this->Rect(5, 5, 200, 287);

        $this->Image("./assets/img/images.png", 15, 8, 20, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->MultiCell(0, 10, "TECHNICAL UNIT  \nW.C.I Soviepe \nFace Hotel Recto-Verso", 0, 'C', '');
        $x = 5;
        $y = 70;
        $w = 100;

        $this->SetXY($x, $y);
        $this->SetFont('Arial', '', 10);

        $anne = date('Y');

        $this->SetFont('Arial', '', 10);

        $this->SetX(5);
        $this->SetMargins(5, 0);
        $this->SetY(50);
        $this->MultiCell(0, 10, "MOTIF DE COTISATION \nMensualites", 1, "C", "");
        $this->SetY(70);
        $this->Cell(0, 10, "DETAIL RETRAIT", 1, 1, "C", "", "");
        $this->SetFont('Arial', 'B', 12);


        $this->Cell(100, 10, "MOTIF RETRAIT", 1, 0, "L", "", "");
        $this->Cell(100, 10, "SOMME RETIREE", 1, 1, "L", "", "");
    }

    function Footer()
    {
        $date = date('d/m/Y');
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        $this->SetY(-20);
        $this->Cell(0, 10, 'Fait a Lome le ' . $date, 0, 1, 'C');

    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->SetXY(5, 100);
$pdf->SetFont('Arial', '', 10);
$pdf->SetY(90);




$pst = $con->prepare('SELECT * FROM retraits_mensuels WHERE anne=:anne');
$pst->execute([
    "anne" => $anne
]);

$values = $pst->fetchAll(PDO::FETCH_ASSOC);


if ($values) {

    foreach ($values as $value) {
        $pdf->Cell(100, 10, $value['description'], 1, 0, "L", "", "");
        $pdf->Cell(100, 10, $value['montant'], 1, 1, "L", "", "");
    }
} else {
    $pdf->Cell(200, 10, "Aucune information disponible", 1, 1, "L", "", "");




}


$pst = $con->prepare('SELECT SUM(montant) as montantretire FROM retraits_mensuels WHERE anne=:anne');
$pst->execute([
    "anne" => $anne
]);
$retire=$pst->fetch(PDO::FETCH_ASSOC);

$restant=$tot['totalcotise']-$retire['montantretire'];


$pdf->Ln(20);
$anne = date('Y');
$pdf->Cell(0, 10, "BUDGET", 1, 1, "C");
$pdf->Cell(100, 10, "MONTANT TOTAL COTISE:", 1, 0, "");

$pdf->Cell(100, 10, $tot['totalcotise'], 1, 1, "");

$pdf->SetX(5);

$pdf->Cell(100, 10, "MONTANT TOTAL RETIRE:", 1, 0, "");
$pdf->Cell(100, 10, $retire['montantretire'], 1, 1, "");
$pdf->SetX(5);
$pdf->Cell(100, 10, "MONTANT RESTANT:", 1, 0, "");
$pdf->Cell(100, 10, $restant, 1, 1, "");

$pdf->Output('', 'recu.pdf');
?>