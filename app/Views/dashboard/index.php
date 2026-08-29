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
    <!-- Exibe o valor total dos serviços do usuário logado. -->
    <h2>Valor total dos seus serviços</h2>

    <p>
        <strong>
            R$ <?= number_format(
                $totalServices,
                2,
                ',',
                '.'
            ) ?>
        </strong>
    </p>

    <!-- Exibe os últimos serviços pendentes do usuário. -->
    <h2>Últimos serviços pendentes</h2>

    <?php if ($pendingServices === []): ?>
        <p>Você não possui serviços pendentes.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($pendingServices as $pendingService): ?>
                <li>
                    <strong>
                        <?= htmlspecialchars(
                            $pendingService['description'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>

                    — R$ <?= number_format(
                        (float) $pendingService['price'],
                        2,
                        ',',
                        '.'
                    ) ?>

                    — <?= date(
                        'd/m/Y',
                        strtotime($pendingService['created_at'])
                    ) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

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
                                href="index.php?route=service-edit&id=<?= (int) $service['id_service'] ?>"
                            >
                                Alterar
                            </a>

                            <form
                                action="index.php?route=service-delete"
                                method="POST"
                                class="delete-service-form"
                            >
                                <input
                                    type="hidden"
                                    name="service_id"
                                    value="<?= (int) $service['id_service'] ?>"
                                >

                                <button type="submit">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <script src="assets/js/services.js"></script>
</body>
</html>