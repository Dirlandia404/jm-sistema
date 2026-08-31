<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;

//Controla a exibição e os filtros do dashboard
class DashboardController
{
    private Service $serviceModel;

    public function __construct(
        Service $serviceModel
    ) {
        $this->serviceModel = $serviceModel;
    }

    //Exibe a tela do dashboard
    public function index(): void
    {
        if (!isset($_SESSION["user"])) {
            header(
                "Location: index.php?route=login"
            );
            exit();
        }

        $loggedUser = $_SESSION["user"];
        $userId = (int) $loggedUser["id_user"];

        $startDate = trim(
            (string) ($_GET["start_date"] ?? "")
        );

        $endDate = trim(
            (string) ($_GET["end_date"] ?? "")
        );

        $serviceName = trim(
            (string) ($_GET["service_name"] ?? "")
        );

        $userName = trim(
            (string) ($_GET["user_name"] ?? "")
        );

        $status = trim(
            (string) ($_GET["status"] ?? "")
        );

        $allowedStatuses = [
            "",
            "pendente",
            "finalizado",
        ];

        if (
            !in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $_SESSION["service_error"] =
                "Status informado é inválido.";

            header(
                "Location: index.php?route=dashboard"
            );

            exit();
        }

        $isValidDate = static function (string $date): bool {
            if ($date === "") {
                return true;
            }

            $parsedDate =
                \DateTime::createFromFormat(
                    "!Y-m-d",
                    $date
                );

            return $parsedDate !== false
                && $parsedDate->format("Y-m-d")
                === $date;
        };

        if (
            !$isValidDate($startDate)
            || !$isValidDate($endDate)
            || (
                $startDate !== ""
                && $endDate !== ""
                && $startDate > $endDate
            )
        ) {
            $_SESSION["service_error"] =
                "Período informado é inválido.";

            header(
                "Location: index.php?route=dashboard"
            );

            exit();
        }

        $filters = [
            "start_date" => $startDate,
            "end_date" => $endDate,
            "service_name" => $serviceName,
            "user_name" => $userName,
            "status" => $status,
        ];

        $services =
            $this->serviceModel->findAll(
                $filters
            );

        $latestServices =
            $this->serviceModel->findLatest();

        $totalServices =
            $this->serviceModel
                ->getTotalByUserId($userId);

        $pendingServices =
            $this->serviceModel
                ->findLatestPendingByUserId(
                    $userId
                );

        $serviceSuccess =
            $_SESSION["service_success"] ?? null;

        $serviceError =
            $_SESSION["service_error"] ?? null;

        unset(
            $_SESSION["service_success"],
            $_SESSION["service_error"]
        );

        require __DIR__
            . "/../Views/dashboard/index.php";

        exit();
    }
}