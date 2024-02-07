<?php
    session_start();
    include "../config_log.php";
    include "../acoes_usuario.php";
    require_once "../conexao/database.php";

    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

    $senhaMd5 = md5($senha); // Aplica o hash MD5 à senha

    $PDO = db_connect();

    $sql = "SELECT id, usuario, nivel FROM usuarios WHERE usuario = :usuario AND senha = :senha";
    $stmt = $PDO->prepare($sql);

    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':senha', $senhaMd5, PDO::PARAM_STR); // Usa a senha com hash MD5

    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        // Se não houver correspondência no banco de dados, exibe uma mensagem de erro e redireciona de volta.
        echo "<script type=\"text/javascript\">alert('Usuario ou senha inválidos!');</script>";
        echo "<script>history.back()</script>";
        exit;
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pega o primeiro usuário encontrado (supondo que os emails são únicos na tabela).
    $usr = $users[0];

    // Armazena o nome do usuário na sessão.
    $_SESSION['logged_in'] = true;
    $_SESSION['id'] = $usr['id'];
    $_SESSION['usuario'] = $usr['usuario'];
    $_SESSION['nivel'] = $usr['nivel'];

    // Agora, vamos verificar o nível de acesso do usuário no banco de dados.
    $sql_nivel = "SELECT nivel FROM usuarios WHERE id = :id";
    $stmt_nivel = $PDO->prepare($sql_nivel);
    $stmt_nivel->bindParam(':id', $usr['id'], PDO::PARAM_INT);
    $stmt_nivel->execute();

    if ($stmt_nivel->rowCount() > 0) {
        $nivel_acesso = $stmt_nivel->fetch(PDO::FETCH_ASSOC)['nivel'];

        // Redireciona com base no nível de acesso
        if ($nivel_acesso == 'admin') {
            header("Location: ../../view/index_admin.php");
        } elseif ($nivel_acesso == 'usuario') {
            header("Location: ../../view/index.php");
        }
    } else {
        
    }
    
?>
