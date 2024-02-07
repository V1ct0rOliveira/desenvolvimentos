<?php
    session_start();
    require_once "../conexao/database.php";
    require_once "../../lib/vendor/autoload.php";

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('C3', 'ESTOQUE DE PEÇAS PARA REPOSIÇÃO');
    $sheet->setCellValue('C5', 'Modelo/Descrição');
    $sheet->setCellValue('D5', 'Quantidade');
    $sheet->setCellValue('E5', 'Status');

    $consulta = "SELECT * FROM estoque ORDER BY nome_item ASC";
    $resultado = mysqli_query($conexao, $consulta);

    if ($resultado) {
        $row = 6;
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $sheet->setCellValue('C' . $row, $linha['nome_item']);
            $sheet->setCellValue('D' . $row, $linha['quantidade']);
            $status = ($linha['quantidade'] < $linha['insuficiencia']) ? 'Comprar' : 'Suficiente';
            $sheet->setCellValue("E{$row}", $status);

            $conditionalStyle = $sheet->getStyle("E{$row}");

            if ($status === 'Comprar') {
                $conditionalStyle->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $conditionalStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditionalStyle->getFill()->getStartColor()->setARGB('FFF5B7B1');
            } elseif ($status === 'Suficiente') {
                $conditionalStyle->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
                $conditionalStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $conditionalStyle->getFill()->getStartColor()->setARGB('FFC1FFC1');
                }    

            $row++;
        }

        $titleStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '151581']],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '000000']]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->getStyle('C3:E3')->applyFromArray($titleStyle);
        $sheet->getStyle('C5:E5')->applyFromArray($titleStyle); 
        $sheet->mergeCells('C3:E3');

        $dataStyle = [
            'font' => ['size' => 11],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '000000']]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->getStyle("C6:E{$row}")->applyFromArray($dataStyle);

        foreach (range('C', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    // Definir o nome do arquivo para download
    $nomeDoc = 'estoque_geral.xlsx';

    // Enviar o documento para download
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header("Content-Disposition: attachment; filename=\"$nomeDoc\"");

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
?>
