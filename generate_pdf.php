<?php
require_once 'db_config.php'; // Database configuration
require_once 'fpdf/fpdf.php'; // Include the FPDF library

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo - Adjust the path as needed
        $this->Image('img\logo.png', 10, 6, 30);
        $this->Ln(20); // Line break after the logo

        $this->SetFont('Arial', 'B', 16);
        // Set text color for the header
        $this->SetTextColor(2, 150, 76); // RGB for #02964C
        $this->Cell(0, 10, 'Supplier List Report', 0, 1, 'C');

        // Table Header
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(2, 150, 76); // Background color for header
        $this->SetTextColor(255, 255, 255); // Text color for header
        $header = array('Company' => 70, 'Staff' => 40, 'Contact' => 30, 'Email' => 65, 'Address' => 125);
        foreach ($header as $col => $width) {
            $this->Cell($width, 10, $col, 1, 0, 'C', true);
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
$pdf = new PDF('L', 'mm', array(216, 356)); // Legal size in mm

$pdf->AliasNbPages();
$pdf->AddPage();

// Fetch data from the database
$result = "SELECT * FROM supplier_list ORDER BY id";
$sql = $conn->query($result);

// Loop through the data and add it to the PDF
while ($row = $sql->fetch_object()) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(255, 255, 255); // Reset background color for rows
    $pdf->SetTextColor(0, 0, 0); // Reset text color for rows
    $pdf->Cell(70, 10, $row->companyname, 1);
    $pdf->Cell(40, 10, $row->staffname, 1);
    $pdf->Cell(30, 10, $row->contactnumber, 1);
    $pdf->Cell(65, 10, $row->email, 1);
    $pdf->Cell(125, 10, $row->address, 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output();
