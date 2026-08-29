<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Service;
use App\Services\EmailService;

//controla as ações relacionadas aos serviçoes
class ServiceController
{
    private Service $serviceModel;
    private EmailService $emailService;

    //Recebe o Model e o serviço de email
    public function __construct(
        Service $serviceModel,
        EmailService $emailService,
    ) {
        $this->serviceModel = $serviceModel;
        $this->emailService = $emailService;
    }
    public function create(): void
    {
        //verifica se o usuario esta logado
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }

        //carrega a tela de cadastro de serviço
        require __DIR__ . "/../Views/services/create.php";
    }
    //processa o cadastro de serviço
    public function store(): void
    {
        //verifica usuario logado
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }
        //recebe os dados do formulario
        $description = trim((string) ($_POST["description"] ?? ""));
        $price = trim((string) ($_POST["price"] ?? ""));
        $price = str_replace(",", ".", $price);

        //valida campos obrigatorios
        if ($description === "" || !is_numeric($price) || (float) $price <= 0) {
            $_SESSION["service_error"] = "Não foi possivel cadastrar serviço";
            header("Location: index.php?route=dashboard");
            exit();
        }
        $userId = (int) $_SESSION["user"]["id_user"];
        try {
            $create = $this->serviceModel->create(
                $description,
                $price,
                $userId,
            );
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $create = false;
        }
        if ($create) {
            $_SESSION["service_success"] = "Serviço cadastrado com sucesso";
        } else {
            $_SESSION["service_error"] = "Não foi possivel cadastrar o serviço";
        }
        header("Location: index.php?route=dashboard");
        exit();
    }
    //exibe formulario de edição de serviço
    public function edit(int $serviceId): void
    {
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }

        $service = $this->serviceModel->findById($serviceId);

        if ($service === null) {
            $_SESSION["service_error"] = "Serviço não encontrado";
            header("Location: index.php?route=dashboard");
            exit();
        }

        require __DIR__ . "/../Views/services/edit.php";
    }
    // Processa a alteração do serviço.
    public function update(): void
    {
        // Verifica se o usuário está logado.
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }

        // Recebe os dados do formulário.
        $serviceId = (int) ($_POST["service_id"] ?? 0);
        $description = trim((string) ($_POST["description"] ?? ""));
        $price = trim((string) ($_POST["price"] ?? ""));

        $price = str_replace(",", ".", $price);

        // Valida os dados.
        if (
            $serviceId <= 0 ||
            $description === "" ||
            !is_numeric($price) ||
            (float) $price <= 0
        ) {
            $_SESSION["service_error"] = "Não foi possível alterar o serviço.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        // Verifica se o serviço existe.
        if ($this->serviceModel->findById($serviceId) === null) {
            $_SESSION["service_error"] = "Serviço não encontrado.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        try {
            $updated = $this->serviceModel->update(
                $serviceId,
                $description,
                $price,
            );
        } catch (\PDOException $exception) {
            error_log($exception->getMessage());
            $updated = false;
        }

        if ($updated) {
            $_SESSION["service_success"] = "Serviço alterado com sucesso.";
        } else {
            $_SESSION["service_error"] = "Não foi possível alterar o serviço.";
        }

        header("Location: index.php?route=dashboard");
        exit();
    }
    //Procesa exclusão
    public function delete(): void
    {
        // Verifica se o usuário está logado.
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }
        $serviceId = (int) ($_POST["service_id"] ?? 0);

        if ($serviceId <= 0) {
            $_SESSION["service_error"] = "Serviço invalido";

            header("Location: index.php?route=dashboard");
            exit();
        }
        // Verifica se o serviço existe.
        if ($this->serviceModel->findById($serviceId) === null) {
            $_SESSION["service_error"] = "Serviço não encontrado.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        try {
            $deleted = $this->serviceModel->delete($serviceId);
        } catch (\PDOException $exception) {
            error_log($exception->getMessage());
            $deleted = false;
        }

        if ($deleted) {
            $_SESSION["service_success"] = "Serviço excluído com sucesso.";
        } else {
            $_SESSION["service_error"] = "Não foi possível excluir o serviço.";
        }

        header("Location: index.php?route=dashboard");
        exit();
    }

    //Processa a finalização do serviço
    public function finish(): void
    {
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?route=login");
            exit();
        }

        $serviceId = (int) ($_POST["service_id"] ?? 0);

        if ($serviceId <= 0) {
            $_SESSION["service_error"] = "Serviço inválido.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        $service = $this->serviceModel->findById($serviceId);

        if ($service === null) {
            $_SESSION["service_error"] = "Serviço não encontrado.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        if ($service["finished_at"] !== null) {
            $_SESSION["service_error"] = "Este serviço já foi finalizado.";

            header("Location: index.php?route=dashboard");
            exit();
        }

        $commission = $this->calculateCommission((float) $service["price"]);

        try {
            $finished = $this->serviceModel->finish($serviceId, $commission);
        } catch (\PDOException $exception) {
            error_log($exception->getMessage());
            $finished = false;
        }

        if ($finished) {
            try {
                $emailSent = $this->emailService->sendServiceFinished(
                    (string) $service["user_email"],
                    (string) $service["user_name"],
                    (string) $service["description"],
                    (float) $service["price"],
                    $commission,
                );
            } catch (\Throwable $exception) {
                error_log($exception->getMessage());
                $emailSent = false;
            }

            if ($emailSent) {
                $_SESSION["service_success"] =
                    "Serviço finalizado e e-mail enviado com sucesso.";
            } else {
                $_SESSION["service_success"] =
                    "Serviço finalizado, mas não foi possível enviar o e-mail.";
            }
        } else {
            $_SESSION["service_error"] =
                "Não foi possível finalizar o serviço.";
        }

        header("Location: index.php?route=dashboard");
        exit();
    }

    //Calcula a comissão de acordo com o valor do serviço
    private function calculateCommission(float $price): float
    {
        if ($price > 10000) {
            return round($price * 0.2, 3);
        }

        if ($price > 1000) {
            return round($price * 0.1, 3);
        }

        return round($price * 0.05, 3);
    }
}
