<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/entrada_saida.css">
    <link rel="icon" type="imagex/x-icon" href="../img/.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
</head>
<body>
    <header class="topo">
        <img src="../img/umc.jpg" alt="logo">
    </header>
    <main class="centro">
        <div class="form">
            <h1>Trocar Senha</h1>
            <form method="post" action="../../BD/processos/processar_troca_senha.php">
                <div class="form_area">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" name="nova_senha" required>
                </div>
                <br>
                <div class="form_area">
                    <label for="confirmacao_senha">Confirmar Nova Senha</label>
                    <input type="password" name="confirmacao_senha" required><br>
                </div>
                <div class="form_footer">
                    <button type="submit" class="submit">Trocar Senha</button>
                    <?php
                        session_start();
                        if ($_SESSION['nivel'] == 'admin') {
                            echo "<a href='../index_admin.php'>Voltar</a>";
                        } else {
                            echo "<a href='../index.php'>Voltar</a>";
                        }
                    ?>
                </div>
            </form>
        </div>
    </main>  
</body>
</html>
