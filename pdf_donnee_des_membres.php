<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
  header('Location: ./login.php');
  exit;
}

require './traitement/connect.php';
require('fpdf/fpdf.php');  

$anne = date('Y');
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT 
            m.nom, 
            m.prenom, 
            GROUP_CONCAT(mo.nom_mois ORDER BY FIELD(mo.nom_mois, 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre') SEPARATOR ', ') AS mois_arrieres, 
            SUM(c.montant) AS total_arrieres,
            COUNT(mo.id_mois) * 800 AS montant_du
          FROM 
            membres m
          CROSS JOIN 
            mois mo
          LEFT JOIN 
            cotisation c 
          ON 
            mo.id_mois = c.id_mois 
            AND m.id = c.id_membre 
            AND c.anne = :anne
          WHERE 
            c.id_cotisation IS NULL
          GROUP BY 
            m.id
          HAVING 
            SUM(c.montant) < 800 OR SUM(c.montant) IS NULL
          ORDER BY 
            m.nom ASC";

if (!empty($search)) {
  $query = "SELECT 
  m.nom, 
  m.prenom, 
   GROUP_CONCAT(mo.nom_mois ORDER BY FIELD(mo.nom_mois, 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre') SEPARATOR ', ') AS mois_arrieres, 
  SUM(c.montant) AS total_arrieres,
  COUNT(mo.id_mois) * 800 AS montant_du
FROM 
  membres m
CROSS JOIN 
  mois mo
LEFT JOIN 
  cotisation c 
ON 
  mo.id_mois = c.id_mois 
  AND m.id = c.id_membre 
  AND c.anne = :anne
WHERE 
  c.id_cotisation IS NULL
  AND (m.nom LIKE :search OR m.prenom LIKE :search)
GROUP BY 
  m.id
   HAVING 
  SUM(c.montant) < 800 OR SUM(c.montant) IS NULL
ORDER BY 
  m.nom ASC";
}

$pst = $con->prepare($query);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->bindValue(':anne', $anne, PDO::PARAM_INT);
$pst->execute();
$rs = $pst->fetchAll(PDO::FETCH_ASSOC);


$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

$pdf->Cell(0, 10, 'LISTE DES MEMBRES AVEC MENSUALITES EN RETARD', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'NOM', 1);
$pdf->Cell(100, 10, 'PRENOM', 1);
$pdf->Cell(80, 10, 'MONTANT ARRIERES', 1);
$pdf->Ln();


$pdf->SetFont('Arial', '', 12);
foreach ($rs as $res) {
  $pdf->Cell(100, 10, $res['nom'], 1);
  $pdf->Cell(100, 10, $res['prenom'], 1);
  $pdf->Cell(80, 10, isset($res['montant_du']) ? number_format($res['montant_du'], 0, ',', ' ') . " FCFA" : '0 FCFA', 1);
  $pdf->Ln();
}

$pdf->Output();
?>
