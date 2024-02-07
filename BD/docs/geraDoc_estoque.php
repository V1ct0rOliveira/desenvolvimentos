<?php
    session_start();
    require_once "../conexao/database.php";
    require_once "../../lib/vendor/autoload.php";

    // Instanciar o PhpWord
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // Adicionar uma seção e definir configurações
    $section = $phpWord->addSection(array('borderTopSize' => 1, 'borderBottomSize' => 1, 'borderLeftSize' => 1, 'borderRightSize' => 1));

    $logo = "../../view/img/umc_logo.png";

    $section->addImage($logo, array(
        'width' => 200, 
        'height' => 100, 
        'alignment' => 'center',
        'lineHeight' => 2.0, 
    ));

    $estiloTitulo = array(
        'size' => 26, 
        'name' => 'Calibri Light',
        'lineHeight' => 1.5,
    );
    $section->addText('Relatório de Estoque', $estiloTitulo, array('alignment' => 'center'));

    $consulta = "SELECT * FROM estoque ORDER BY nome_item ASC";
    $resultado = mysqli_query($conexao, $consulta);

    $table = $section->addTable(array ('alignment' => 'center'));
    $table->addRow();

    $estiloTabela = array (
        'bold' => true, 
        'size' => 16,
    );

    $table->addCell(4000)->addText('Nome do Item', $estiloTabela, array ('alignment' => 'center'));
    $table->addCell(4000)->addText('Quantidade', $estiloTabela, array ('alignment' => 'center'));

    if ($resultado) {
        while ($row = mysqli_fetch_assoc($resultado)) {
            $itemNome = $row['nome_item'];
            $itemQuantidade = $row['quantidade'];

            $estiloTexto = array(
                'name' => 'Arial',
                'size' => 10,
            );

            $table->addRow();
            $table->addCell(4000)->addText($itemNome, $estiloTexto, ['alignment' => 'center']);
            $table->addCell(4000)->addText($itemQuantidade, $estiloTexto, ['alignment' => 'center']);
        }
    }

    // Definir o nome do arquivo para download
    $nomeDoc = 'estoque_geral.docx';

    // Enviar o documento para download
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header("Content-Disposition: attachment; filename=\"$nomeDoc\"");
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('php://output');

    exit();
?>