<?php 

    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome_item"];
        $quantidade = isset($_POST["quantidade"]) ? $_POST["quantidade"] : null;
        $insu = $_POST["insuficiencia"];
        
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo "Você não está autenticado. Faça login primeiro.";
            exit;
        }

        if (empty($nome)) {
            echo "<script type=\"text/javascript\">alert('Por favor, preencha todos os campos do formulário.');</script>";
            echo "<script>history.back()</script>";
            exit;
        }
        
        if (empty($quantidade) || !is_numeric($quantidade)) {
            $quantidade = 0;
        }
        
        // Conexão com o banco de dados
        require_once "../conexao/database.php";
        include "../acoes_usuario.php";
        $PDO = db_connect();
        
        // Insira os dados na tabela "entradas"
        $insercao = "INSERT INTO estoque (nome_item, quantidade, insuficiencia) VALUES (:nome, :quantidade, :insuficiencia)";
        $stmt = $PDO->prepare($insercao);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':insuficiencia', $insu, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: ../../view/index_admin.php');
    }

?>