<?php
    
    session_start();
    require_once "../conexao/database.php";
    include "../acoes_usuario.php";

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../../index.php"); // Redirecione para a página de login se não estiver logado
        exit();
    }

    // Verifique se os campos do formulário foram submetidos
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nova_senha = $_POST["nova_senha"];
        $confirmar_senha = $_POST["confirmacao_senha"];

        // Verifique se as senhas coincidem
        if ($nova_senha != $confirmar_senha) {
            echo "<script type=\"text/javascript\">alert('As senhas não coincidem.');</script>";
            echo "<script>history.back()</script>";
            exit();
        }

        $usuario = $_SESSION['usuario'];

        $PDO = db_connect();
        $verificacao = "SELECT senha FROM usuarios WHERE usuario = :usuario";
        $stmt = $PDO->prepare($verificacao);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            echo "<script type=\"text/javascript\">alert('Usuário não encontrado.');</script>";
            echo "<script>history.back()</script>";
        } else {
            $senha_antiga = $row['senha'];

            $nova_senha_hash = md5($nova_senha);

            if ($senha_antiga == $nova_senha_hash) {
                echo "<script type=\"text/javascript\">alert('A nova senha deve ser diferente da senha atual.');</script>";
                echo "<script>history.back()</script>";
            } else {
                $atualizar = "UPDATE usuarios SET senha = :senha WHERE usuario = :usuario";
                $stmt = $PDO->prepare($atualizar);
                $stmt->bindParam(':senha', $nova_senha_hash, PDO::PARAM_STR);
                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->execute();

                // Redirecionar com base no nível do usuário
                if ($_SESSION['nivel'] == 'admin') {
                    header('Location: ../../view/index_admin.php');
                } elseif ($_SESSION['nivel'] == 'usuario') {
                    header('Location: ../../view/index.php');
                }
            }
        }
    }
?>
