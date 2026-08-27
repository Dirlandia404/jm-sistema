<?php

declare(strict_types=1);
namespace App\Models;
use PDO;

class User{
    //conexão PDO recebida da classe Database
    private PDO $connection;

    public function __construct(PDO $connection){
        $this->connection = $connection;
    }

    //busca usuario ativo pelo email, considera apenas usuario ativo e retorna array de dados do usuario
    public function  findbyEmail(string $email): ?array{
        $sql = 'SELECT id_user, name, email, password, ativo FROM user WHERE email = :email AND ativo = 1 LIMIT 1';

        //prepara consulta
        $stmt = $this->connection->prepare($sql);
        //bind email
        $stmt->bindValue(':email', $email);
        //executa
        $stmt->execute();
        //busca o usuario 
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        //retorna array
        return $user === false ? null : $user;
    }
}