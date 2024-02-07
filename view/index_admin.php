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
                <a href="./forms/trocar_senha.php">trocar senha</a>
            </div>
            <div class="info">
                <a href="../BD/deslogar.php">sair</a>
            </div>
        </div>
    </header>
    <main class="centro">
        <div class="container">
            <h1>Estoque - Lab</h1>
            <br>
            <a href="?pagina=cadastrar">Cadastrar Item</a>
            <a href="?pagina=entrada">Registrar Entrada</a>
            <a href="?pagina=saida">Registrar Saída</a>
            <a href="?pagina=listar_entrada">Consultar Entrada</a>
            <a href="?pagina=listar_saida">Consultar Saída</a>
            <a href="?pagina=listarItens">Consultar estoque</a>
            <a href="?pagina=criar_usuario">Criar usuário</a>
        </div>
        <div class="conteudo" id="conteudo">
            <?php
                if (isset($_GET['pagina'])) {
                    $pagina = $_GET['pagina'];
                    switch ($pagina) {
                        case 'cadastrar':
                            include('./forms/cadastrar.php');
                            break;
                        case 'entrada':
                            include('./list/entrada.php');
                            break;
                        case 'saida':
                            include('./list/saida.php');
                            break;
                        case 'listar_entrada':
                            include('./list/listar_entrada.php');
                            break;
                        case 'listar_saida':
                            include('./list/listar_saida.php');
                            break;
                        case 'listarItens':
                            include('./list/listarItens.php');
                            break;
                        case 'criar_usuario':
                            include('./forms/criar_usuario.php');
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
