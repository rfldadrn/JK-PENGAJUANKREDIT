<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelHelper
{
    private $spreadsheet;
    private $sheet;
    private $currentRow = 1;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }
    
    /**
     * Set report header
     */
    public function setHeader($title, $period = '')
    {
        // Company name
        $this->sheet->setCellValue('A1', 'BANK BRI');
        $this->sheet->mergeCells('A1:' . $this->getColumnLetter(10) . '1');
        $this->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $this->sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Report title
        $this->sheet->setCellValue('A2', $title);
        $this->sheet->mergeCells('A2:' . $this->getColumnLetter(10) . '2');
        $this->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $this->sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Period
        if ($period) {
            $this->sheet->setCellValue('A3', $period);
            $this->sheet->mergeCells('A3:' . $this->getColumnLetter(10) . '3');
            $this->sheet->getStyle('A3')->getFont()->setSize(10);
            $this->sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $this->currentRow = 5;
        } else {
            $this->currentRow = 4;
        }
    }
    
    /**
     * Create table with header and data
     */
    public function createTable($headers, $data)
    {
        $columnCount = count($headers);
        
        // Headers
        $col = 0;
        foreach ($headers as $header) {
            $cellCoordinate = $this->getColumnLetter($col) . $this->currentRow;
            $this->sheet->setCellValue($cellCoordinate, $header);
            $col++;
        }
        
        // Style headers
        $headerRange = 'A' . $this->currentRow . ':' . $this->getColumnLetter($columnCount - 1) . $this->currentRow;
        $this->sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2980B9']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
        
        $this->currentRow++;
        
        // Data rows
        foreach ($data as $row) {
            $col = 0;
            foreach ($row as $cell) {
                $cellCoordinate = $this->getColumnLetter($col) . $this->currentRow;
                $this->sheet->setCellValue($cellCoordinate, $cell);
                $col++;
            }
            $this->currentRow++;
        }
        
        // Style data rows
        if (count($data) > 0) {
            $dataRange = 'A' . ($this->currentRow - count($data)) . ':' . $this->getColumnLetter($columnCount - 1) . ($this->currentRow - 1);
            $this->sheet->getStyle($dataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ]);
        }
        
        // Auto-size columns
        for ($i = 0; $i < $columnCount; $i++) {
            $this->sheet->getColumnDimension($this->getColumnLetter($i))->setAutoSize(true);
        }
        
        $this->currentRow += 2; // Add spacing
    }
    
    /**
     * Add summary section
     */
    public function addSummary($summaryData)
    {
        $this->sheet->setCellValue('A' . $this->currentRow, 'RINGKASAN');
        $this->sheet->getStyle('A' . $this->currentRow)->getFont()->setBold(true);
        $this->currentRow++;
        
        foreach ($summaryData as $label => $value) {
            $this->sheet->setCellValue('A' . $this->currentRow, $label);
            $this->sheet->setCellValue('B' . $this->currentRow, $value);
            $this->currentRow++;
        }
    }
    
    /**
     * Save and download file
     */
    public function download($filename)
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Get column letter from index
     */
    private function getColumnLetter($index)
    {
        return chr(65 + $index); // A=65 in ASCII
    }
}
