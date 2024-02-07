<?php
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
    <title>Formulario de Entrada</title>
</head>
<body>
    <header class="topo">
        <img src="../img/umc.jpg" alt="logo">
    </header>
    <main class="centro">
        <div class="form">
            <h1>Formulário de Entrada</h1>
            <form action="../../BD/validar_entrada.php" method="post">
                <div class="form_area">
                    <label for="nome_item">Nome do Item</label>
                    <input type="text" name="nome_item" value="<?php echo ($dados['nome_item'])?>">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade">
                </div>
                <br>
                <div class="form_footer">
                    <button type="submit" class="submit">Registrar Entrada</button>
                    <a href="../index_admin.php?pagina=entrada">Voltar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>