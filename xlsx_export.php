<?php

require 'vendor/autoload.php';
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $exportData = $_SESSION['exportData'];

    $sheet->setCellValue('A1', "Datum");
    $sheet->setCellValue('B1', "Tip");
    $sheet->setCellValue('C1', "Iznos");

    $rowNum = 3;
    foreach($exportData as $row){
        $sheet->setCellValue('A'.$rowNum, $row['date_formatted']);
        $sheet->setCellValue('B'.$rowNum, $row['type_name']);
        $sheet->setCellValue('C'.$rowNum, $row['amount']);
        $rowNum++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = "data.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>