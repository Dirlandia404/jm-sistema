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
}