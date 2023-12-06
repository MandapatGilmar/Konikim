<?php
require_once 'db_config.php'; // Database configuration
require_once 'fpdf/fpdf.php'; // Include the FPDF library

// Extend the FPDF class to create custom header and footer
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Supplier List Report', 0, 1, 'C');

        // Header
        $this->SetFont('Arial', 'B', 12);
        $header = array('ID' => 10, 'Company' => 60, 'Staff' => 40, 'Contact' => 30, 'Email' => 65, 'Address' => 65);
        foreach ($header as $col => $width) {
            $this->Cell($width, 10, $col, 1);
        }
        $this->Ln();
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create a new PDF instance and set it to landscape orientation
$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();

// Fetch data from the database
$result = "SELECT * FROM supplier_list ORDER BY id";
$sql = $conn->query($result);

// Loop through the data and add it to the PDF
while ($row = $sql->fetch_object()) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(10, 10, $row->id, 1);
    $pdf->Cell(60, 10, $row->companyname, 1);
    $pdf->Cell(40, 10, $row->staffname, 1);
    $pdf->Cell(30, 10, $row->contactnumber, 1);
    $pdf->Cell(65, 10, $row->email, 1);
    $pdf->Cell(65, 10, $row->address, 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output();
