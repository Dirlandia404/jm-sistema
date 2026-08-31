<?php

declare(strict_types=1);
namespace App\Models;
use PDO;

class User
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //Busca usuario ativo pelo email, considera apenas usuario ativo e retorna array de dados do usuario
    public function findbyEmail(string $email): ?array
    {
        $sql =
            "SELECT id_user, name, email, password, ativo FROM user WHERE email = :email AND ativo = 1 LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user === false ? null : $user;
    }

    //Verifica se o email ja esta cadastrado
    public function emailExists(string $email): bool
    {
        $sql = '
        SELECT COUNT(*)
        FROM user
        WHERE email = :email
    ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

    //Cadastra um novo usuario ativo
    public function create(string $name, string $email, string $password): bool
    {
        $sql = '
        INSERT INTO user (
            name,
            email,
            password,
            ativo
        ) VALUES (
            :name,
            :email,
            :password,
            1
        )
    ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
