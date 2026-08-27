<?php

declare(strict_types=1);

// Recebe a mensagem de erro preparada pelo index.php.
$error = $error ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if ($error !== null): ?>
        <!-- Exibe o erro de autenticação. -->
        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <!-- Envia as credenciais para a rota de autenticação. -->
    <form action="index.php?route=authenticate" method="post">
        <input
            type="email"
            name="email"
            placeholder="E-mail"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Senha"
            required
        >

        <button type="submit">Entrar</button>
    </form>
</body>
</html>