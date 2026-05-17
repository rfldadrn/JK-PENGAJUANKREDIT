<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class PdfHelper extends TCPDF
{
    private $reportTitle = '';
    private $reportPeriod = '';
    
    public function setReportTitle($title)
    {
        $this->reportTitle = $title;
    }
    
    public function setReportPeriod($period)
    {
        $this->reportPeriod = $period;
    }
    
    // Page header
    public function Header()
    {
        // Logo
        $logoPath = __DIR__ . '/../../public/assets/img/logo-bri.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 15, 10, 30, 0, 'PNG');
        }
        
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->Cell(0, 5, 'BANK BRI', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, $this->reportTitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        
        if ($this->reportPeriod) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 5, $this->reportPeriod, 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->Ln(5);
        }
        
        // Line break
        $this->Ln(5);
        $this->Line(15, $this->GetY(), $this->getPageWidth() - 15, $this->GetY());
        $this->Ln(3);
    }
    
    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        
        // Line
        $this->Line(15, $this->GetY(), $this->getPageWidth() - 15, $this->GetY());
        $this->Ln(2);
        
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Print date
        $this->SetY(-15);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d/m/Y H:i:s'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
    
    /**
     * Create a simple table
     */
    public function createTable($header, $data, $widths = null)
    {
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(41, 128, 185);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(41, 128, 185);
        $this->SetLineWidth(0.3);
        
        // Calculate widths if not provided
        if ($widths === null) {
            $totalWidth = $this->getPageWidth() - 30; // 15mm margin on each side
            $columnCount = count($header);
            $widths = array_fill(0, $columnCount, $totalWidth / $columnCount);
        }
        
        // Header
        foreach ($header as $index => $col) {
            $this->Cell($widths[$index], 7, $col, 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Data
        $this->SetFont('helvetica', '', 8);
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $fill = false;
        
        foreach ($data as $row) {
            foreach ($row as $index => $col) {
                $this->Cell($widths[$index], 6, $col, 'LR', 0, 'L', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        
        // Closing line
        foreach ($widths as $width) {
            $this->Cell($width, 0, '', 'T');
        }
        $this->Ln();
    }
    
    /**
     * Add summary section
     */
    public function addSummary($summaryData)
    {
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(0, 7, 'RINGKASAN', 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 9);
        foreach ($summaryData as $label => $value) {
            $this->Cell(60, 6, $label, 0, 0, 'L');
            $this->Cell(0, 6, ': ' . $value, 0, 1, 'L');
        }
    }
}
