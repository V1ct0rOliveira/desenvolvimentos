<?php

    // constantes com as credenciais de acesso ao banco MySQL
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'estoque_lab');

   $conexao = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    $conexao->set_charset('utf8');

    // habilita todas as exibições de erros
    ini_set('display_errors', true);
    error_reporting(E_ALL);

    // inclui o arquivo de funçõees
    require_once 'functions.php';

?>