<?php

declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>

    <!-- Exibe os dados do usuário autenticado. -->
    <p>
        Usuário:
        <?= htmlspecialchars(
            $loggedUser['name'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>

    <p>
        E-mail:
        <?= htmlspecialchars(
            $loggedUser['email'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>

    <!-- Exibe a data atual exigida no enunciado. -->
    <p>Data atual: <?= date('d/m/Y') ?></p>
</body>
</html>