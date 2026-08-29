<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

//Responsavel pelas consultas relacionadas aos serviçoes
class Service{
    private PDO $connection;
    public function __construct(PDO $connection){
        $this->connection = $connection;
    }

    public function findAll(): array{
        //retorna todo os serviçoes alocados ao funcionario
        $sql = '
            SELECT
                service.id_service,
                service.description,
                service.price,
                service.created_at,
                service.finished_at,
                CASE
                    WHEN service.finished_at IS NULL
                        THEN "Pendente"
                    ELSE "Finalizado"
                END AS status,
                user.name AS user_name
            FROM service
            INNER JOIN user
                ON user.id_user = service.user_id_user
            ORDER BY service.created_at DESC
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Calcula o valor total dos serviços do usuario
    public function getTotalByUserId(int $userId): float{
        $sql = '
            SELECT
                COALESCE(SUM(service.price), 0) AS total
            FROM service
            WHERE service.user_id_user = :user_id_user
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(
            ':user_id_user',
            $userId,
            PDO::PARAM_INT
        );
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float) $result['total'];
    }
    //cadastra um novo serviço para o usuario logado
    public function create(string $description, string $price, int $userId): bool{
        $sql = '
            INSERT INTO service(
                description,
                price,
                created_at,
                finished_at,
                commission_user,
                user_id_user
            ) VALUES (
                :description,
                :price,
                NOW(),
                NULL,
                0,
                :user_id_user
            )
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':price', $price, PDO::PARAM_STR);
        $stmt->bindValue(':user_id_user', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //Busca serviço por ID
    public function findById(int $serviceId): ?array{
        $sql = '
            SELECT
                service.id_service,
                service.description,
                service.price
            FROM service
            WHERE service.id_service = :service_id
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':service_id', $serviceId, PDO::PARAM_INT);
        $stmt->execute();

        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        return $service ?: null;
    }
    //atualiza dados do serviço
    public function update(int $serviceId, string $description, string $price): bool {
        $sql = '
            UPDATE service
            SET
                description = :description,
                price = :price
            WHERE id_service = :service_id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(
            ':description',
            $description,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':price',
            $price,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ':service_id',
            $serviceId,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }
    //exclui um serviço pelo ID
    public function delete(int $serviceId): bool{
        $sql='
            DELETE FROM service
            WHERE id_service = :service_id
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':service_id', $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}