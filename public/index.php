<?php

declare(strict_types=1);

use Core\Database;
use App\Models\User;
use App\Models\Service;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Controllers\DashboardController;
use App\Services\EmailService;

session_start();

require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../app/Models/User.php";
require_once __DIR__ . "/../app/Models/Service.php";
require_once __DIR__ . "/../app/Controllers/AuthController.php";
require_once __DIR__ . "/../app/Controllers/ServiceController.php";
require_once __DIR__ . "/../app/Controllers/DashboardController.php";
require_once __DIR__ . "/../app/Services/EmailService.php";

$config = require __DIR__ . "/../config/database.php";

try {
    $db = new Database($config);
    $connection = $db->getConnection();
    $userModel = new User($connection);
    $serviceModel = new Service($connection);
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

$route = $_GET["route"] ?? "login";

if ($route === "login") {
    $authController->showLogin();
}

if ($route === "user-create") {
    $authController->showRegister();
}

if ($route === "user-store" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $authController->storeRegistration();
}

if ($route === "authenticate" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $authController->authenticate();
}

if ($route === "service-create") {
    $serviceController->create();
    exit();
}


if ($route === "service-store" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceController->store();
    exit();
}

if ($route === "service-delete") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }
    $serviceController->delete();
    exit();
}

if ($route === "service-finish") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }

    $serviceController->finish();
    exit();
}

if ($route === "service-edit") {
    $serviceId = (int) ($_GET["id"] ?? 0);
    $serviceController->edit($serviceId);
    exit();
}

if ($route === "service-update") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php?route=dashboard");
        exit();
    }

    $serviceController->update();
    exit();
}

if ($route === "dashboard") {
    $dashboardController->index();
}

if ($route === "logout") {
    $authController->logout();
    header("Location: index.php?route=login");
    exit();
}
