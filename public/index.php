<?php

declare(strict_types=1);

use Core\Database;

//carrega classe de configuração do banco de dados
require_once __DIR__ . '/../core/Database.php';

//carrega o arquivo de configuração do banco de dados
$config = require __DIR__ . '/../config/database.php';

//conecta ao banco de dados
try{
    $db = new Database($config);
    $db->getConnection();
    echo 'Conectado ao banco de dados';
} catch (\PDOException $exception) {
    echo 'Erro ao conectar ao banco de dados: ' . $exception->getMessage();
}
