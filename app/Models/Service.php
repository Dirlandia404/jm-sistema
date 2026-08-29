<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

//Responsavel pelas consultas relacionadas aos serviçoes
class Service
{
    private PDO $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(array $filters = []): array
    {
        //Retorna os serviços e aplica os filtros informados
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
        ';

        $conditions = [];

        if (!empty($filters["start_date"])) {
            $conditions[] = "DATE(service.created_at) >= :start_date";
        }

        if (!empty($filters["end_date"])) {
            $conditions[] = "DATE(service.created_at) <= :end_date";
        }
        if (!empty($filters["service_name"])) {
            $conditions[] = "service.description LIKE :service_name";
        }

        if (!empty($filters["user_name"])) {
            $conditions[] = "user.name LIKE :user_name";
        }

        if (($filters["status"] ?? "") === "pendente") {
            $conditions[] = "service.finished_at IS NULL";
        }

        if (($filters["status"] ?? "") === "finalizado") {
            $conditions[] = "service.finished_at IS NOT NULL";
        }

        if ($conditions !== []) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY service.created_at DESC";

        $stmt = $this->connection->prepare($sql);

        if (!empty($filters["start_date"])) {
            $stmt->bindValue(
                ":start_date",
                $filters["start_date"],
                PDO::PARAM_STR,
            );
        }

        if (!empty($filters["end_date"])) {
            $stmt->bindValue(":end_date", $filters["end_date"], PDO::PARAM_STR);
        }
        if (!empty($filters["service_name"])) {
            $stmt->bindValue(
                ":service_name",
                "%" . $filters["service_name"] . "%",
                PDO::PARAM_STR,
            );
        }
        if (!empty($filters["user_name"])) {
            $stmt->bindValue(
                ":user_name",
                "%" . $filters["user_name"] . "%",
                PDO::PARAM_STR,
            );
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //Calcula o valor total dos serviços do usuario
    public function getTotalByUserId(int $userId): float
    {
        $sql = '
            SELECT
                COALESCE(SUM(service.price), 0) AS total
            FROM service
            WHERE service.user_id_user = :user_id_user
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":user_id_user", $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (float) $result["total"];
    }
    //Busca os ultimos serviços pendentes do usuario
    public function findLatestPendingByUserId(int $userId): array
    {
        $sql = '
            SELECT
                service.id_service,
                service.description,
                service.price,
                service.created_at
            FROM service
            WHERE service.user_id_user = :user_id_user
                AND service.finished_at IS NULL
            ORDER BY service.created_at DESC
            LIMIT 5
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":user_id_user", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //cadastra um novo serviço para o usuario logado
    public function create(
        string $description,
        string $price,
        int $userId,
    ): bool {
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
        $stmt->bindValue(":description", $description, PDO::PARAM_STR);
        $stmt->bindValue(":price", $price, PDO::PARAM_STR);
        $stmt->bindValue(":user_id_user", $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //Busca serviço por ID
    public function findById(int $serviceId): ?array
    {
        $sql = '
            SELECT
                service.id_service,
                service.description,
                service.price,
                service.finished_at,
                service.commission_user,
                service.user_id_user,
                user.name AS user_name,
                user.email AS user_email
            FROM service
            INNER JOIN user
                ON user.id_user = service.user_id_user
            WHERE service.id_service = :service_id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":service_id", $serviceId, PDO::PARAM_INT);
        $stmt->execute();

        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        return $service ?: null;
    }
    //atualiza dados do serviço
    public function update(
        int $serviceId,
        string $description,
        string $price,
    ): bool {
        $sql = '
            UPDATE service
            SET
                description = :description,
                price = :price
            WHERE id_service = :service_id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(":description", $description, PDO::PARAM_STR);

        $stmt->bindValue(":price", $price, PDO::PARAM_STR);

        $stmt->bindValue(":service_id", $serviceId, PDO::PARAM_INT);

        return $stmt->execute();
    }
    //Grava a finalização e a comissão do serviço
    public function finish(int $serviceId, float $commission): bool
    {
        $sql = '
            UPDATE service
            SET
                finished_at = NOW(),
                commission_user = :commission_user,
                update_at = NOW()
            WHERE id_service = :service_id
                AND finished_at IS NULL
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(
            ":commission_user",
            number_format($commission, 3, ".", ""),
            PDO::PARAM_STR,
        );
        $stmt->bindValue(":service_id", $serviceId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() === 1;
    }
    //exclui um serviço pelo ID
    public function delete(int $serviceId): bool
    {
        $sql = '
            DELETE FROM service
            WHERE id_service = :service_id
        ';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":service_id", $serviceId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
