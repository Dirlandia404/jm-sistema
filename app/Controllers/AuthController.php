<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

//controle de fluxo de autenticação do usuario
class AuthController
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    //verifica as credenciais e inicia  sessão
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

        //Guarda os dados sem a senha
        $_SESSION["user"] = [
            "id_user" => $user["id_user"],
            "name" => $user["name"],
            "email" => $user["email"],
        ];

        return true;
    }

    //Valida e cadastra um novo usuario
    public function register(
        string $name,
        string $email,
        string $password,
    ): bool {
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

    //encerra sessão
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
