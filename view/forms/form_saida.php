<?php
    session_start();

    require_once "../../BD/conexao/database.php";

    $id = $_GET["id"];

    $sql = "SELECT * FROM estoque WHERE id = $id";
    
    $resultado = mysqli_query($conexao, $sql);
    
    $dados = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/entrada_saida.css">
    <link rel="icon" type="imagex/x-icon" href="../img/.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de saída</title>
</head>
<body>
    <header class="topo">
        <img src="../img/umc.jpg" alt="logo">
    </header>
    <main class="centro">
        <div class="form">
            <h1>Formulário de Saída</h1>
            <form action="../../BD/processos/processar_saida.php" method="post">
                <div class="form_area">
                    <label for="nome_item">Nome do Item</label>
                    <input type="text" name="nome_item" value="<?php echo ($dados['nome_item'])?>">
                    <br><br>
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade">
                    <br><br>
                    <label for="destino">Destino</label><br>
                    <input type="text" name="destino">
                    <br><br>
                    <label for="quem retirou">Quem retirou</label>
                    <input type="text" name="quem_retirou">
                </div>
                <div class="form_footer">
                    <button type="submit" class="submit">Registrar Saída</button>
                    <?php
                        if ($_SESSION['nivel'] == 'admin') {
                            echo "<a href='../index_admin.php?pagina=saida'>Voltar</a>";
                        } else {
                            echo "<a href='../index.php?pagina=saida'>Voltar</a>";
                        }
                    ?>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
