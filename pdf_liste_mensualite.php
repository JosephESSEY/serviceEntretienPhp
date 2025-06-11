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
        $this->SetX(5);
       
        $this->SetY(45);
        $this->Cell(0, 10, "RAPPORT DE MENSUALITE", 0, 1, 'C');
        $this->Ln(10);


        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(60, 10, 'NOM', 1, 0, 'L', true);
        $this->Cell(60, 10, 'PRENOM', 1, 0, 'L', true);
        $this->Cell(40, 10, 'TOTAL COTISE', 1, 0, 'L', true);
        $this->Cell(30, 10, 'STATUT', 1, 1, 'L', true);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }

}

$anne = date("Y");
$pdf = new PDF();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);
$pst = $con->prepare("SELECT 
    m.nom AS Nom,
    m.prenom AS Prenom,
    SUM(c.montant) AS TotalCotise,
    CASE 
        WHEN SUM(c.montant) >= 10000 THEN 'Solde'
        ELSE CONCAT('Reste: ', 10000 - SUM(c.montant))
    END AS Status
FROM 
    membres m
JOIN 
    cotisation c ON m.id = c.id_membre

    WHERE anne=:anne

GROUP BY 
    m.nom, m.prenom 
 ORDER BY
  m.nom,m.prenom DESC ;");
$pst->execute([
    "anne" => $anne
]);
$values = $pst->fetchAll(PDO::FETCH_ASSOC);

if ($values) {
    foreach ($values as $value) {
        $pdf->Cell(60, 10, $value['Nom'], 1, 0, 'L', false);
        $pdf->Cell(60, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $value['Prenom']), 1, 0, 'L', false);
        $pdf->Cell(40, 10, $value['TotalCotise'], 1, 0, 'L', false);
        $pdf->Cell(30, 10, $value['Status'], 1, 1, 'L', false);

    }
} else {
    $pdf->Cell(0, 10, 'Aucun cotisation trouve.', 1, 1, 'L');
}
$pdf->Output();




?>