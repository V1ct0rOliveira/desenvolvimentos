<?php

    session_start();

    //arquivo de configuração do log
    $logger = require "config_log.php";

    //pega o usuario salvo na sessão
    $user = $_SESSION["usuario"];

    //registra informações sobre a visita do usuario
    $logger -> info('Usuário acessou a página', ['usuario' => $user, 'página' => $_SERVER['REQUEST_URI']]);

    //registra informações de ações do usuario 
    $logger -> info('Usuário realizou alguma ação', ['usuario' => $user, 'ação' => 'Ação específica']);

?>  