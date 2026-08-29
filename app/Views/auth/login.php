<?php

declare(strict_types=1);

// Recebe a mensagem de erro preparada pelo index.php.
$error = $error ?? null;
$registerSuccess = $registerSuccess ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>
    <main class="login-page">
        <section class="login-card">
            <h1>JM Informática</h1>

            <p class="login-subtitle">
                Acesse o sistema de ordem de serviços
            </p>

            <?php if ($registerSuccess !== null): ?>
                <p class="login-success">
                    <?= htmlspecialchars(
                        $registerSuccess,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            <?php endif; ?>
            <?php if ($error !== null): ?>
                <!-- Exibe o erro de autenticação. -->
                <p class="login-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <!-- Envia as credenciais para a rota de autenticação. -->
            <form action="index.php?route=authenticate" method="POST" class="login-form">
                <div class="form-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Informe seu e-mail" autocomplete="email" required>
                </div>

                <div class="form-field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Informe sua senha" autocomplete="current-password" required>
                </div>

                <button type="submit">Entrar</button>
            </form>
            <p class="login-link">
                Ainda não possui cadastro?

                <a href="index.php?route=user-create">
                    Cadastre-se
                </a>
            </p>
        </section>
    </main>
</body>

</html>