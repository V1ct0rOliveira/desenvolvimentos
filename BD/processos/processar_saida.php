<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    date_default_timezone_set('America/Sao_Paulo');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome_item"];
        $quantidade = $_POST["quantidade"];
        $destino = $_POST["destino"];
        $user = $_SESSION["usuario"];
        $quem = $_POST["quem_retirou"];
        
        // Verifique se o usuário está autenticado
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo "Você não está autenticado. Faça login primeiro.";
            exit;
        }
        
        require_once "../conexao/database.php";
        require_once "../../lib/vendor/autoload.php";
        include "../acoes_usuario.php";
        $PDO = db_connect();
        
        // Verifique se os campos não estão vazios
        if (empty($nome) || empty($quantidade) || empty($destino)) {
            echo "<script type=\"text/javascript\">alert('Por favor, preencha todos os campos do formulário.');</script>";
            echo "<script>history.back()</script>";
            exit;
        }
        
        // Consulta SQL para obter a quantidade atual do item em estoque
        $consulta = "SELECT quantidade FROM estoque WHERE nome_item = :nome";
        $stmt = $PDO->prepare($consulta);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            echo "<script type=\"text/javascript\">alert('Item não encontrado no estoque.');</script>";
            echo "<script>history.back()</script>";
        } else {
            $quantidadeEmEstoque = $row['quantidade'];

            if ($quantidadeEmEstoque >= $quantidade) {
                // Registrar a saída na tabela de registro_saida
                $usuario = $_SESSION['usuario'];
                $registro = "INSERT INTO registro_saida (data_saida, nome_item, quantidade, destino, usuario, quem_retirou) VALUES (NOW(), :nome, :quantidade, :destino, :usuario, :quem_retirou)";
                $stmt = $PDO->prepare($registro);
                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                $stmt->bindParam(':destino', $destino, PDO::PARAM_STR);
                $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt->bindParam(':quem_retirou', $quem, PDO::PARAM_STR);
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
                $mensagem .= "<h2>Um Item acabou de sair do estoque</h2>";
                $mensagem .= "<table border='1'>";
                $mensagem .= "<thead>";
                $mensagem .= "<th>Nome do Item</th>";
                $mensagem .= "<th>Quantidade</th>";
                $mensagem .= "<th>Destino</th>";
                $mensagem .= "<th>Data</th>";
                $mensagem .= "<th>Hora</th>";
                $mensagem .= "<th>Quem registrou a retirada</th>";
                $mensagem .= "<th>Quem retirou</th>";
                $mensagem .= "</thead>";
                $mensagem .= "<tbody>";
                $mensagem .= "<td>" . $nome . "</td>";
                $mensagem .= "<td>" . $quantidade . "</td>";
                $mensagem .= "<td>" . $destino . "</td>";
                $mensagem .= "<td>" . date('d-m-Y') . "</td>";
                $mensagem .= "<td>" . date('H:i:s') . "</td>";
                $mensagem .= "<td>" . $user . "</td>";
                $mensagem .= "<td>" . $quem . "</td>";
                $mensagem .= "</tbody>";
                $mensagem .= "</table>";
                $mensagem .= "</body>";
                $mensagem .= "</html>";

                $mail->setFrom('estoqueti@umc.br', 'Estoque Ti');
                $mail->addAddress('beltramevictor13@gmail.com', 'Victor');
                $mail->Subject = 'Saida de Item do Estoque';
                $mail->isHTML(true);
                $mail->Priority = 3;
                $mail->CharSet = 'utf-8';
                $mail->msgHTML( $mensagem );
                $mail->Body = $mensagem;

                if ($mail->send()) {
                    $username = $_SESSION['usuario'];
                    $action = "Saida de item do estoque";
                    $details = "Item: $nome, Quantidade: $quantidade, Destino: $destino, Quem retirou: $quem";
                    
                    echo "<script type=\"text/javascript\">alert('Saída registrada e Email enviado com sucesso.');</script>";
                    echo "<script>history.back()</script>";
                } else {
                    echo "Houve um erro ao enviar o email: " . $mail->ErrorInfo;
                }

                // Atualizar apenas o campo 'quantidade' na tabela de estoque
                $atualizacao = "UPDATE estoque SET quantidade = quantidade - :quantidade WHERE nome_item = :nome";
                $stmt = $PDO->prepare($atualizacao);
                $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmt->execute();

                // Redirecionar com base no nível do usuário
                if ($_SESSION['nivel'] == 'admin') {
                    header('Location: ../../view/index_admin.php');
                } elseif ($_SESSION['nivel'] == 'usuario') {
                    header('Location: ../../view/index.php');
                }       
            } else {
                echo "Quantidade insuficiente em estoque.";
            }
        }
    }
?>