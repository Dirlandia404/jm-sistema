<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | JM Informática</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <p>Logado como:</p>

                <strong>
                    <?= htmlspecialchars(
                        $loggedUser['name'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </strong>

                <span>
                    <?= htmlspecialchars(
                        $loggedUser['email'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>
                <div class="sidebar-total">
                    <p>Valor total dos seus serviços</p>

                    <strong>
                        R$
                        <?= number_format(
                            $totalServices,
                            2,
                            ',',
                            '.'
                        ) ?>
                    </strong>

                    <span>
                        Serviços pendentes e finalizados
                    </span>
                </div>
            </div>

            <nav class="sidebar-navigation" aria-label="Navegação principal">
                <a href="index.php?route=service-create">
                    Cadastrar serviço
                </a>

                <a href="index.php?route=logout">
                    Sair
                </a>
            </nav>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <div>
                    <p>JM Informática</p>
                    <h1>Dashboard</h1>
                </div>

                <time datetime="<?= date('Y-m-d') ?>">
                    <?= date('d/m/Y') ?>
                </time>
            </header>

            <?php if ($serviceSuccess): ?>
                <p class="alert alert-success">
                    <?= htmlspecialchars(
                        $serviceSuccess,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            <?php endif; ?>

            <?php if ($serviceError): ?>
                <p class="alert alert-error">
                    <?= htmlspecialchars(
                        $serviceError,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            <?php endif; ?>

            <section class="summary-grid">
                <article class="summary-card">
                    <div class="summary-card-header">
                        <div>
                            <p class="summary-label">
                                Acompanhamento
                            </p>

                            <h2>Últimos serviços</h2>
                        </div>

                        <span class="pending-count">
                            <?= count($latestServices) ?>
                        </span>
                    </div>

                    <?php if ($latestServices === []): ?>
                        <p class="empty-message">
                            Nenhum serviço cadastrado.
                        </p>
                    <?php else: ?>
                        <ul class="summary-list">
                            <?php foreach ($latestServices as $latestService): ?>
                                <li>
                                    <div>
                                        <strong>
                                            <?= (int) $latestService['id_service'] ?>
                                            -
                                            <?= htmlspecialchars(
                                                $latestService['description'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= htmlspecialchars(
                                                $latestService['user_name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                            ·

                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $latestService['created_at']
                                                )
                                            ) ?>
                                        </span>
                                    </div>

                                    <strong>
                                        R$ <?= number_format(
                                            (float) $latestService['price'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
                <article class="summary-card">
                    <div class="summary-card-header">
                        <div>
                            <p class="summary-label">
                                Acompanhamento
                            </p>
                            <h2>Serviços pendentes</h2>
                        </div>

                        <span class="pending-count">
                            <?= count($pendingServices) ?>
                        </span>
                    </div>

                    <?php if ($pendingServices === []): ?>
                        <p class="empty-message">
                            Você não possui serviços pendentes.
                        </p>
                    <?php else: ?>
                        <ul class="summary-list pending-list">
                            <?php foreach ($pendingServices as $pendingService): ?>
                                <li>
                                    <div>
                                        <strong>
                                            <?= (int) $pendingService['id_service'] ?>
                                            -
                                            <?= htmlspecialchars(
                                                $pendingService['description'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </strong>

                                        <span>
                                            <?= date(
                                                'd/m/Y',
                                                strtotime(
                                                    $pendingService['created_at']
                                                )
                                            ) ?>
                                        </span>
                                    </div>
                                    <strong>
                                        R$ <?= number_format(
                                            (float) $pendingService['price'],
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </section>

            <section class="filters-card">
                <div class="section-heading">
                    <div>
                        <p>Pesquisa</p>
                        <h2>Filtrar serviços</h2>
                    </div>

                    <a href="index.php?route=dashboard" class="clear-filters">
                        Limpar filtros
                    </a>
                </div>

                <form action="index.php" method="GET" class="filters-form">
                    <input type="hidden" name="route" value="dashboard">

                    <div class="filter-field">
                        <label for="service_name">
                            Nome do serviço
                        </label>

                        <input type="search" id="service_name" name="service_name" placeholder="Buscar pela descrição" maxlength="45" value="<?= htmlspecialchars(
                            $filters['service_name'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                    </div>

                    <div class="filter-field">
                        <label for="user_name">
                            Nome do funcionário
                        </label>

                        <input type="search" id="user_name" name="user_name" placeholder="Buscar pelo funcionário" maxlength="150" value="<?= htmlspecialchars(
                            $filters['user_name'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                    </div>

                    <div class="filter-field">
                        <label for="status">
                            Status
                        </label>

                        <select id="status" name="status">
                            <option value="" <?= (
                                $filters['status'] ?? ''
                            ) === ''
                                ? 'selected'
                                : '' ?>> Todos </option>

                            <option value="pendente" <?= (
                                $filters['status'] ?? ''
                            ) === 'pendente'
                                ? 'selected'
                                : '' ?>> Pendente </option>

                            <option value="finalizado" <?= (
                                $filters['status'] ?? ''
                            ) === 'finalizado'
                                ? 'selected'
                                : '' ?>> Finalizado </option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="start_date">
                            Data inicial
                        </label>

                        <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars(
                            $filters['start_date'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                    </div>

                    <div class="filter-field">
                        <label for="end_date">
                            Data final
                        </label>

                        <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars(
                            $filters['end_date'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>">
                    </div>

                    <div class="filter-actions">
                        <button type="submit">
                            Filtrar
                        </button>
                    </div>
                </form>
            </section>

            <section class="services-card">
                <div class="section-heading">
                    <div>
                        <p>Visão geral</p>
                        <h2>Serviços</h2>
                    </div>

                    <span class="services-count">
                        <?= count($services) ?>
                        serviço(s)
                    </span>
                </div>

                <?php if ($services === []): ?>
                    <p class="empty-message">
                        Nenhum serviço encontrado.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="services-table">
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
                                <?php foreach (
                                    $services as $service
                                ): ?>
                                    <tr>
                                        <td data-label="ID">
                                            <?= (int) $service[
                                                'id_service'
                                            ] ?>
                                        </td>

                                        <td data-label="Descrição">
                                            <?= htmlspecialchars(
                                                $service[
                                                    'description'
                                                ],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <td data-label="Valor">
                                            R$ <?= number_format(
                                                (float) $service[
                                                    'price'
                                                ],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>
                                        </td>

                                        <td data-label="Status">
                                            <span class="status-badge <?= (
                                                $service[
                                                    'finished_at'
                                                ] === null
                                            )
                                                ? 'status-pending'
                                                : 'status-finished' ?>">
                                                <?= htmlspecialchars(
                                                    $service['status'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </td>

                                        <td data-label="Funcionário">
                                            <?= htmlspecialchars(
                                                $service['user_name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>

                                        <td data-label="Ações">
                                            <div class="service-actions">
                                                <a href="index.php?route=service-edit&id=<?= (int) $service[
                                                    'id_service'
                                                ] ?>" class="action-button action-edit">
                                                    Alterar
                                                </a>

                                                <?php if (
                                                    $service[
                                                        'finished_at'
                                                    ] === null
                                                ): ?>
                                                    <form action="index.php?route=service-finish" method="POST" class="finish-service-form">
                                                        <input type="hidden" name="service_id" value="<?= (int) $service[
                                                            'id_service'
                                                        ] ?>">

                                                        <button type="submit" class="action-button action-finish">
                                                            Finalizar
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <form action="index.php?route=service-delete" method="POST" class="delete-service-form">
                                                    <input type="hidden" name="service_id" value="<?= (int) $service[
                                                        'id_service'
                                                    ] ?>">

                                                    <button type="submit" class="action-button action-delete">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="assets/js/services.js"></script>
</body>

</html>