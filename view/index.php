<?php

    include "../BD/acoes_usuario.php";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/telaprincipal.css">
    <link rel="icon" type="imagex/x-icon" href="./img/.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque - Lab</title>
</head>
<body>
    <header class="topo">
        <img src="./img/umc.jpg" alt="logo">
        <div class="perfil">
            <div class="info">
                <span>
                    <?php
                        $usuario = $_SESSION['usuario'];
                        echo $usuario;
                    ?>
                </span>
            </div>
            <div class="info">
                <a href="trocar_senha.php">trocar senha</a>
            </div>
            <div class="info">
                <a href="../BD/deslogar.php">sair</a>
            </div>
        </div>
    </header>
    <main class="centro">
        <div class="container">
            <h1>Estoque</h1>
            <br>
            <a href="?pagina=saida">Registrar Saída</a>
            <a href="?pagina=listar_entrada">Consultar Entrada</a>
            <a href="?pagina=listar_saida">Consultar Saída</a>
            <a href="?pagina=listarItens">Consultar estoque</a>
        </div>
        <div class="conteudo" id="conteudo">
            <?php
                if (isset($_GET['pagina'])) {
                    $pagina = $_GET['pagina'];
                    switch ($pagina) {
                        case 'saida':
                            include('saida.php');
                            break;
                        case 'listar_entrada':
                            include('listar_entrada.php');
                            break;
                        case 'listar_saida':
                            include('listar_saida.php');
                            break;
                        case 'listarItens':
                            include('listarItens.php');
                            break;
                        default:
                            break;
                    }
                }
            ?>
        </div>
    </main>
</body>
</html>
