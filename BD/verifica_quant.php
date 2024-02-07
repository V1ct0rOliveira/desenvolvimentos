<?php

    require_once "database.php";

    $sql = "SELECT * FROM estoque";

    $result = mysqli_query($conexao,$sql);

    while ($row = $result->fetch_assoc()) {
        $item_id = $row['id'];
        $nome = $row['nome_item'];
        $quantidade = $row['quantidade'];
        $nivel_critico = $row['nivel_critico'];

        if ($quantidade <= $nivel_critico) {

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'estoqueti@umc.br';
            $mail->Password = 'nH61$J3f';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mensagem = "<h2>Alerta do Estoque para o item - " . $nome . "</h2>";
            $mensagem .= "O item" . "<strong>" . $nome . "</strong>" . "atingiu o nível crítico de estoque (" . "<strong>" . $quantidade . "</strong>" . "unidades). Por favor, faça a reposição.";

            $mail->setFrom('estoqueti@umc.br', 'Estoque Ti');
            $mail->addAddress('victorrezende@umc.br', 'Victor');
            $mail->Subject = 'Saida de Item do Estoque';
            $mail->isHTML(true);
            $mail->Priority = 3;
            $mail->CharSet = 'utf-8';
            $mail->msgHTML( $mensagem );
            $mail->Body = $mensagem;

            if ($mail->send()) {
                $username = $_SESSION['usuario'];
                $action = "Saida de item do estoque";
                $details = "Item: $nome, Quantidade: $quantidade";
                
                echo "<script type=\"text/javascript\">alert('Saída registrada e Email enviado com sucesso.');</script>";
                echo "<script>history.back()</script>";
            } else {
                echo "Houve um erro ao enviar o email: " . $mail->ErrorInfo;
            }
        }
    }
?>
