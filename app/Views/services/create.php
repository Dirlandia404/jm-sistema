<?php

declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar serviço | JM Informática</title>

    <link rel="stylesheet" href="assets/css/service-form.css">
</head>

<body>
    <main class="service-page">
        <section class="service-card">
            <header class="service-header">
                <p class="service-brand">
                    JM Informática
                </p>

                <h1>Cadastrar serviço</h1>

                <p class="service-description">
                    Informe a descrição e o valor do novo serviço.
                    Ele será cadastrado inicialmente como pendente.
                </p>
            </header>

            <form action="index.php?route=service-store" method="POST" class="service-form">
                <div class="form-field">
                    <div class="field-heading">
                        <label for="description">
                            Descrição
                        </label>

                        <span>Obrigatório</span>
                    </div>

                    <input type="text" id="description" name="description" placeholder="Ex.: Manutenção de computador" maxlength="45" autocomplete="off" required autofocus>

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

                        <input type="number" id="price" name="price" placeholder="0,00" min="0.01" step="0.01" inputmode="decimal" required>
                    </div>

                    <small>
                        Utilize um valor maior que zero.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="index.php?route=dashboard" class="secondary-button">
                        Voltar
                    </a>

                    <button type="submit" class="primary-button">
                        Cadastrar serviço
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>