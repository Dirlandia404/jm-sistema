<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar serviço | JM Informática</title>

    <link rel="stylesheet" href="assets/css/service-form.css">
</head>

<body>
    <main class="service-page">
        <section class="service-card">
            <header class="service-header">
                <p class="service-brand">
                    JM Informática
                </p>

                <h1>Editar serviço</h1>

                <p class="service-description">
                    Atualize os dados do serviço
                    #<?= (int) $service['id_service'] ?>.
                </p>
            </header>

            <form action="index.php?route=service-update" method="POST" class="service-form">
                <input type="hidden" name="service_id" value="<?= (int) $service['id_service'] ?>">

                <div class="form-field">
                    <div class="field-heading">
                        <label for="description">
                            Descrição
                        </label>

                        <span>Obrigatório</span>
                    </div>

                    <input type="text" id="description" name="description" maxlength="45" autocomplete="off" value="<?= htmlspecialchars(
                        $service['description'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>" required autofocus>

                    <small>
                        Informe resumidamente o serviço realizado.
                    </small>
                </div>

                <div class="form-field">
                    <div class="field-heading">
                        <label for="price">
                            Valor do serviço
                        </label>

                        <span>Obrigatório</span>
                    </div>

                    <div class="price-field">
                        <span>R$</span>

                        <input type="number" id="price" name="price" min="0.01" step="0.01" inputmode="decimal" value="<?= number_format(
                            (float) $service['price'],
                            2,
                            '.',
                            ''
                        ) ?>" required>
                    </div>

                    <small>
                        Utilize um valor maior que zero.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="index.php?route=dashboard" class="secondary-button">
                        Cancelar
                    </a>

                    <button type="submit" class="primary-button">
                        Salvar alterações
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>