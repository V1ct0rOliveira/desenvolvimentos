<?php 
    // Conexão com o banco de dados
    require_once "../BD/conexao/database.php";

    $sql = "SELECT * FROM estoque ORDER BY nome_item ASC";

    $resultado = mysqli_query($conexao, $sql);
?>

<html>
    <head>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" type="imagex/x-icon" href="../img/.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <div class="tabela">
        <h1>Lista de Itens no Estoque</h1>
        <div class="geraDocs">
            <div class="gera2">
                <form class="form_gera" action="../BD/docs/geraDoc_estoque.php" method="post">
                    <button type="submit" class="submit">Gerar documento</button>
                </form>
            </div>
            <div class="gera2">
                <form class="form_gera" action="../BD/docs/geraExcel_estoque.php" method="post">
                    <button type="submit" class="submit">Gerar Excel</button>
                </form>
            </div>
        </div>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                <?php while($dados = mysqli_fetch_assoc($resultado)){?>
                    <tr>
                        <td><?php echo $dados['nome_item']?></td>
                        <td><?php echo $dados['quantidade']?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</html>