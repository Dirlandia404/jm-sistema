<?php

declare(strict_types=1);

use Core\Database;
use App\Models\User;
use App\Models\Service;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Controllers\DashboardController;
use App\Services\EmailService;

//inicia sessão
session_start();
//carrega classe de configuração do banco de dados
require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../app/Models/User.php";
require_once __DIR__ . "/../app/Models/Service.php";
require_once __DIR__ . "/../app/Controllers/AuthController.php";
require_once __DIR__ . "/../app/Controllers/ServiceController.php";
require_once __DIR__ . "/../app/Controllers/DashboardController.php";
require_once __DIR__ . "/../app/Services/EmailService.php";

//carrega o arquivo de configuração do banco de dados
$config = require __DIR__ . "/../config/database.php";

//conecta ao banco de dados
try {
    //cria a conexaõ
    $db = new Database($config);
    $connection = $db->getConnection();
    //cria o Model
    $userModel = new User($connection);
    $serviceModel = new Service($connection);
    //cria o Controller recebendo o Model
    $authController = new AuthController($userModel);
    $emailService = new EmailService();
    $serviceController = new ServiceController($serviceModel, $emailService);
    $dashboardController = new DashboardController($serviceModel);
} catch (\PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    echo "Erro ao conectar ao iniciar app: ";
    exit();
}

//rotas
$route = $_GET["route"] ?? "login";

//Exibe a tela de login
if ($route === "login") {
    $authController->showLogin();
}

//Exibe o formulário de cadastro
if ($route === "user-create") {
    $authController->showRegister();
}
//Processa o cadastro de usuário
if (
    $route === "user-store"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $authController->storeRegistration();
}

//Processa a autenticação
if (
    $route === "authenticate"
    && $_SERVER["REQUEST_METHOD"] === "POST"
) {
    $authController->authenticate();
}
//exibe formulario de cadastro de serviço
if ($route === "service-create") {
    $serviceController->create();
    exit();
}

//processa cadastro de serviço
if ($route === "service-store" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceController->store();
    exit();
}
//processa exclusao
if ($route === "service-delete") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }
    $serviceController->delete();
    exit();
}
//Processa finalização do serviço
if ($route === "service-finish") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }

    $serviceController->finish();
    exit();
}
//exibir formulario de edição
if ($route === "service-edit") {
    $serviceId = (int) ($_GET["id"] ?? 0);
    $serviceController->edit($serviceId);
    exit();
}
//salvar alteração
if ($route === "service-update") {
    // Aceita somente o envio pelo formulário.
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }

    $serviceController->update();
    exit();
}
//Exibe a tela do dashboard
if ($route === "dashboard") {
    $dashboardController->index();
}
//exibir tela de logout
if ($route === "logout") {
    $authController->logout();
    header("Location: index.php?route=login");
    exit();
}
