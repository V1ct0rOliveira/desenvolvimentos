<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set('America/Sao_Paulo');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome_item"];
        $quantidade = $_POST["quantidade"];
        $user = $_SESSION["usuario"];
        
        // Verifique se o usuário está autenticado
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo "Você não está autenticado. Faça login primeiro.";
            exit;
        }
        
        // Conexão com o banco de dados
        require_once "./conexao/database.php";
        require_once "../lib/vendor/autoload.php";
        include "acoes_usuario.php";
        $PDO = db_connect();

        // Verifique se os campos não estão vazios
        if (empty($nome) || empty($quantidade)) {
            echo "<script type=\"text/javascript\">alert('Por favor, preencha todos os campos do formulário.');</script>";
            echo "<script>history.back()</script>";
        exit;
            exit;
        }

        // Insira os dados na tabela "entradas_pendentes"
        $insercaoPendente = "INSERT INTO entradas_pendentes (nome_item, quantidade, usuario) VALUES (:nome, :quantidade, :usuario)";
        $stmtPendente = $PDO->prepare($insercaoPendente);
        $stmtPendente->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmtPendente->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmtPendente->bindParam(':usuario', $user, PDO::PARAM_STR);
        $stmtPendente->execute();

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
            }
            
            table > tbody > tr > td > a {
                text-decoration: none;
                color: black;
                font-size: 15px;
                font-family: Verdana, Geneva, Tahoma, sans-serif;
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
        $mensagem .= "<th>Clique abaixo para confirmar</th>";
        $mensagem .= "</thead>";
        $mensagem .= "<tbody>";
        $mensagem .= "<td>" . $nome . "</td>";
        $mensagem .= "<td>" . $quantidade . "</td>";
        $mensagem .= "<td>" . date('d-m-Y') . "</td>";
        $mensagem .= "<td>" . date('H:i:s') . "</td>";
        $mensagem .= "<td>" . $user . "</td>";
        $mensagem .= "<td><a href='http://localhost/estoque/BD/processos/processar_entrada.php?entrada_id=" . $PDO->lastInsertId() . "'>Confirmar entrada</a></td>";
        $mensagem .= "</tbody>";
        $mensagem .= "</table>";
        $mensagem .= "</body>";
        $mensagem .= "</html>";

        $mail->setFrom('estoqueti@umc.br', 'Estoque Ti');
        $mail->addAddress('beltramevictor13@gmail.com', 'Victor');
        $mail->Subject = 'Confirmação de entrada no estoque';
        $mail->isHTML(true);
        $mail->Priority = 3;
        $mail->CharSet = 'utf-8';
        $mail->msgHTML( $mensagem );
        $mail->Body = $mensagem;

        if ($mail->send()) {
            $username = $_SESSION['usuario'];
            $action = "Confirmação de entrada no estoque";
            $details = "Item: $nome, Quantidade: $quantidade, Quem adicionou: $user";
                    
            echo "<script type=\"text/javascript\">alert('Entrada pré registrada e Email de confirmação enviado. Aguarde a confirmação.'); window.location.href = '../view/index_admin.php?pagina=entrada';</script>";
            echo "<script>history.back()</script>";
        } else {
            echo "Houve um erro ao enviar o email: " . $mail->ErrorInfo;
        }
    }
?>