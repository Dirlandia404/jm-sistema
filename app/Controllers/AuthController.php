<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

//Controle de fluxo de autenticação do usuario
class AuthController
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    //Verifica as credenciais e inicia  sessão
    public function login(string $email, string $password): bool
    {
        $email = trim($email);

        if (
            $email === "" ||
            $password === "" ||
            filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false
        ) {
            return false;
        }
        $user = $this->userModel->findbyEmail($email);
        if ($user === null) {
            return false;
        }

        if (!hash_equals((string) $user["password"], $password)) {
            return false;
        }

        $_SESSION["user"] = [
            "id_user" => $user["id_user"],
            "name" => $user["name"],
            "email" => $user["email"],
        ];

        return true;
    }

    //Valida e cadastra um novo usuario
    public function register(string $name, string $email, string $password): bool
    {
        $name = trim($name);
        $email = trim($email);

        if (
            $name === "" ||
            $email === "" ||
            $password === "" ||
            filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            return false;
        }

        if ($this->userModel->emailExists($email)) {
            return false;
        }

        return $this->userModel->create($name, $email, $password);
    }

    //Exibe a tela de login
    public function showLogin(): void
    {
        $error =
            $_SESSION["login_error"] ?? null;

        $registerSuccess =
            $_SESSION["register_success"] ?? null;

        unset(
            $_SESSION["login_error"],
            $_SESSION["register_success"]
        );

        require __DIR__
            . "/../Views/auth/login.php";

        exit();
    }

    //Exibe a tela de cadastro de usuário
    public function showRegister(): void
    {
        $registerError =
            $_SESSION["register_error"] ?? null;

        unset($_SESSION["register_error"]);

        require __DIR__
            . "/../Views/auth/register.php";

        exit();
    }
    //Processa o cadastro de usuário
    public function storeRegistration(): void
    {
        $name =
            (string) ($_POST["name"] ?? "");

        $email =
            (string) ($_POST["email"] ?? "");

        $password =
            (string) ($_POST["password"] ?? "");

        try {
            $registered = $this->register(
                $name,
                $email,
                $password
            );
        } catch (\PDOException $exception) {
            error_log($exception->getMessage());
            $registered = false;
        }

        if ($registered) {
            $_SESSION["register_success"] =
                "Cadastro realizado com sucesso. "
                . "Faça seu login.";

            header(
                "Location: index.php?route=login"
            );

            exit();
        }

        $_SESSION["register_error"] =
            "Não foi possível realizar o cadastro. "
            . "Verifique os dados informados.";

        header(
            "Location: index.php?route=user-create"
        );

        exit();
    }
    //Processa a autenticação
    public function authenticate(): void
    {
        $email =
            (string) ($_POST["email"] ?? "");

        $password =
            (string) ($_POST["password"] ?? "");

        if ($this->login($email, $password)) {
            header(
                "Location: index.php?route=dashboard"
            );

            exit();
        }

        $_SESSION["login_error"] =
            "Ops, Email ou Senha inválido";

        header(
            "Location: index.php?route=login"
        );

        exit();
    }
    //encerra sessão
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
