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

    <!--Cadastro novo serviço -->
    <a href="index.php?route=service-create">Cadastrar novo serviço</a>
    <?php if ($serviceSuccess): ?>
        <p><?= htmlspecialchars($serviceSuccess) ?></p>
    <?php endif; ?>

    <?php if ($serviceError): ?>
        <p><?= htmlspecialchars($serviceError) ?></p>
    <?php endif; ?>

    <!-- Exibe a data atual exigida no enunciado. -->
    <p>Data atual: <?= date('d/m/Y') ?></p>

    <h2>Serviços</h2>

    <?php if ($services === []): ?>
        <!-- Exibido quando não existem serviços cadastrados. -->
        <p>Nenhum serviço encontrado.</p>
    <?php else: ?>
        <!-- Exibe os serviços retornados pelo Model. -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Funcionário</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td>
                            <?= (int) $service['id_service'] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $service['description'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            R$ <?= number_format(
                                (float) $service['price'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $service['status'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $service['user_name'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td>
                            <a
                                href="index.php?route=service-edit&id=<?=(int) $service['id_service']?>"
                            >
                                Alterar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>