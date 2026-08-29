<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço</title>
</head>
<body>
    <h2>Editar Serviço</h2>

    <form action="index.php?route=service-update" method="POST">
        <input
            type="hidden"
            name="service_id"
            value="<?=(int) $service['id_service']?>"
        >
        <label for="description">Descriçao:</label>
        <input 
            type="text" 
            name="description" 
            id="description" 
            value="<?= htmlspecialchars(
                $service['description'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            required
        >
        <br>
        <label for="price">Preço:</label>
        <input
            type="number"
            id="price"
            name="price"
            min="0.01"
            step="0.01"
            value="<?= number_format(
                (float) $service['price'],
                2,
                '.',
                ''
            ) ?>"
            required
        >
        <br>
        <button type="submit">Salvar</button>
        <a href="index.php?route=dashboard">Voltar</a>
    </form>
</body>
</html>