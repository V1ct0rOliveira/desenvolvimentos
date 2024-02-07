<?php 

    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['usuario'];
        $email = $_POST['email'];
        $pass = $_POST['senha'];
        $nivel = $_POST['nivel'];

        $senhaMd5 = md5($pass); // Aplica o hash MD5 à senha
        
        // Verifique se o usuário está autenticado
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            echo "Você não está autenticado. Faça login primeiro.";
            exit;
        }

        // Verifique se os campos não estão vazios
        if (empty($user) || empty($email) || empty($senhaMd5) || empty($nivel)) {
            echo "<script type=\"text/javascript\">alert('Por favor, preencha todos os campos do formulário.');</script>";
            echo "<script>history.back()</script>";
        exit;
            exit;
        }
        
        // Conexão com o banco de dados
        require_once "../conexao/database.php";
        include "../acoes_usuario.php";
        $PDO = db_connect();
        
        // Insira os dados na tabela "entradas"
        $insercao = "INSERT INTO usuarios (usuario, email, senha, nivel) VALUES (:usuario, :email, :senha, :nivel)";
        $stmt = $PDO->prepare($insercao);
        $stmt->bindParam(':usuario', $user, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senhaMd5, PDO::PARAM_STR);
        $stmt->bindParam(':nivel', $nivel, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: ../../view/index_admin.php');
    }

?>