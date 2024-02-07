<?php
    
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set('America/Sao_Paulo');

    if(isset($_GET['entrada_id'])){

        $entrada_id = $_GET['entrada_id'];

        require_once "../conexao/database.php";
        require_once "../../lib/vendor/autoload.php";
        include "../acoes_usuario.php";
        $PDO = db_connect();
        
        $sql = "SELECT * FROM entradas_pendentes WHERE id = :entrada_id";
        $stmt = $PDO->prepare($sql);
        $stmt->bindParam(':entrada_id', $entrada_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $entrada = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($entrada){
            $nome_item = $entrada['nome_item'];
            $quantidade = $entrada['quantidade'];
            $usuario = $entrada['usuario'];
            $data_hora = $entrada['data_entrada'];
            $data = date('d-m-Y', strtotime($data_hora));
            $hora = date('H:i:s', strtotime($data_hora));

            // Inseri a quantidade na tabela "registros_entrada"
            $insercao = "INSERT INTO registros_entrada (nome_item, quantidade, usuario, data_entrada) VALUES (:nome, :quantidade, :usuario, :data_entrada)";
            $stmt = $PDO->prepare($insercao);
            $stmt->bindParam(':nome', $nome_item, PDO::PARAM_STR);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmt->bindParam(':data_entrada', $data_hora, PDO::PARAM_STR);
            $stmt->execute();

            // Atualize a tabela "estoque" com a quantidade atualizada de itens
            $sql = "UPDATE estoque SET quantidade = quantidade + :quantidade WHERE nome_item = :nome_item";
            $stmt = $PDO->prepare($sql);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(':nome_item', $nome_item, PDO::PARAM_STR);
            $stmt->execute();

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'estoqueti@umc.br';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mensagem = "<html>";
            $mensagem .= "<style>";
            $mensagem .= "table {
                padding: 10px;
                border-radius: 10px;
                width: 100%;
                margin: 0 auto;
            }
                
            table th,
            table td {
                width: auto;
                height: auto;
                font-family: Arial, sans-serif;
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
                border-radius: 10px;
            }
                
            table th {
                background-color: #f2f2f2;
            }
                
            table tr:nth-child(even) {
                background-color: #f2f2f2;
            }
                
            table th {
                background-color: rgb(21, 21, 129);
                color: white;
            }
                
            table tr:hover {
                background-color: #e0e0e0;
            }";
            $mensagem .= "</style>";
            $mensagem .= "<body>";
            $mensagem .= "<h2>Um Item acabou de ser adicionado ao estoque</h2>";
            $mensagem .= "<table border='1'>";
            $mensagem .= "<thead>";
            $mensagem .= "<th>Nome do Item</th>";
            $mensagem .= "<th>Quantidade</th>";
            $mensagem .= "<th>Data</th>";
            $mensagem .= "<th>Hora</th>";
            $mensagem .= "<th>Quem Adicionou</th>";
            $mensagem .= "</thead>";
            $mensagem .= "<tbody>";
            $mensagem .= "<td>" . $nome . "</td>";
            $mensagem .= "<td>" . $quantidade . "</td>";
            $mensagem .= "<td>" . date('d-m-Y') . "</td>";
            $mensagem .= "<td>" . date('H:i:s') . "</td>";
            $mensagem .= "<td>" . $usuario . "</td>";
            $mensagem .= "</tbody>";
            $mensagem .= "</table>";
            $mensagem .= "</body>";
            $mensagem .= "</html>";

            $mail->setFrom('estoqueti@umc.br', 'Estoque Ti');
            $mail->addAddress('beltramevictor13@gmail.com', 'Victor');
            $mail->Subject = 'Entrada de Item no Estoque';
            $mail->isHTML(true);
            $mail->Priority = 3;
            $mail->CharSet = 'utf-8';
            $mail->msgHTML( $mensagem );
            $mail->Body = $mensagem;

            if ($mail->send()) {
                $username = $_SESSION['usuario'];
                $action = "Entrada de item no estoque";
                $details = "Item: $nome, Quantidade: $quantidade, Quem adicionou: $usuario";
            } else {
                echo "Houve um erro ao enviar o email: " . $mail->ErrorInfo;
            }

            // Exclui a entrada pendente da tabela "entradas_pendentes"
            $exclusao = "DELETE FROM entradas_pendentes WHERE id = :entrada_id";
            $stmt = $PDO->prepare($exclusao);
            $stmt->bindParam(':entrada_id', $entrada_id, PDO::PARAM_INT);
            $stmt->execute();
            
            echo "<script type=\"text/javascript\">alert('Entrada registrada com sucesso.'); window.location.href = '../../view/index_admin.php?pagina=entrada';</script>";
            echo "<script>history.back()</script>";

        } else {
            echo "A entrada pendente não foi encontrada ou já foi confirmada anteriormente.";
        }
    } else {
        echo "O parâmetro de entrada não foi especificado na URL.";
    }
?>
