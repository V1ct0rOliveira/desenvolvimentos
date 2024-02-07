
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
        <h1>Lista para Entrada de Itens</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Adicionar Item</th>
                </tr>
            </thead>
            <tbody>
                <?php while($dados = mysqli_fetch_assoc($resultado)){?>
                    <tr>
                        <td><?php echo $dados['nome_item']?></td>
                        <td><?php echo $dados['quantidade']?></td>
                        <td><a href="./forms/form_entrada.php?id=<?php echo $dados['id']?>">Registrar entrada</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</html>