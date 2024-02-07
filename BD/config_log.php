<?php

    require_once __DIR__ . "/../lib/vendor/autoload.php";

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    $logger = new Logger('app');
    $logger -> pushHandler(new StreamHandler('../lib/logs/arquivo.log', Logger::INFO));

    return $logger;

?>