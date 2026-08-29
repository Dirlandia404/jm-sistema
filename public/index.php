<?php

declare(strict_types=1);

use Core\Database;
use App\Models\User;
use App\Models\Service;
use App\Controllers\AuthController;
use App\Controllers\ServiceController;
use App\Services\EmailService;

//inicia sessão
session_start();
//carrega classe de configuração do banco de dados
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ .'/../app/Models/User.php';
require_once __DIR__ .'/../app/Models/Service.php';
require_once __DIR__ .'/../app/Controllers/AuthController.php';
require_once __DIR__ .'/../app/Controllers/ServiceController.php';
require_once __DIR__ . '/../app/Services/EmailService.php';

//carrega o arquivo de configuração do banco de dados
$config = require __DIR__ . '/../config/database.php';

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

} catch (\PDOException $exception) {
    error_log($exception->getMessage());    
    http_response_code(500);
    echo 'Erro ao conectar ao iniciar app: ' ;
    exit;
}

//rotas
$route = $_GET['route'] ?? 'login';

//exibir tela de login
if($route === "login"){
    $error = $_SESSION['login_error'] ?? null;

    $registerSuccess =
        $_SESSION['register_success'] ?? null;

    unset(
        $_SESSION['login_error'],
        $_SESSION['register_success']
    );

    require __DIR__ . '/../app/Views/auth/login.php';
    exit;
}
//Exibe formulario de cadastro de usuario
if($route === "user-create"){
    $registerError =
        $_SESSION['register_error'] ?? null;

    unset($_SESSION['register_error']);

    require __DIR__
        . '/../app/Views/auth/register.php';

    exit;
}

//Processa cadastro de usuario
if($route === 'user-store' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = (string) ($_POST['name'] ?? '');
    $email = (string) ($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    try {
        $registered = $authController->register(
            $name,
            $email,
            $password
        );
    } catch (\PDOException $exception) {
        error_log($exception->getMessage());
        $registered = false;
    }

    if($registered){
        $_SESSION['register_success'] =
            'Cadastro realizado com sucesso. Faça seu login.';

        header('Location: index.php?route=login');
        exit;
    }

    $_SESSION['register_error'] =
        'Não foi possível realizar o cadastro. Verifique os dados informados.';

    header('Location: index.php?route=user-create');
    exit;
}

//processa autenticação
if($route === 'authenticate' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = (string) ($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if($authController->login($email, $password)){
        header('Location: index.php?route=dashboard');
        exit;
    }
    $_SESSION['login_error'] = 'Ops, Email ou Senha inválido';
    header('Location: index.php?route=login');
    exit;
    
}
//exibe formulario de cadastro de serviço
if($route === "service-create"){
    $serviceController->create();
    exit;
}
//processa cadastro de serviço
if($route === 'service-store' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    $serviceController->store();
    exit;
}
//processa exclusao
if($route === 'service-delete'){
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: index.php?route=dashboard');
        exit;
    }
    $serviceController->delete();
    exit;
}
//Processa finalização do serviço
if($route === 'service-finish'){
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: index.php?route=dashboard');
        exit;
    }

    $serviceController->finish();
    exit;
}
//exibir formulario de edição
if($route === "service-edit"){
    $serviceId = (int) ($_GET['id'] ?? 0);
    $serviceController->edit($serviceId);
    exit;
}
//salvar alteração
if ($route === 'service-update') {
    // Aceita somente o envio pelo formulário.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?route=dashboard');
        exit;
    }

    $serviceController->update();
    exit;
}
//exibir tela de dashboard
if($route === "dashboard"){
    if(!isset($_SESSION['user'])){
        header('Location: index.php?route=login');
        exit;
    }
    $loggedUser = $_SESSION['user'];
    $userId = (int) $loggedUser['id_user'];

    $startDate = trim(
        (string) ($_GET['start_date'] ?? '')
    );

    $endDate = trim(
        (string) ($_GET['end_date'] ?? '')
    );

    $serviceName = trim(
        (string) ($_GET['service_name'] ?? '')
    );
    $userName = trim(
        (string) ($_GET['user_name'] ?? '')
    );

    $status = trim(
        (string) ($_GET['status'] ?? '')
    );

    $allowedStatuses = [
        '',
        'pendente',
        'finalizado'
    ];

    if(!in_array($status, $allowedStatuses, true)){
        $_SESSION['service_error'] =
            'Status informado é inválido.';

        header('Location: index.php?route=dashboard');
        exit;
    }

    $isValidDate = static function(string $date): bool{
        if($date === ''){
            return true;
        }

        $parsedDate = \DateTime::createFromFormat(
            '!Y-m-d',
            $date
        );

        return $parsedDate !== false
            && $parsedDate->format('Y-m-d') === $date;
    };

    if(
        !$isValidDate($startDate)
        || !$isValidDate($endDate)
        || (
            $startDate !== ''
            && $endDate !== ''
            && $startDate > $endDate
        )
    ){
        $_SESSION['service_error'] =
            'Período informado é inválido.';

        header('Location: index.php?route=dashboard');
        exit;
    }

    $filters = [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'service_name' => $serviceName,
        'user_name' => $userName,
        'status' => $status
    ];

    $services = $serviceModel->findAll($filters);
    $totalServices = $serviceModel->getTotalByUserId(
        $userId
    );
    $pendingServices =
        $serviceModel->findLatestPendingByUserId(
            $userId
        );

    $serviceSuccess =
        $_SESSION['service_success'] ?? null;

    $serviceError =
        $_SESSION['service_error'] ?? null;

    unset(
        $_SESSION['service_success'],
        $_SESSION['service_error']
    );

    require __DIR__
        . '/../app/Views/dashboard/index.php';

    exit;
}
//exibir tela de logout
if($route === "logout"){
    $authController->logout();
    header('Location: index.php?route=login');
    exit;

}