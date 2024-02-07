<?php 
    // Conexão com o banco de dados
    require_once "../BD/conexao/database.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Se o formulário foi enviado, obter o mês selecionado
        $mesSelecionado = $_POST['mes'];
        $sql = "SELECT * FROM registro_saida WHERE MONTH(data_saida) = $mesSelecionado";
        
        $resultado = mysqli_query($conexao, $sql);

        // Verificar se há registros para o mês selecionado
        if (mysqli_num_rows($resultado) === 0) {
            $mesSelecionadoText = date("F", strtotime("2000-$mesSelecionado-01"));
            $mensagemErro = "Não há registros para o mês de $mesSelecionadoText.";
        }
    } else {
        // Caso contrário, mostrar todos os registros
        $sql = "SELECT * FROM registro_saida";
        $resultado = mysqli_query($conexao, $sql);
    }

?>

<html>
    <head>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/form_select.css">
        <link rel="icon" type="imagex/x-icon" href="../img/.ico">
        <meta charset="utf8mb4_general_ci">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <div class="tabela">
        <h1>Lista de Retiradas do Estoque</h1>
        <form action="" class="form" method="post">
            <label for="mes">Seleciona um mês</label>
            <div class="form_area">
                <select name="mes" id="mes">
                    <option value="1">Janeiro</option>
                    <option value="2">fevereiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Desembro</option>
                </select>
            </div>
            <div class="form_footer">
                <button type="submit" class="submit">Filtrar</button>
            </div>
        </form>
        <div class="gera1">
            <form class="form_gera_saida" action="../BD/docs/geraDoc_saida.php" method="post">
                <button type="submit" class="submit">Gerar documento</button>
            </form>
        </div>
        <br>
        <?php
        if (!empty($mensagemErro)) {
            echo "<script type=\"text/javascript\">alert('$mensagemErro');</script>";
            echo "<script>history.back()</script>";
        } elseif (mysqli_num_rows($resultado) > 0) {
            ?>
            <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Destino</th>
                    <th>Data de Saída</th>
                    <th>Horário de Saída</th>
                    <th>Sáida Registrada Por</th>
                    <th>Quem Retirou</th>
                </tr>
            </thead>
            <tbody>
            <?php while($dados = mysqli_fetch_assoc($resultado)){
                    $dataHora = $dados['data_saida'];
                    $data = date('d-m-Y', strtotime($dataHora));
                    $hora = date('H:i:s', strtotime($dataHora));?>
                    <tr>
                        <td><?php echo $dados['nome_item']?></td>
                        <td><?php echo $dados['quantidade']?></td>
                        <td><?php echo $dados['destino']?></td>
                        <td><?php echo $data?></td>
                        <td><?php echo $hora?></td>
                        <td><?php echo $dados['usuario']?></td>
                        <td><?php echo $dados['quem_retirou']?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>  
</html>