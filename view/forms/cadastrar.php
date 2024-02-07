<html>
    <head>
        <link rel="stylesheet" href="../css/style_form.css">
        <link rel="icon" type="imagex/x-icon" href="../img/.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <div class="form">
        <h1>Cadastrar item no estoque</h1>
        <form action="../BD/processos/processar_cadastro.php" method="post">
            <div class="form_area">
                <label for="nome_item">Nome do Item</label>
                <input type="text" name="nome_item" autocomplete="off">
            </div>
            <br>
            <div class="form_area">
                <label for="quantidade">Quantidade</label>
                <input type="number" name="quantidade" min="0" step="1" required autocomplete="off">
            </div>
            <br>
            <div class="form_area">
                <label for="insuficiencia">Número de insuficiência</label>
                <input type="number" name="insuficiencia" min="0" step="1" required autocomplete="off">
            </div>
            <div class="form_footer">
                <button type="submit" class="submit">cadastrar</button>
            </div>
        </form>
    </div>
</html>