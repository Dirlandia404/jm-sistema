<?php

declare(strict_types=1);

// Recebe a mensagem de erro preparada pelo index.php.
$error = $error ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro</title>
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>
    <main class="login-page">
        <section class="login-card">
            <h1>Criar conta</h1>

            <p class="login-subtitle">
                Cadastre-se para acessar o sistema
            </p>

            <?php if ($registerError !== null): ?>
                <p class="login-error">
                    <?= htmlspecialchars(
                        $registerError,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            <?php endif; ?>

            <form action="index.php?route=user-store" method="POST" class="login-form">
                <div class="form-field">
                    <label for="name">Nome</label>

                    <input type="text" id="name" name="name" placeholder="Informe seu nome" maxlength="150" autocomplete="name" required>
                </div>

                <div class="form-field">
                    <label for="email">E-mail</label>

                    <input type="email" id="email" name="email" placeholder="Informe seu e-mail" maxlength="100" autocomplete="email" required>
                </div>

                <div class="form-field">
                    <label for="password">Senha</label>

                    <input type="password" id="password" name="password" placeholder="Informe sua senha" maxlength="45" autocomplete="new-password" required>
                </div>

                <button type="submit">
                    Cadastrar
                </button>
            </form>

            <p class="login-link">
                Já possui cadastro?

                <a href="index.php?route=login">
                    Entrar
                </a>
            </p>
        </section>
    </main>
</body>

</html>