<html>
    <head>
        <link rel="stylesheet" href="../css/style_form.css">
        <link rel="icon" type="imagex/x-icon" href="../img/.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <div class="form">
        <h1>Criar Usuário</h1>
        <form action="../BD/processos/processar_usuario.php" method="post">
            <div class="form_area">
                <label for="usuaio">Usuário</label>
                <input type="text" name="usuario" autocomplete="off">
                <br>
                <br>
                <label for="email">Email</label>
                <input type="email" name="email" autocomplete="off">
                <br>
                <br>
                <label for="senha">Senha</label>
                <input type="password" name="senha" autocomplete="off">
                <br>
                <br>
                <label for="nivel">Nivel de Usuário</label>
                <select name="nivel">
                    <option value="usuario">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <br>
            <div class="form_footer">
                <button type="submit" class="submit">Criar</button>
            </div>
        </form>
    </div>
</html>