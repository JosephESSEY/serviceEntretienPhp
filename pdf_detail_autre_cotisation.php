<?php

require './traitement/connect.php';
require './fpdf/fpdf.php';

class PDF extends FPDF
{
    public function Header()
    {
        $this->SetMargins(10, 10);
        $this->SetFont('Arial', '', 10);
        $this->Image('./assets/img/images.png', 10, 10, 30, 30);
        $this->SetX(60);
        $this->Ln(5);
        $this->Cell(0, 10, "TECHNICAL UNIT SOVIEPE", 0, "1", 'C');
        $this->Cell(0, 10, "GAKPOTO LOKPOKPA FACE HOTEL RECTO VERSO", 0, "1", 'C');


        $this->SetFont('Arial', 'B', 14);
        $this->SetY(110);
        $this->Cell(0, 10, "LISTE DES PARTICIPANTS A LA COTISATION", 0, 1, 'C');
        $this->Ln(10);


        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(63, 10, 'NOM', 1, 0, 'L', true);
        $this->Cell(63, 10, 'PRENOM', 1, 0, 'L', true);
        $this->Cell(63, 10, 'TOTAL COTISE', 1, 1, 'L', true);

    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }

}
$pdf = new PDF();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);
$anne = date("Y");
$id = intval(htmlspecialchars($_GET['id']));
$pdf->SetFillColor(255, 255, 255);

if (!empty($id && isset($_GET['id']) && $id > 0 && is_numeric($id))) {


    $pst = $con->prepare("SELECT nom,prenom, SUM(montant) AS TotalCotise FROM 
membres  
JOIN 
contributions_cotisations  ON membres.id = contributions_cotisations.membres_id

JOIN autres_cotisations  ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id   

WHERE autres_cotisations.id_ac=:id

GROUP BY 
nom,prenom 
 ORDER BY
  nom,prenom DESC ;");
    $pst->execute([
        "id" => htmlspecialchars(intval($_GET['id']))
    ]);
    $values = $pst->fetchAll(PDO::FETCH_ASSOC);

    if ($values) {
        foreach ($values as $value) {
            $pdf->Cell(63, 10, $value['nom'], 1, 0, 'L', false);
            $pdf->Cell(63, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value['prenom']), 1, 0, 'L', false);
            $pdf->Cell(63, 10, $value['TotalCotise'], 1, 1, 'L', false);

        }
    } else {
        $pdf->Cell(189, 10, 'Aucun cotisation trouve.', 1, 0, 'L');
    }
} else {
    $pdf->Cell(188, 10, 'Aucun cotisation trouve.', 1, 0, 'L');

}

$pst = $con->prepare("SELECT * FROM autres_cotisations WHERE id_ac=:id");
$pst->execute([
    "id" => $id
]);

$exist = $pst->fetch(PDO::FETCH_ASSOC);

if ($exist) {

    $pst = $con->prepare("SELECT SUM(montant) AS totalcotise FROM contributions_cotisations 
    JOIN autres_cotisations ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id
     WHERE id_ac=:id");
    $pst->execute([
        "id" => $id
    ]);
    $tot = $pst->fetch(PDO::FETCH_ASSOC);

    $pst = $con->prepare("SELECT SUM(montant) AS totalretire FROM retraits_evenements
    JOIN autres_cotisations ON autres_cotisations.id_ac=retraits_evenements.id_cot
     WHERE id_ac=:id");
    $pst->execute([
        "id" => $id
    ]);
    $ret = $pst->fetch(PDO::FETCH_ASSOC);
    $retrait = $ret['totalretire'];
    if ($retrait == null) {
        $retrait = 0;
    } else {
        $retrait = $ret['totalretire'];
    }

    $restant=$tot['totalcotise']-$retrait;



    $pdf->SetY(50);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(188, 10, "INFORMATION CONCERNANT LA COTISATION", 1, 1, 'C', "", "");
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(94, 10, "MOTIF DE LA COTISATION", 1, 0, 'L', "", "");
    $pdf->Cell(94, 10, $exist['motif_acotisation'], 1, 1, 'L', "", "");
    $pdf->Cell(94, 10, "MONTANT TOTAL COTISE", 1, 0, 'L', "", "");
    $pdf->Cell(94, 10, $tot['totalcotise'], 1, 1, 'L', "", "");
    $pdf->Cell(94, 10, "MONTANT RETIRE", 1, 0, 'L', "", "");
    $pdf->Cell(94, 10, $retrait, 1, 1, 'L', "", "");
    $pdf->Cell(94, 10, "RESTANT", 1, 0, 'L', "", "");
    $pdf->Cell(94, 10, $restant, 1, 100, 'L', "", "");
} else {
    $pdf->SetY(50);
    $pdf->Cell(188, 10, 'Aucun information disponible.', 1, 0, 'L');

}

$pdf->Output();


?>