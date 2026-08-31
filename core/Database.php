<?php

declare(strict_types=1);

namespace Core;
use PDO;

class Database
{
    private PDO $connection;

    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config["host"]};dbname={$config["database"]};charset={$config["charset"]}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->connection = new PDO(
            $dsn,
            $config["username"],
            $config["password"],
            $options,
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
