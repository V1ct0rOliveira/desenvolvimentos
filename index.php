<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="icon" type="imagex/x-icon" href="./view/img/.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <header class="topo">
        <img src="./view/img/umc.jpg" alt="Logo">
    </header>
    <main class="centro">
        <div class="login">
            <form action="./BD/processos/processar_login.php" method="post" class="form">
                <h2>Login</h2>
                <div class="form_contest">
                    <div class="form_area">
                        <label for="usuario">Usuario</label>
                    </div>
                    <div class="form_area">
                        <input type="text" name="usuario" autocomplete="off">
                    </div>
                    <br>
                    <div class="form_area">
                        <label for="senha">Senha</label>
                    </div>
                    <div class="form_area">
                        <input type="password" name="senha" autocomplete="off">
                    </div>
                    <br>
                    <div class="form_footer">
                        <button type="submit" class="submit">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

git config --global user.name "Victor Oliveira"
git config --global user.email "beltramevictor13@umc.br"